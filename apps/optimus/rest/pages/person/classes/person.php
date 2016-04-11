<?php

/*
 * CREATE TABLE `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `creator` int(3) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifier` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `email` (`email`(255)),
  KEY `date_of_birth` (`date_of_birth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * */

class person extends mainObject
{
    const GENDER_UNKNOWN = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    /**
     * @var String
     */
    protected $firstName;
    /**
     * @var String
     */
    protected $lastName;
    /**
     * @var String
     */
    protected $email;
    /**
     * @var DateTime
     */
    protected $dateOfBirth;
    /**
     * @var int
     */
    protected $birthDay;
    /**
     * @var int
     */
    protected $birthMonth;
    /**
     * @var int
     */
    protected $birthYear;
    /**
     * @var int
     */
    protected $stateID;
    /**
     * @var int
     */
    protected $gender;
    /**
     * @var Array
     */
    protected $data;

    public function __construct(){
        parent::__construct();
        $this->dateOfBirth = new DateTime("1.1.1876.");
        $this->data = array();
    }

    /**
     * @return String
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param String $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return String
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param String $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param String $mail
     */
    public function setEmail($mail)
    {
        $this->email = $mail;
    }

    /**
     * @return DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param DateTime $date
     */
    public function setDateOfBirth(DateTime $date)
    {
        $this->dateOfBirth = $date;
    }

    /**
     * @return int
     */
    public function getBirthDay(){
        return  $this->birthDay;
    }

    /**
     * @param int $birthDay
     */
    public function setBirthDay($birthDay){
        $this->birthDay = (int)$birthDay;
    }

    /**
     * @return int
     */
    public function getBirthMonth(){
        return  $this->birthMonth;
    }

    /**
     * @param int $birthMonth
     */
    public function setBirthMonth($birthMonth){
        $this->birthMonth = (int)$birthMonth;
    }

    /**
     * @return int
     */
    public function getBirthYear(){
        return  $this->birthYear;
    }

    /**
     * @param int $birthYear
     */
    public function setBirthYear($birthYear){
        $this->birthYear = (int)$birthYear;
    }

    /**
     * @return int
     */
    public function getStateID()
    {
        return $this->stateID;
    }

    /**
     * @param $stateID
     */
    public function setStateID($stateID)
    {
        $this->stateID = (int)$stateID;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param $gender
     */
    public function setGender($gender)
    {
        $this->gender = (int)$gender;
    }

    /**
     * @param str|int $key
     * @return mixed
     */
    public function getData($key){
        return $this->data[$key];
    }

    /**
     * @param str|int $key
     * @param mixed $val
     */
    public function setData($key,$val){
        $this->data[$key] = $val;
    }

    public function fillMe($vals){

        parent::fillMe($vals);

        $this->setFirstName($vals["first_name"]);
        $this->setLastName($vals["last_name"]);
        $this->setEmail($vals["email"]);
        if (isset($vals["date_of_birth"])){
            $this->setDateOfBirth(new DateTime($vals["date_of_birth"]));
        }
        $this->setBirthDay($vals["birth_day"]);
        $this->setBirthMonth($vals["birth_month"]);
        $this->setBirthYear($vals["birth_year"]);
        $this->setStateID($vals["state_id"]);
        $this->setGender($vals["gender"]);
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array  = parent::__getArray();
        $array["first_name"] = $this->firstName;
        $array["last_name"] = $this->lastName;
        $array["email"] = $this->email;
        $array["date_of_birth"] = $this->dateOfBirth->format("Y-m-d");
        $array["birth_day"] = $this->birthDay;
        $array["birth_month"] = $this->birthMonth;
        $array["birth_year"] = $this->birthYear;
        $array["state_id"] = $this->stateID;
        $array["gender"] = $this->gender;
        $array["data"] = $this->data;

        return $array;
    }
}