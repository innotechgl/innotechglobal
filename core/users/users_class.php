<?php

class users_class extends page_class
{

    protected $tableLogedIn = "user_loged_in";

    public function __construct(){
        parent::__construct();

        $this->table = "users";
    }

    /**
     * @param user $user
     * @return array|bool
     */
    public function add(user $user){
        $userID = $this->checkEmailExistence($user->getEmail());
        if (!$userID){
            $result = $this->_add($user);
            return $result;
        }
        else {
            return false;
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    protected function checkEmailExistence($email){
        $query = "SELECT id FROM ".$this->table." WHERE email='".$email."' LIMIT 0,1;";
        $this->engine->dbase->query($query);
        if (count($this->engine->dbase->rows)>0){
            return $this->engine->dbase->rows[0]['id'];
        }
        else {
            return false;
        }
    }

    /**
     * @param user $user
     * @return array
     */
    protected function _add(user $user){
        $password = $this->generatePassword();
        $hashed_password = $this->hashPassword($password,$user->getSalt());

        $query = "INSERT INTO ".$this->table." (mail, username, active, password, created, creator, salt)
        VALUES ('".$user->getEmail()."', '".$user->getEmail()."', 0,'".$hashed_password
            ."', NOW(),'".$user->getCreator()."', '".$user->getSalt()."')";

        $id = $this->engine->dbase->insertQuery($query);
        $result = array("password"=>$password,"id"=>$id);

        return $result;
    }

    /**
     * @param int $size
     * @param int $power
     * @return string
     */
    protected function generatePassword($size = 9, $power = 0)
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

    /**
     * @param $password
     * @param $salt
     * @return string
     */
    public function hashPassword($password,$salt){
        return sha1($password . $salt);
    }

    /**
     * @param user $user
     * @param $password
     * @return bool
     */
    public function update(user $user, $password){
        $hashed_password = $this->hashPassword($password,$user->getSalt());

        $query = "UPDATE ".$this->table." SET password='".$hashed_password."', username='".$user->getUsername()
            ."', extended_data='".json_encode($user->getData())."' WHERE id='".$user->getID()."' LIMIT 1;";

        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id){
        $query = "DELETE FROM ".$this->table." WHERE id=".(int)$id." LIMIT 1;";

        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }


    public function login(){

    }

    public function logout(){

    }

    /**
     * @param string $username
     * @return bool
     */
    protected function checkUsernameExistence($username){
        $query = "SELECT id FROM ".$this->table." WHERE username='".$username."' LIMIT 0,1;";
        $this->engine->dbase->query($query);
        if (count($this->engine->dbase->rows)>0){
            return $this->engine->dbase->rows[0]['id'];
        }
        else {
            return false;
        }
    }

    /**
     * @param string $token
     * @return bool
     */
    protected function checkLogedInByToken($token){

        $query = "SELECT id_user FROM ".$this->tableLogedIn." WHERE session_id='".$token."'";
        $this->engine->dbase->query($query);

        if (count($this->engine->dbase->rows)>0){
            $this->engine->dbase->rows[0]["id_user"];
        }
        else {
            return false;
        }
    }

    /**
     * @param int $userID
     * @param string $token
     * @return bool
     */
    protected function writeLogedIn($userID, $token){
        $query = "INSERT INTO ".$this->table." (`id_user`,'session_id',`time`,`last_active`) VALUES ('".(int)$userID."','".$token."',NOW(), NOW());";
        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    /**
     * @param string $token
     * @return bool
     */
    protected function updateLogedInStatus($token){
        $query = "UPDATE ".$this->table." SET `last_active`=NOW() WHERE session_id='".$token."'";
        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    /**
     * @param int $userID
     * @return bool
     */
    protected function removeLogedIn($userID){
        $query = "DELETE FROM ".$this->tableLogedIn." WHERE id_user=".(int)$userID;
        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    protected function generateToken(){
        $token = "";
        return $token;
    }

    protected function checkToken($token){

    }



}