<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class productModelsObject extends mainObject
{

    protected $Title;
    protected $Description;
    protected $HasIMEI;
    protected $DoubleIMEI;
    protected $GroupID;

    public function __construct()
    {
        parent::__construct();
    }

    public function setTitle($title){
        $this->Title = $title;
    }

    public function getTitle(){
        return $this->Title;
    }

    public function setDescription($Description){
        $this->Description = $Description;
    }

    public function getDescription(){
        return $this->Description;
    }

    public function setHasIMEI($HasIMEI){
        $this->HasIMEI = $HasIMEI;
    }

    public function getHasIMEI(){
        return $this->HasIMEI;
    }

    public function setDoubleIMEI($DoubleIMEI){
        $this->DoubleIMEI = $DoubleIMEI;
    }

    public function getDoubleIMEI(){
        return $this->DoubleIMEI;
    }

    public function setGroupID($GroupID){
        $this-> GroupID = $GroupID;
    }

    public function getGroupID(){
        return $this->GroupID;
    }
}