<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class productsObject extends mainObject
{

    protected $ModelID;
    protected $SerialNumber;

    public function __construct()
    {
        parent::__construct();
    }

    public function getModelID(){
        return $this->ModelID;
    }

    public function setModelID($ModelID){
        $this->ModelID = $ModelID;
    }

    public function getSerialNumber(){
        return $this->SerialNumber;
    }

    public function setSerialNumber($SerialNumber){
        $this->SerialNumber = $SerialNumber;
    }

}