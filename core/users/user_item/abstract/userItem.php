<?php

class user
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $salt;

    /**
     * @var DateTime
     */
    protected $created;
    /**
     * @var int
     */
    protected $creator;
    /**
     * @var DateTime
     */
    protected $modified;
    /**
     * @var int
     */
    protected $modifier;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param int  $id
     */
    public function setID($id){
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getID(){
        return $this->id;
    }

    /**
     * @param string $email
     */
    public function setEmail($email){
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @param string $username
     */
    public function setUsername($username){
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt){
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getSalt(){
        return $this->salt;
    }

    /**
     * @param int $creator
     */
    public function setCreator($creator)
    {
        $this->creator = (int)$creator;
    }

    /**
     * @return int
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $modifier
     */
    public function setModifier($modifier)
    {
        $this->modifier = (int)$modifier;
    }

    /**
     * @return int
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     * @param DateTime $modified
     */
    public function setModified(DateTime $modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param array $data
     */
    public function setData(array $data){
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addDataByKey($key,$value){
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getDataByKey($key){
        return $this->data[$key];
    }

    /**
     * Adds data as element of array
     *
     * @param string $key
     * @param mixed $value
     */
    public function addDataToValue($key,$value){
        $this->data[$key][] = $value;
    }

    /**
     * @return array
     */
    public function __toArray(){
        $array = array();

        $array["id"] = $this->id;
        $array["email"] = $this->email;
        $array["username"] = $this->username;
        $array["salt"] = $this->salt;

        $array["data"] = $this->data;

        $array["created"] = $this->created->format("Y-m-d H:i:s");
        $array["creator"] = $this->creator;
        $array["modified"] = $this->modified->format("Y-m-d H:i:s");
        $array["modifier"] = $this->modifier;

        return $array;
    }

}