<?php

/**
 * Class factory
 */
class factory extends mainObject
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
     * @param Int $parentID
     */
    public function setParentID($parentID){
        $this->parentID = (int)$parentID;
    }

    /**
     * @return Int
     */
    public function getParentID(){
        return $this->parentID;
    }

    /**
     * @param Int $stateID
     */
    public function setStateID($stateID){
        $this->stateID = (int)$stateID;
    }

    /**
     * @return Int
     */
    public function getStateID(){
        return $this->stateID;
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
    public function getTitle(){
        return $this->title;
    }


    /**
     * @param Int $code
     */
    public function setCode($code){
        $this->code = (int)$code;
    }

    /**
     * @return Int
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["parent_id"] = $this->parentID;
        $array["title"] = $this->title;
        $array["code"] = $this->code;
        $array["state_id"] = $this->stateID;

        return $array;
    }

    public function fillMe($vals){

        parent::fillMe($vals);

        $this->setCode($vals["postal_code"]);
        $this->setStateID($vals["state_id"]);
        $this->setParentID($vals["parent_id"]);
        $this->setTitle($vals["title"]);
    }


}