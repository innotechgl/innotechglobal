<?php

global $engine;
require_once($engine->path .
    'core/users/user_item/abstract/user_item_class.php');

class user_item_class extends user_item
{

    /**
     *
     * @var unknown_type
     */
    protected $user_type = "standard";
    // Password length
    protected $min_password_legth = 6;
    protected $max_password_legth = 13;

    public function __construct()
    {
        // Set user type
        $this->set_user_type(self::USER_TYPE_STANDARD_USER);
        // Set extended table
        $this->extended_table = 'user_profile';
    }

    // ID
    public function set_id($id)
    {
        $this->id = (int)$id;
    }

    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param String $mail
     * @param String $password
     * @return bool
     */
    public function login($mail, $password)
    {
        global $engine;
        // GET SALT
        $query = "SELECT salt FROM " . $this->table . " WHERE mail LIKE
		'" . $mail . "' LIMIT 0, 1;";
        $engine->dbase->query($query);
        if (count($engine->dbase->rows) <= 0) {
            return false;
            exit();
        }
        $salt = $engine->dbase->rows[0]['salt'];
        $hashed_password = sha1($password . $salt);

        $query = "SELECT COUNT(*) as count, id, username FROM " . $this->table .
            " WHERE mail LIKE '" . $mail . "' AND password='" . $hashed_password .
            "' AND active=1;";

        $engine->dbase->query($query);
        $user = $engine->dbase->rows[0];
        if ($engine->dbase->rows[0]['count'] > 0) {
            $this->write_to_session($user["id"], $user["username"]);
            $this->clear_login_attempts();
            $this->load_user($user['id']);
            return true;
        } else {
            // Add new LOGIN attempt
            $this->add_login_attempt();
            // Add protect wild login attempts protection
            $this->protect_wild_login_attempts();
            return false;
        }
    }

    /**
     * Force login
     *
     */
    public function forceLogin($mail)
    {
        global $engine;
        $this->set_mail($mail);
        $query = "SELECT COUNT(*) as count, id, username FROM " . $this->table .
            " WHERE mail LIKE '" . $this->get_mail() . "' AND active=1;";
        $engine->dbase->query($query);
        if ($engine->dbase->rows[0]['count'] > 0) {
            $this->id = $engine->dbase->rows[0]['id'];
            $this->username = $engine->dbase->rows[0]['username'];
            $token = $this->createToken();
            $this->write_loged_in_user($token);
            $this->write_to_session($this->id, $this->username);
            $this->clear_login_attempts();
            return true;
        } else {
            // Add new LOGIN attempt
            $this->add_login_attempt();
            // Add protect wild login attempts protection
            $this->protect_wild_login_attempts();
            return false;
        }
    }

    public function logout()
    {
        $this->remove_loged_user();
    }

