<?php

/**
 * Class places
 */
class places extends mainObject
{

    /**
     * @var Int
     */
    protected $parentID;
    /**
     * @var String
     */
    protected $title;
    /**
     * @var Int
     */
    protected $code;
    /**
     * @var Int
     */
    protected $stateID;

    /**
     * @return Int
     */
    public function getParentID(){
        return $this->parentID;
    }

    /**
     * @param Int $parentID
     */
    public function setParentID($parentID){
        $this->parentID = (int)$parentID;
    }

    /**
     * @return Int
     */
    public function getStateID(){
        return $this->stateID;
    }

    /**
     * @param Int $stateID
     */
    public function setStateID($stateID){
        $this->stateID = (int)$stateID;
    }

    /**
     * @return String
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle($title){
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @param String $code
     */
    public function setCode($code){
        $this->code = $code;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["state_id"] = $this->stateID;
        $array["title"] = $this->title;
        $array["code"] = $this->code;
        $array["parent_id"] = $this->parentID;

        return $array;
    }

    public function fillMe($data){

        parent::fillMe($data);
        $this->setTitle($data["title"]);
        $this->setStateID($data["state_id"]);
        $this->setCode($data["postal_code"]);
        $this->setParentID($data["parent_id"]);

    }

}