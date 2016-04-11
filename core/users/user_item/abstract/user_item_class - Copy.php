<?php

/**
 *
 * @author dajana nestorovic
 *
 */
abstract class user_item
{

    const USER_TYPE_FB_USER = "FB_USER";
    const USER_TYPE_GOOGLE_USER = "GOOGLE_USER";
    const USER_TYPE_STANDARD_USER = "STANDARD_USER";
    protected $id;
    protected $username = 'Guest';
    protected $password;
    protected $created;
    protected $creator;
    protected $modified;
    protected $modifier;
    protected $key;
    protected $salt;
    protected $groups;
    protected $is_admin = false;
    protected $user_level = -1;
    protected $blocked = false;
    protected $mail;
    protected $user_type;
    protected $table = 'user';
    protected $extended_table;

    // ID

    public function get_user_type()
    {
        return $this->user_type;
    }

    public function set_user_type($user_type)
    {
        $this->user_type = $user_type;
    }

    /**
     * Get blocked status
     * @return int
     */
    public function get_blocked()
    {
        return $this->blocked;
    }

    /**
     * Set blocked status
     * @param inst $blocked
     */
    public function set_blocked($blocked)
    {
        $this->blocked = ( int )$blocked;
    }

    public function get_mail()
    {
        return $this->mail;
    }

    /**
     *
     * @param string $mail
     */
    public function set_mail($mail)
    {
        $this->mail = $mail;
    }

    // categorie id

    public function get_key()
    {
        return $this->key;
    }

    public function set_key()
    {
        $this->key = md5($this->username . $this->password . rand(0, 5800));
    }

    public function get_salt()
    {
        return $this->salt;
    }

    public function set_salt()
    {
        $this->salt = time();
    }

    // categorie id

    public function get_is_admin()
    {
        return $this->is_admin;
    }

    // Get key

    public function set_is_admin($is_admin = false)
    {
        $this->is_admin = (int)$is_admin;
    }

    // categorie id

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($password)
    {
        // Hash pass
        $this->password = sha1($password . $this->salt);
    }

    // categorie id

    /**
     *
     * @param int $group_id
     * @param string $group_name
     * @param description $group_description
     */
    public function add_group($group_id, $group_name, $group_description)
    {
        if (!$this->_check_if_user_is_member_of_group($group_id)) {
            $this->groups[]['group_id'] = $group_id;
            $this->groups[]['group_name'] = $group_name;
            $this->groups[]['group_description'] = $group_description;
        }
    }

    /**
     * Internal check user group existance
     */
    protected function _check_if_user_is_member_of_group($group_id)
    {
        for ($i = 0; $i < count($this->groups); $i++) {
            if ($this->groups[$i]['group_id'] == $group_id) {
                return true;
            }
        }
        return false;
    }

    // Title

    /**
     * Check user group membership
     *
     * @param int $group_id
     * @return bool
     */
    public function check_user_group_membership($group_id)
    {
        for ($i = 0; $i < count($this->groups); $i++) {
            if ($this->groups[$i]['group_id'] == $group_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Update my activity
     */
    public function update_activity()
    {
        global $engine;
        $query = "UPDATE " . $engine->table->table_loged_in .
            " SET last_active=NOW() WHERE id_user=" . $this->get_id() .
            " LIMIT 1;";
        $row = $engine->dbase->insertQuery($query);
        if (count($row) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = ( int )$id;
    }

    /**
     * Create session for user
     */
    public function create_session($session_cookie_md5 = '')
    {
        $this->_load_user($this->id);
        // Write session data
        // Set id
        $_SESSION['users']['id'] = $this->get_id();
        // Set user name
        $_SESSION['users']['username'] = $this->get_username();
        // Set cookie id
        $_SESSION['users']['cookie_md5'] = $session_cookie_md5;
        // Set user type (fb, standard or google)
        $_SESSION['users']['user_type'] = $this->user_type;
        // Set user groups
        $_SESSION['users']['groups'] = $this->get_groups();
    }

    protected function _load_user()
    {
    }

    public function get_username()
    {
        return $this->username;
    }

    public function set_username($username)
    {
        $this->username = $username;
    }

    /**
     * Get groups
     *
     */
    public function get_groups()
    {
        return $this->groups;
    }

    /**
     *
     * @param string $mail
     */
    public function check_mail_existance($mail)
    {
        global $engine;
        $this->set_mail($mail);
        // Query
        $query = "SELECT COUNT(*) as existance FROM " . $this->table . " WHERE mail LIKE '" . $this->mail . "'";
        $res = $engine->dbase->query($query);
        if ($res[0]['existance'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function activate_with_key($key)
    {
        global $engine;
        $this->set_key($key);
        if ($this->key !== "") {
            $query = "UPDATE " . $this->table . " SET active=1, key=''
                    WHERE key LIKE '" . $this->key . "' LIMIT 1;";
            if ($engine->dbase->insertQuery($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function __destruct()
    {
    }
}