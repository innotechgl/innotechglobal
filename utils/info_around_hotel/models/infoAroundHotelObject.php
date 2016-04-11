<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class infoAroundHotelObject extends mainObject
{

    protected $Service;
    protected $ServiceCategories;
    protected $TouristInfo;

    public function __construct()
    {
        parent::__construct();
    }

    public function getService(){
        return $this->Service;
    }

    public function setService($Service){
        $this->Service = $Service;
    }

    public function getServiceCategories(){
        return $this->ServiceCategories;
    }

    public function setServiceCategories($ServiceCategories){
        $this->ServiceCategories = $ServiceCategories;
    }

    public function getTouristInfo(){
        return $this->TouristInfo;
    }

    public function setTourstInfo($TouristInfo){
        $this->TouristInfo = $TouristInfo;
    }
}