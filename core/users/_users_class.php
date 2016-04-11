<?php

global $engine;
// Load default user class
require_once $engine->path . 'core/users/user_item/user_item_class.php';
require_once $engine->path . 'core/users/user_item/fb_user_class.php';

/**
 * @name users
 * @copyright ŠUTIJA-WEB, Agencija za izradu internet prezentacija
 *
 */
class users
{

    public $lang;
    public $page = 'users';
    //public $loged_in_user = null;
    public $table = 'user';
    public $table_loged_in = 'user_loged_in';
    /**
     * @var user_item_class
     */
    public $active_user;
    protected $allowed_login_attempts = 10;
    protected $users = array();
    protected $default_user_group;
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
        include_once $engine->path . 'language/core/users/users_lat.php';
        $this->lang = new users_language();
        $this->loadActiveUser();
    }

    /**
     *
     * @global object $engine
     */
    public function loadActiveUser()
    {
        global $engine;
        $this->active_user = new user_item_class;
        if ($this->active_user->is_loged_in()) {
            // Create active user
            $this->active_user = new user_item_class();
            // Get active user info
            $id = $this->get_id();
            // Load active user
            $this->active_user->load_user($id);
            // Set extended data for user
            $this->active_user->setExtendedData($this->active_user->getExtendedUserInfo($id));
        }
    }

    public function get_id()
    {
        if (isset($_SESSION['users'])) {
            return $_SESSION['users']['id'];
        } else {
            return 0;
        }
    }

    /**
     * @param string $username
     * @return bool
     */
    public function user_exists($username = '')
    {
        global $engine;
        $this->username = $engine->security->get_val($username);
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE username LIKE '" . $this->username . "';";
        $engine->dbase->query($query);
        if ($engine->dbase->rows[0]['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param String $mail
     * @param String $password
     * @return bool
     */
    public function login($mail, $password)
    {
        if ($this->active_user->login($mail, $password)) {
            $token = $this->engine->users->createToken();
            $this->write_loged_in_user($token);
            return $token;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function createToken()
    {
        $token = $this->engine->users->active_user->generate_key();
        $_SESSION[$token]['user_id'] = $this->engine->users->active_user->get_id();
        return $token;
    }

    /**
     * LOCKINGS
     *
     */

    protected function write_loged_in_user($token)
    {
        global $engine;
        $this->remove_loged_user();
        $query = "INSERT INTO " . $this->table_loged_in .
            " (username,id_user,time,last_active,ip, session_id) VALUES ('" . $this->active_user->get_username() .
            "','" . $this->active_user->get_id() . "',NOW(),NOW(),'" . $_SERVER["REMOTE_ADDR"] . "','" . $token . "');";


        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function remove_loged_user()
    {
        global $engine;

        $query = "DELETE FROM " . $this->table_loged_in . " WHERE id_user='" . $this->get_id() . "' LIMIT 1;";
       /* if ($engine->dbase->insertQuery($query)) {
            $this->remove_session();
            return true;
        } else {
            return false;
        }*/
    }

    public function destroyToken($token)
    {
        unset($_SESSION[$token]);
    }

    /**
     * @param String $token
     * @return int|bool
     */
    public function getUserIDByToken($token)
    {
        $query = "SELECT id_user FROM " . $this->table_loged_in . " WHERE session_id='" . $token . "';";

        $this->engine->dbase->query($query);
        if (count($this->engine->dbase->rows) > 0) {
            return $this->engine->dbase->rows[0]["id_user"];
        } else {
            return false;
        }
    }

    public function get_loged_in_users()
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table_loged_in . ";";
        $engine->dbase->query($query);
        return $engine->dbase->rows;
    }

    public function remove_session()
    {
        unset($_SESSION['users']);
    }

    public function logout()
    {
        $this->active_user->logout();
    }

    public function delete($id = 0)
    {
        global $engine;
        if ($id <= 0) {
            return false;
            exit();
        }
        $this->id = $id;
        $query = "DELETE FROM " . $this->table . " WHERE id=" . $this->id . " LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param array $ids
     * @return array
     */
    public function get_users_by_id($ids = array())
    {
        $users = $this->get_users('*', 'WHERE id IN (' . implode(",", $ids) . ')');
        $users_array = array();
        foreach ($users as $key => $val) {
            $users_array[$val['id']] = $val;
        }
        foreach ($ids as $key => $val) {
            if (!key_exists($val, $users_array)) {
                $users_array[$val] = 'unknown user!';
            }
        }
        // return users
        return $users_array;
    }

    /**
     * @name  Get array of users
     * @return array
     */
    public function get_users($what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . " FROM " . $this->table . " " . $filter_params . " ORDER BY " . $order_by . " " . $order_direction;
        echo $query;
        $engine->dbase->query($query, true, true);
        return $engine->dbase->rows;
    }

    /**
     * Remove old sessions
     *
     */
    public function remove_old_sessions()
    {
        global $engine;
        $query = "DELETE FROM " . $this->table_loged_in . " WHERE last_active<'" . date("Y-m-d h:i:s", strtotime("-3 hour")) . "'";
        $engine->dbase->insertQuery($query);
    }

    /**
     * @param mixed $id
     * @name Activate user account
     *
     */
    public function activate($id = NULL)
    {
        global $engine;
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=1 WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param mixed $id
     * @name Deactivate user account
     *
     */
    public function deactivate($id = NULL)
    {
        global $engine;
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=0 WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add login attempt
     */
    private function add_login_attempt()
    {
        global $engine;
        $query = "UPDATE " . $this->table . " SET login_attempts=login_attempts+1 WHERE username LIKE '" . $this->get_username() . "'";
        $result = $engine->dbase->insertQuery($query);
        return $result;
    }

    /**
     * Clear login attempts
     */
    private function clear_login_attempts()
    {
        global $engine;
        $query = "UPDATE " . $this->table . " SET login_attempts=0 WHERE username LIKE '" . $this->username . "' LIMIT 1;";
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
            $query = "UPDATE " . $this->table . " SET active=0 
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
        $this->set_username($username);
        $user = $this->get_users('login_attempts', 'WHERE username LIKE "' . $this->username . '"');
        echo $user[0]['login_attempts'];
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

    /**
     *
     * @return int
     */
    public function get_allowed_login_attempts()
    {
        return $this->allowed_login_attempts;
    }

    /**
     *
     * @param int $number
     */
    public function set_allowed_login_attempts($number)
    {
        $this->allowed_login_attempts = (int)$number;
    }
}

?>