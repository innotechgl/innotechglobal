<?php
global $engine;
require_once($engine->path . 'core/users/user_item/abstract/user_item_class.php');

final class fb_user_class extends user_item
{

    public $table = 'fb_user';
    public $page = 'fb_user';
    protected $user_profile_link = 'http://graph.facebook.com/me?access_token=';
    protected $access_token = '';
    private $name = '';
    private $first_name = '';
    private $last_name = '';
    private $link = '';
    private $birthday = '';
    private $home_town_id = 0;
    private $home_town_name = '';
    private $location_id = 0;
    private $location_name = '';
    private $bio = '';
    private $work = '';
    private $education = '';
    private $gender = '';
    private $inerested_in = '';
    private $relationship_status = '';
    private $religion = '';

    // Set user_profile_link
    private $political = '';
    private $website = '';

    // Set table and page
    private $verified = '';
    private $update_time = '';

    // Rel users
    private $rel_table = 'rel_fb_user';
    private $rel_id = 0;
    private $rel_fb_id = 0;
    private $rel_user_id = 0;

    private $apiId = 0;
    private $secret = 0;

    public function __construct()
    {
        global $engine;
        // Set user type
        $this->set_user_type(self::USER_TYPE_FB_USER);
        $engine->load_util('facebook');
    }

    public function get_access_token()
    {
        return $this->access_token;
    }

