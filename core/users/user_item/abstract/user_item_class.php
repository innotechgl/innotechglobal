<?php

// Require user interface
require $engine->path . "core/users/user_item/interface/user_item_interface.php";

/**
 *
 * @author dajana
 *
 */
abstract class user_item
{

    const USER_TYPE_FB_USER = "FB_USER";
    const USER_TYPE_GOOGLE_USER = "GOOGLE_USER";
    const USER_TYPE_STANDARD_USER = "STANDARD_USER";
    public $extended_table;
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
    protected $is_active = false;
    protected $user_level = -1;
    protected $blocked = false;
    protected $mail;
    protected $extended_data;
    protected $user_type;
    protected $table = 'user';
    protected $table_loged_in = 'user_loged_in';
    /**
     * @var sCMS_engine
     */
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
    }

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
        $this->blocked = (int)$blocked;
    }

    public function getExtendedData($extended = array())
    {
        return $this->extended_data;
    }

    public function setExtendedData($extended = array())
    {
        $this->extended_data = $extended;
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

    /**
     *
     * @param int $active
     */
    public function set_active($active)
    {
        $this->is_active = (int)$active;
    }

    /**
     *
     * @return active
     */
    public function get_active()
    {
        return $this->is_active;
    }

    public function generate_key()
    {
        $key = md5($this->username . $this->password . rand(0, 5800));
        return $key;
    }

    public function get_key()
    {
        return $this->key;
    }

    public function set_key($key)
    {
        $this->key = $key;
    }

    public function get_salt()
    {
        return $this->salt;
    }

    // categorie id

    public function set_salt()
    {
        $this->salt = time();
    }

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

    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC', $table = null)
    {
        global $engine;
        if ($table !== null) {
            $table = $table;
        } else {
            $table = $this->table;
        }
        $query = "SELECT " . $what_to_get . "
                  FROM " . $table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     *
     * @param int $group_id
     * @param string $group_name
     * @param description $group_description
     */
    public function add_group($group_id, $group_name, $group_description, $group_type)
    {
        if (!$this->_check_if_user_is_member_of_group($group_id)) {
            $i = count($this->groups);
            $this->groups[$i]['group_id'] = $group_id;
            $this->groups[$i]['group_name'] = $group_name;
            $this->groups[$i]['group_description'] = $group_description;
            $this->groups[$i]['group_type'] = $group_type;
        }
    }

    // Title

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
        $query = "UPDATE " . $engine->table->table_loged_in . " SET last_active=NOW() WHERE id_user=" . $this->get_id() . " LIMIT 1;";
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
        $this->id = (int)$id;
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
        $q = $engine->dbase->query($query);
        $res = $engine->dbase->rows;
        if ($res[0]['existance'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function write_loged_in_user()
    {
        global $engine;
        $this->remove_loged_user();
        $query = "INSERT INTO " . $this->table_loged_in . " (username,id_user,time,last_active,ip, session_id) VALUES ('" . $this->username . "','" . $this->id . "',NOW(),NOW(),'" . $_SERVER["REMOTE_ADDR"] . "','" . session_id() . "');";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function check_if_is_admin()
    {
        foreach ($this->groups as $key_g => $val_g) {
            if ($val_g['group_type'] == 2) {
                return true;
            }
        }
        return false;
    }

    public function write_to_session($id, $username)
    {
        global $engine;
        // Clear previous user sessions
        $this->clearSession();
        // Take groups attached to user
        $query = "SELECT
		user_groups.type AS type,
		user_groups.id AS group_id
		FROM
		user_groups
		Left Join rel_user_group ON rel_user_group.id_rel_group = user_groups.id
		WHERE
		rel_user_group.id_rel_user =  '" . (int)$id . "'";
        $engine->dbase->query($query);
        // Write session data
        $_SESSION['users']['id'] = $id;
        $_SESSION['users']['username'] = $username;
        //$_SESSION['users']['cookie_md5'] = $session_cookie_md5;
        foreach ($engine->dbase->rows as $key => $val) {
            $_SESSION['users']['groups'][$key]['group_id'] = $val['group_id'];
            $_SESSION['users']['groups'][$key]['type'] = $val['type'];
        }
    }

    protected function clearSession()
    {
        unset($_SESSION['users']);
    }

    public function __destruct()
    {
    }
}