    /**
     * Load user from DB
     * @param int $id
     * @global $engine
     */
    public function load_user($id)
    {
        global $engine;
        $this->set_id($id);
        $query = "SELECT
`user`.id,
`user`.username,
`user`.`password`,
`user`.created,
`user`.creator,
`user`.modified,
`user`.modifier,
`user`.active,
`user`.`key`,
`user_groups`.`id` AS group_id,
`user_groups`.`name` AS group_name,
`user_groups`.`type` AS group_type,
`user_groups`.`description` AS group_description,
`user_groups`.`active` AS group_active,
user_groups.id,
user_groups.`name`
FROM
`user`
LEFT JOIN rel_user_group ON `user`.id = rel_user_group.id_rel_user
LEFT JOIN user_groups ON user_groups.id = rel_user_group.id_rel_group
WHERE
`user`.id = " . (int)$this->get_id();
        // Do query
        $engine->dbase->query($query);
        $rows = $engine->dbase->rows;
        // Count rows
        if (count($rows) > 0) {
            // Set data
            $this->set_username($rows[0]['username']);
            $this->set_active($rows[0]['active']);
            $this->key = $rows[0]['key'];
            // Go through rows and ad groups
            for ($i = 0; $i < count($rows); $i++) {
                // Add group
                $this->add_group($rows[$i]['group_id'], $rows[$i]['group_name'], $rows[$i]['group_description'], $rows[$i]['group_type']);
                if ($rows[$i]['group_type'] == 2) {
                    $this->set_is_admin(true);
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Change password
     */
    public function change_password($id = 0, $password = '')
    {
        global $engine;
        $salt = time();
        $hashed_password = sha1($password . $salt);
        $query = "UPDATE " . $this->table . " SET password='" . $hashed_password .
            "', salt='" . $salt . "' WHERE id=" . $id;
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function generateRandpassword($size = 9, $power = 0)
    {
        $vowels = 'aeuy';
        $randconstant = 'bdghjmnpqrstvz';
        if ($power & 1) {
            $randconstant .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($power & 2) {
            $vowels .= "AEUY";
        }
        if ($power & 4) {
            $randconstant .= '23456789';
        }
        if ($power & 8) {
            $randconstant .= '@#$%';
        }
        $Randpassword = '';
        $alt = time() % 2;
        for ($i = 0; $i < $size; $i++) {
            if ($alt == 1) {
                $Randpassword .= $randconstant[(rand() % strlen($randconstant))];
                $alt = 0;
            } else {
                $Randpassword .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $Randpassword;
    }

    /*
     * @param string $name
     */
    public function check_user_name_existance($name = '')
    {
        global $engine;
        $query = "SELECT COUNT(*) as num_of_uname FROM " . $this->table .
            " WHERE username LIKE '" . $name . "'";
        $engine->dbase->query($query);
        $result = $engine->dbase->rows;
        if ($result[0]['num_of_uname'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check password
     */
    public function check_password($password = '')
    {
        global $engine;
        // GET SALT
        $query = "SELECT salt FROM " . $this->table . " WHERE id='" . $this->get_id() .
            "' LIMIT 0, 1;";
        $engine->dbase->query($query);
        if (count($engine->dbase->rows) <= 0) {
            return false;
            exit();
        }
        $salt = $engine->dbase->rows[0]['salt'];
        $hashed_password = sha1($password . $salt);
        $query = "SELECT COUNT(*) as count, id, username FROM " . $this->table .
            " WHERE id='" . $this->get_id() . "' AND password='" . $hashed_password . "';";
        $engine->dbase->query($query);
        if ($engine->dbase->rows[0]['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if user is loged in
     * @return boolean
     */
    public function is_loged_in()
    {
        // Check if we have registered session
        if (!isset($_SESSION['users']['id'])) {
            return false;
            exit();
        } // Check if we have user id
        elseif ($_SESSION['users']['id'] <= 0) {
            return false;
            exit();
        } else {
            return true;
            exit();
        }
    }

    /**
     * Register user
     * @return bool
     */
    public function register($mail, $password, $password_again)
    {
        global $engine;

        $arr_vars = array(
            "mail" => $mail,
            "password" => $password,
            "password_again" => $password_again);

        // Check existance of user name
        if ($this->check_mail_existance($arr_vars['mail'])) {
            $engine->log->add_error('user', 'mail exists.', 'mail_existance');
        }
        // Check passwords
        if ($arr_vars['password'] !== $arr_vars['password_again']) {
            $engine->log->add_error('user', 'passwords doesn\'t match', 'matching_passwords');
        }
        // Check if password to small
        if (strlen($arr_vars['password']) < $this->min_password_legth) {
            $engine->log->add_error('user', 'Password to small', 'password_length');
        }
        // Check if password to long
        if (strlen($arr_vars['password']) > $this->max_password_legth) {
            $engine->log->add_error('user', 'Password too long', 'password_length');
        }
        // Check if we have errors
        if (isset($engine->log->error_log['user'])) {
            return false;
        }
        $user_item = new user_item_class();
        $user_item->set_mail($arr_vars['mail']);
        $user_item->set_salt();
        $user_item->set_password($arr_vars['password']);
        $user_item->set_key($user_item->generate_key());

        // Set query
        $query = "INSERT INTO " . $this->table .
            "( mail, password, created, creator, active, salt, `key` )
		VALUES ( '" . $user_item->mail . "', '" . $user_item->get_password() .
            "', NOW(),
		0, 0, '" . $user_item->get_salt() . "', '" . $user_item->get_key() . "')";

        // Do Query
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            $engine->user_groups->add_user_to_group($this->id, $engine->settings->
            user_groups->default);
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $key
     */
    public function activate_registration($key = '')
    {
        global $engine;
        $query = "UPDATE " . $this->table . " SET active=1 WHERE `key` LIKE '" . $key .
            "' LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            $query = "UPDATE " . $this->table . " SET `key`='" . rand(0, 100000000000) .
                "' WHERE `key` LIKE '" . $key . "' LIMIT 1;";
            if ($engine->dbase->insertQuery($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function add_login_attempt()
    {
        global $engine;
        $query = "UPDATE user SET login_attempts=login_attempts+1 WHERE username LIKE '" .
            $this->get_username() . "'";
        $result = $engine->dbase->insertQuery($query);
        return $result;
    }

    /**
     * Clear login attempts
     */
    private function clear_login_attempts()
    {
        global $engine;
        $query = "UPDATE user SET login_attempts=0 WHERE username LIKE '" . $this->
            username . "' LIMIT 1;";
        $result = $engine->dbase->insertQuery($query);
        return $result;
    }

    /**
     * Protect wild login attempts
     */
    private function protect_wild_login_attempts()
    {
        global $engine;
        // Get login attempts
        if ($this->get_login_attempts($this->get_username())) {
            $query = "UPDATE user SET active=0
			WHERE username LIKE
			'" . $this->username . "' LIMIT 1;";
            $engine->dbase->insertQuery($query);
            // Disable IP in .htaccess
            $engine->security->deny_ip();
            die();
        }
    }

    /**
     *
     * @param string $username
     * @return boolean
     */
    private function get_login_attempts($username)
    {
        global $engine;
        $this->set_username($username);
        $query = "SELECT login_attempts FROM user WHERE username LIKE '" . $this->
            username . "'";
        $user = $engine->dbase->query($query);
        if (count($user) > 0) {
            if ($user[0]['login_attempts'] >= $this->get_allowed_login_attempts()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function write_loged_in_user()
    {
        global $engine;
        $this->remove_loged_user();
        $query = "INSERT INTO " . $this->table_loged_in .
            " (username,id_user,time,last_active,ip, session_id) VALUES ('" . $this->
            username . "','" . $this->id . "',NOW(),NOW(),'" . $_SERVER["REMOTE_ADDR"] .
            "','" . session_id() . "');";
        $_SESSION['users']['id'] = $this->id;
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes loged in user
     */
    public function remove_loged_user()
    {
        global $engine;
        $query = "DELETE FROM " . $this->table_loged_in . " WHERE id_user=" .
            (int)$this->get_id() . " LIMIT 1;";
        $engine->dbase->insertQuery($query);
        unset($_SESSION['users']);
    }

    /**
     *
     * @global $engine $engine
     * @param int $id
     * @param array $whatToGet
     * @return boolean
     */
    public function getExtendedUserInfo($id, $whatToGet = array('*'))
    {
        global $engine;
        $query = "SELECT " . implode(",", $whatToGet) . " FROM " . $this->extended_table . " WHERE user_id=" . (int)$id;
        // Do Query
        $engine->dbase->query($query);
        // Get Results
        if (count($engine->dbase->rows) > 0) {
            $row = $engine->dbase->rows[0];
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get extended info for users
     */
    public function getExtendedUserInfos($ids, $whatToGet = array('*'))
    {
        global $engine;
        // Query
        $query = "SELECT " . implode(",", $whatToGet) . " FROM " . $this->extended_table . " WHERE user_id IN (" . implode(",", $ids) . ")";
        // Do Query
        $engine->dbase->query($query);
        // Get Results
        if (count($engine->dbase->rows) > 0) {
            return $engine->dbase->rows;
        } else {
            return false;
        }
    }

    public function delete()
    {
    }

    /**
     * Extended
     * @param array $data
     */
    public function saveExtendedProfileData($id)
    {
        global $engine;
        // Get values
        $vals = $engine->security->get_vals(array("firstName",
            "lastName",
            "language",
            "gender"
        ));
        // Check if avatar is defined
        if (isset($_FILES['avatar'])) {
        }
        // Prepare query
        $query = "UPDATE " . $this->extended_table . " SET first_name='" .
            $vals['firstName'] . "', last_name='" .
            $vals['lastName'] . "', language='" . $vals['language']
            . "', gender='" . $vals['gender'] . "' WHERE user_id=" . (int)$id;
        // Insert data
        $res = $engine->dbase->insertQuery($query);
        // Check result
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}

?>