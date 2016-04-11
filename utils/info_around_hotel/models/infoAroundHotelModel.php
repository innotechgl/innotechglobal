<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:22 PM
 */


class infoAroundHotelModel extends page_class
{
    protected $table;
    protected $engine;


    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $aroundHotel = new infoAroundHotelObject();
        $aroundHotel ->setService($data["service"]);
        $aroundHotel ->setServiceCategories($data["service_categories"]);
        $aroundHotel ->setTouristInfo($data["tourist_info"]);

        $this->_add($aroundHotel);
    }

    private function checkData(array $data){
        return true;
    }

    protected function _add(infoAroundHotelObject $aroundHotel){

    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $aroundHotel = new infoAroundHotelObject();
        $aroundHotel ->setService($data["service"]);
        $aroundHotel ->setServiceCategories($data["service_categories"]);
        $aroundHotel ->setTouristInfo($data["tourist_info"]);

        $this->_update($aroundHotel);
    }

    protected function _update(infoAroundHotelObject $aroundHotel){

        $query = "UPDATE ".$this->table." SET service='".$aroundHotel->getService()."', service_categories='".$aroundHotel->getServiceCategories()."',tourist_info='".$aroundHotel->getTouristInfo()."',
        id='".$aroundHotel->getID()."' WHERE id='".$aroundHotel->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    protected function _delete(productModelsObject $aroundHotel){

        $query = "DELETE ".$this->table." SET service='".$aroundHotel->getService()."', service_categories='".$aroundHotel->getServiceCategories()."',tourist_info='".$aroundHotel->getTouristInfo()."',
        id='".$aroundHotel->getID()."' WHERE id='".$aroundHotel->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }
}