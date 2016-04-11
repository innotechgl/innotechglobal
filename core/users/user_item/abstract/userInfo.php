<?php

class userInfo extends mainObject{

    /**
     * @var Str
     */
    protected $mail;
    /**
     * @var Array
     */
    protected $extendedData;

    /**
     * @var Array
     */
    protected $data;

    /**
     * @var Int
     */
    protected $active;

    /**
     * @var DateTime
     */
    protected $lastLogin;


    public function __construct(){
        parent::__construct();
    }

    /**
     * @return Str
     */
    public function getMail(){
        return $this->mail;
    }

    /**
     * @param Str $mail
     */
    public function setMail($mail){
        $this->mail = $mail;
    }

    /**
     * @param Array $data
     */
    public function setAllExtendedData(array $data){
        $this->extendedData = $data;
    }

    /**
     * @param Str $key
     * @return mixed
     */
    public function getExtendedData($key){
        return $this->extendedData[$key];
    }

    /**
     * @param Str $key
     * @param Mixed $val
     */
    public function setExtendedData($key,$val){
        $this->extendedData[$key] = $val;
    }

    /**
     * @return Array
     */
    public function getAllExtendedData(){
        return $this->extendedData;
    }

    /**
     * @param Str $key
     * @param Mixed $val
     */
    public function addData($key,$val){
        $this->data[$key] = $val;
    }

    /**
     * @param Str $key
     * @return mixed
     */
    public function getData($key){
        return $this->data[$key];
    }

    /**
     * @return Array
     */
    public function getAllData(){
        return $this->data;
    }

    /**
     * @return Int
     */
    public function getActive(){
        return $this->active;
    }

    /**
     * @param Int $active
     */
    public function setActive($active){
        $this->active = (int)$active;
    }

    public function getLastLogin(){
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $lastLogin){
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string
     */
    public function __toJSON(){
        $jsonData = json_encode($this->__toArray());
        return $jsonData;
    }

    /**
     * @return array
     */
    public function __toArray(){
        $data = array();
        $data["id"] = $this->id;
        $data["mail"] = $this->mail;
        $data["extended_data"] = $this->extendedData;
        $data["data"] = $this->data;
        $data["active"] = $this->active;
        $data["last_login"] = $this->lastLogin->format("Y-m-d h:i:s");
        $data["created"] = $this->created->format("Y-m-d h:i:s");
        $data["creator"] = $this->creator;
        $data["modified"] = $this->modified->format("Y-m-d h:i:s");
        $data["modifier"] = $this->modifier;

        return $data;
    }

}