<?php

/**
 *
 * CREATE TABLE `products` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`product_line_id` int(11) DEFAULT NULL,
`title` varchar(256) DEFAULT NULL,
`description` varchar(256) DEFAULT NULL,
`created` datetime DEFAULT NULL,
`creator` int(3) DEFAULT NULL,
`modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
`modifier` int(3) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 */

class product extends mainObject{
    /**
     * @var int
     */
    protected $parentID;
    /**
     * @var String
     */
    protected $title;
    /**
     * @var @int
     */
    protected $code;
    /**
     * @var @int
     */
    protected $stateID;

    /**
     * @return int
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
     * @return int
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
     * @return int
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @param Int $code
     */
    public function setCode($code){
        $this->code = (int)$code;
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

    public function fillMe($data){

        parent::fillMe($data);
        $this->setTitle($data["title"]);
        $this->setParentID($data["parent_id"]);
        $this->setCode($data["postal_code"]);
        $this->setStateID($data["state_id"]);

    }
}