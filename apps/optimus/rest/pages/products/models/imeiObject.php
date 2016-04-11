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

    public function setIMEIOne($IMEIOne){
        $this->IMEIOne = $IMEIOne;
    }

    public function getIMEIOne(){
        return $this->IMEIOne;
    }

    public function setIMEITwo($IMEITwo){
        $this->IMEITwo = $IMEITwo;
    }

    public function getIMEITwo(){
        return $this->IMEITwo;
    }

    public function setProductID($ProductID){
        $this->ProductID = $ProductID;
    }

    public function getProductID(){
        return $this->ProductID;
    }
}