    /**
     * Set access token
     */
    public function set_access_token($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * Register user
     * @return bool
     */
    public function register($mail, $password)
    {
        global $engine;
        $arr_vars = array("mail" => $mail, "password" => $password);
        // Check existance of user name
        if ($this->check_mail_existance($arr_vars['mail'])) {
            $engine->log->add_error('user', 'mail exists.', 'mail_existance');
        }
        // Check if we have errors
        if (isset($engine->log->error_log['user'])) {
            return false;
            exit();
        }
        $user_item = new user_item_class();
        $user_item->set_mail($arr_vars['mail']);
        $user_item->set_salt();
        $user_item->set_password($arr_vars['password']);
        $user_item->set_key($user_item->generate_key());
        // Set query
        $query = "INSERT INTO " . $user_item->table . "( mail, password, created, creator, active, salt, `key` )
		VALUES ( '" . $user_item->mail . "', '" . $user_item->get_password() . "', NOW(),
		0, 0, '" . $user_item->get_salt() . "', '" . $user_item->get_key() . "')";
        // Do Query
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            $engine->user_groups->add_user_to_group($this->id, $engine->settings->user_groups->default);
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * Connect user with FB profile
     *
     */
    public function connect_user($user_id = 0, $fb_id = 0)
    {
        global $engine;
        if (!$this->user_connection_exists($user_id, $fb_id)) {
            $query = "INSERT INTO " . $this->rel_table . " (user_id, fb_id) VALUES (" . $user_id . "," . $fb_id . ")";
            $res = $engine->dbase->insertQuery($query);
            if ($res) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * @param id $fb_id
     *
     */
    private function user_connection_exists($user_id = 0, $fb_id = 0)
    {
        // Check user_existance
        $rel_user = $this->get_array(true, 'COUNT(*) as num_of_users', 'WHERE fb_id=' . (int)$fb_id, 'id', 'asc', $this->rel_table);
        // Check user existance
        if ($rel_user[0]['num_of_users'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Login
     *
     */
    public function login()
    {
        global $engine;
        // Session
        $uid = $engine->util_facebook->fb->getUser();
        $me = null;
        // Session based API call.
        if ($uid) {
            try {
                $uid = $engine->util_facebook->fb->getUser();
                $me = $engine->util_facebook->fb->api('/me');
                // Set e-mail
                $this->set_mail($me['email']);
                // Set extended data
                $this->setExtendedData($me);
            } catch (FacebookApiException $e) {
                //var_dump($e);
            }
        }
        if ($me) {
            // Get user id
            $user_id = $this->get_rel_user_id();
            if ($user_id > 0) {
                // Write user as loged in
                $this->write_loged_in_user();
                if ($this->write_to_session($user_id, $engine->users->active_user->get_username())) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // Create user
                $this->create_user();
            }
        }
    }

    private function get_rel_user_id()
    {
        global $engine;
        $fb_id = $engine->util_facebook->fb->getUser();
        $rel_user = $this->get_array(true, '*', 'WHERE fb_id=' . $fb_id, 'id', 'asc', $this->rel_table);
        if (count($rel_user) > 0) {
            return $rel_user[0]['user_id'];
        } else {
            return false;
        }
    }

    public function write_loged_in_user()
    {
        global $engine;
        // Load user as active
        $engine->users->active_user->load_user($this->get_rel_user_id());
        //var_dump($engine->users->active_user);
        // Remove loged user
        $this->remove_loged_user();
        $query = "INSERT INTO " . $this->table_loged_in . " (username,id_user,time,last_active,ip, session_id) VALUES ('" . $engine->users->active_user->get_username() . "'," . $engine->users->active_user->get_id() . ",NOW(),NOW(),'" . $_SERVER["REMOTE_ADDR"] . "','" . session_id() . "');";
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
        $query = "DELETE FROM " . $this->table_loged_in . " WHERE id_user=" . $engine->users->active_user->get_id() . " LIMIT 1;";
        $engine->dbase->insertQuery($query);
        unset($_SESSION['users']);
    }

    /**
     * @param int $user_id
     *
     */
    public function create_user($user_id = 0)
    {
        global $engine;
        $this->prepare_data();
        if (!$this->user_exists()) {
            $query = "INSERT INTO " . $this->table . " (id, name, first_name, last_name, link, username,
			birthday, home_town_id, home_town_name, location_id, location_name, bio, work, education, gender,
			interested_in,relationship_status, religion, political, website, verified, update_time, access_token)
	
			VALUES (" . $this->id . ", '" . $this->name . "', '" . $this->first_name . "', '" . $this->last_name . "', '" . $this->link . "',
			'" . $this->username . "', '" . $this->birthday . "', '" . $this->home_town_id . "', '" . $this->home_town_name . "',
			'" . $this->location_id . "', '" . $this->location_name . "', '" . $this->bio . "', '" . $this->work . "', '" . $this->education . "',
			'" . $this->gender . "', '" . $this->inerested_in . "', '" . $this->relationship_status . "', '" . $this->religion . "',
			'" . $this->political . "', '" . $this->website . "', '" . $this->verified . "', '" . $this->update_time . "', '" . $this->access_token . "')";
        } else {
            $query = "UPDATE " . $this->table . "
			SET
			name='" . $this->name . "',
			first_name='" . $this->first_name . "',
			last_name='" . $this->last_name . "',
			link='" . $this->link . "',
			username='" . $this->username . "',
			birthday='" . $this->birthday . "',
			home_town_id='" . $this->home_town_id . "',
			home_town_name='" . $this->home_town_name . "',
			location_id='" . $this->location_id . "',
			location_name='" . $this->location_name . "',
			bio='" . $this->bio . "',
			work='" . $this->work . "',
			education='" . $this->education . "',
			gender='" . $this->gender . "',
			interested_in='" . $this->inerested_in . "',
			relationship_status='" . $this->relationship_status . "',
			religion='" . $this->religion . "',
			political='" . $this->political . "',
			website='" . $this->website . "',
			verified='" . $this->verified . "',
			update_time='" . $this->update_time . "',
            access_token='" . $this->access_token . "'
			WHERE id='" . $this->id . "' LIMIT 1;";
        }
        // insert or update
        $result = $engine->dbase->insertQuery($query);
        if ($result) {
            return true;
        } else {
            return true;
        }
    }

    /**
     * Prepare data
     */
    public function prepare_data()
    {
        global $engine;
        // get values
        $engine->util_facebook->fb->getUser();
        $me = $engine->util_facebook->fb->api('/me');
        // set data
        $this->id = $me['id'];
        $this->name = @$engine->security->get_val($me['name']);
        $this->first_name = @$engine->security->get_val($me['first_name']);
        $this->last_name = @$engine->security->get_val($me['last_name']);
        $this->link = @$engine->security->get_val($me['link']);
        $this->username = @$engine->security->get_val($me['user_name']);
        $this->birthday = @$engine->security->get_val($me['birthday']);
        $this->home_town_id = @$engine->security->get_val($me['home_town_id']);
        $this->home_town_name = @$engine->security->get_val($me['home_town_name']);
        $this->location_id = @$engine->security->get_val($me['location_id']);
        $this->location_name = @$engine->security->get_val($me['location_name']);
        $this->bio = @$engine->security->get_val($me['bio']);
        // set work
        $this->work = @$engine->security->get_val(array_map(create_function('$key, $value', 'return $key.":".$value." # ";'), array_keys($me['work']), array_values($me['work'])));
        // set education
        $this->education = @$engine->security->get_val(array_map(create_function('$key, $value', 'return $key.":".$value." # ";'), array_keys($me['education']), array_values($me['education'])));
        $this->gender = @$engine->security->get_val($me['gender']);
        $this->inerested_in = @$engine->security->get_val($me['interested_in']);
        $this->relationship_status = @$engine->security->get_val($me['relationship_status']);
        $this->religion = @$engine->security->get_val($me['religion']);
        $this->political = @$engine->security->get_val($me['political']);
        $this->website = @$engine->security->get_val($me['website']);
        $this->verified = @$engine->security->get_val($me['verified']);
        $this->update_time = @$engine->security->get_val($me['updated_time']);
        $this->access_token = $engine->util_facebook->fb->getAccessToken();
    }

    // Logout

    /**
     * @param id $fb_id
     *
     */
    private function user_exists()
    {
        global $engine;
        $id = $engine->util_facebook->fb->getUser();
        // Check user_existance
        $rel_user = $this->get_array(true, 'COUNT(*) as num_of_users', 'WHERE id=' . $id, 'id', 'asc');
        // Check user existance
        if ($rel_user[0]['num_of_users'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        // Logout from website
        $engine->users->logout();
        // Logout from FB
        header("Location: " . $engine->util_facebook->getLogoutUrl());
    }

    /**
     *
     */
    public function isFbUser($user_id)
    {
        $rel_user = $this->get_array(true, 'COUNT(*) AS numOfItem', 'WHERE user_id=' . (int)$user_id, 'id', 'asc', $this->rel_table);
        if ($rel_user[0]['numOfItem'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function loadDataFromDB()
    {
        $uData = $this->get_array(false, '*', 'WHERE id=' . $this->id);
        if (isset($uData[0])) {
            return $uData[0];
        } else {
            return array();
        }
    }

    private function set_user_profile_link($link = null)
    {
        if ($link !== null) {
            $this->user_profile_link = $link;
        }
        $this->user_profile_link .= $this->access_token;
    }
}

//$fb = new fb_user_class;
//var_dump($fb);
?>