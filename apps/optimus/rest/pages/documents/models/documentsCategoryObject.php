<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class documentsCategoryObject extends mainObject
{

    protected $ParentID;
    protected $Name;

    public function __construct()
    {
        parent::__construct();
    }

    public function getParentID(){
        return $this->ParentID;
    }

    public function setParentID($ParentID){
        $this->ParentID = $ParentID;
    }

    public function getName(){
        return $this->Name;
    }

    public function setName($Name){
        $this->Name = $Name;
    }

}