<?php


class productGroupsObject extends mainObject
{

    protected $name;
    protected $description;
    protected $ParentID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getParentID(){
        return $this->ParentID;
    }

    public function setParentID($ParentID){
        $this->ParentID = $ParentID;
    }
}