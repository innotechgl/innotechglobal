<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 * Date: 2/24/2016
 * Time: 12:46 PM
 */

class customersObject extends mainObject
{

    protected $Name;
    protected $Address;
    protected $TaxNo;
    protected $RegNo;
    protected $PlaceID;
    protected $OtherInfo;

    public function __construct()
    {
        parent::__construct();
    }

    public function setName($Name){
        $this->Name = $Name;
    }

    public function getName(){
        return $this->Name;
    }

    public function setAddress($Address){
        $this->Address = $Address;
    }

    public function getAddress(){
        return $this->Address;
    }

    public function setTaxNo($TaxNo){
        $this->TaxNo = $TaxNo;
    }

    public function getTaxNo(){
        return $this->TaxNo;
    }

    public function setRegNo($RegNo){
        $this->RegNo = $RegNo;
    }

    public function getRegNo(){
        return $this->RegNo;
    }

    public function setPlaceID($PlaceID){
        $this-> PlaceID = $PlaceID;
    }

    public function getPlaceID(){
        return $this->PlaceID;
    }

    public function setOtherInfo($OtherInfo){
        $this-> OtherInfo = $OtherInfo;
    }

    public function getOtherInfo(){
        return $this->OtherInfo;
    }
}