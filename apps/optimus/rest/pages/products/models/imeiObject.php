<?php

class imeiObject extends mainObject
{

    protected $IMEIOne;
    protected $IMEITwo;
    protected $ProductID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getIMEIOne(){
        return $this->IMEIOne;
    }

    public function setIMEIOne($IMEIOne){
        $this->IMEIOne = $IMEIOne;
    }

    public function getIMEITwo(){
        return $this->IMEITwo;
    }

    public function setIMEITwo($IMEITwo){
        $this->IMEITwo = $IMEITwo;
    }

    public function getProductID(){
        return $this->ProductID;
    }

    public function setProductID($ProductID){
        $this->ProductID = $ProductID;
    }
}