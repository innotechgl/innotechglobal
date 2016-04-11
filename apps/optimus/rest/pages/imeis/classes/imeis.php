<?php

class imeis extends mainObject
{
    /**
     * @var Int
     */
    protected $imeiOne;
    /**
     * @var Int
     */
    protected $imeiTwo;
    /**
     * @var Int
     */
    protected $productID;

    /**
     * @return Int
     */
    public function getImeiOne(){
        return $this->imeiOne;
    }

    /**
     * @param Int $imeiOne
     */
    public function setImeiOne($imeiOne){
        $this->imeiOne = (int)$imeiOne;
    }

    /**
     * @return Int
     */
    public function getImeiTwo(){
        return $this->imeiTwo;
    }

    /**
     * @param Int $imeiTwo
     */
    public function setImeiTwo($imeiTwo){
        $this->imeiTwo = (int)$imeiTwo;
    }

    /**
     * @return Int
     */
    public function getProductID(){
        return $this->productID;
    }

    /**
     * @param Int $productID
     */
    public function setProductID($productID){
        $this->productID = (int)$productID;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["imei_one"] = $this->imeiOne;
        $array["imei_two"] = $this->imeiTwo;
        $array["product_id"] = $this->productID;

        return $array;
    }


}