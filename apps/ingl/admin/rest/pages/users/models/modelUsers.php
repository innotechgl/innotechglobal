<?php

class modelUsers extends users
{

    protected $minPasswordLength;
    protected $maxPasswordLength;

    public function __construct()
    {
        parent::__construct();

        $this->minPasswordLength = 6;
        $this->maxPasswordLength = 12;
    }

    public function login($mail, $password)
    {
        $token = null;
        $result = parent::login($mail, $password);
        if ($result) {
            $token = $result;
        }
        return $token;
    }

    public function logoutUser($token)
    {
        parent::logout();
        $this->destroyToken($token);
    }

    /**
     * @return array
     */
    public function getAllUsers(){

        $users = array();
        $allUsers = $this->get_users();

        foreach($allUsers as $key=>$val){

            $user = new userInfo();
            $user->setID($val["id"]);
            $user->setMail($val["mail"]);
            $user->setAllExtendedData( json_decode( $val["extended_data"], true ) );
            $user->setActive($val["active"]);
            $user->setLastLogin(new DateTime($val["last_login"]));
            $user->setCreator($val["creator"]);
            $user->setCreated(new DateTime($val["created"]));
            $user->setModified(new DateTime($val["modified"]));
            $user->setModifier($val["modifier"]);

            $users[] = $user->__toArray();
        }

        return $users;
    }

    public function register($data){
        $mail = filter_var($data["mail"],FILTER_SANITIZE_EMAIL);
        $password = filter_var($data["password"],FILTER_SANITIZE_STRING);
        $passwordAgain = filter_var($data["password_again"], FILTER_SANITIZE_STRING);

        // Check existance of user name
        if ($this->checkMailExistance($mail)) {
            $this->engine->log->add_error('user', 'mail exists.', 'mail_existance');
        }

        // Check passwords
        if ($password !== $passwordAgain) {
            $this->engine->log->add_error('user', 'passwords doesn\'t match', 'matching_passwords');
        }

        // Check if password to small
        if (strlen($password) < $this->minPasswordLength) {
            $this->engine->log->add_error('user', 'Password to small', 'password_length');
        }

        // Check if password to long
        if (strlen($password) > $this->maxPasswordLength) {
            $this->engine->log->add_error('user', 'Password too long', 'password_length');
        }

        // Check if we have errors
        if (isset($this->engine->log->error_log['user'])) {
            return false;
        }

        $user_item = new user_item_class();
        $user_item->set_mail($mail);
        $user_item->set_salt();
        $user_item->set_password($password);
        $user_item->set_key($user_item->generate_key());

        // Set query
        $query = "INSERT INTO " . $this->table .
            "( mail, password, created, creator, active, salt, `key` )
		VALUES ( '" . $mail . "', '" . $user_item->get_password() .
            "', NOW(),
		0, 0, '" . $user_item->get_salt() . "', '" . $user_item->get_key() . "')";

        // Do Query
        $id = $this->engine->dbase->insertQuery($query);

        if ($id > 0) {

            //$this->engine->user_groups->add_user_to_group($id, $this->engine->settings->user_groups->default);

            return true;

        } else {
            return false;
        }
    }

    private function checkMailExistance($mail)
    {
        // Query
        $query = "SELECT COUNT(*) as existance FROM " . $this->table . " WHERE mail LIKE '" . $mail . "'";
        $this->engine->dbase->query($query);

        if ($this->engine->dbase->rows[0]['existance'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function loadUser($id){
        $users = $this->get_users("*","WHERE id=".$id);
        return $users[0];
    }
}