<?php


abstract class customers extends mainObject
{
    /**
     * @var String
     */
    protected $name;
    /**
     * @var String
     */
    protected $address;
    /**
     * @var String
     */
    protected $pib;
    /**
     * @var String
     */
    protected $mb;
    /**
     * @var int
     */
    protected $placeID;
    /**
     * @var String
     */
    protected $otherInfo;

    /**
     * @return String
     */
    public function getName()
    {
       return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param String $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return String
     */
    public function getPib()
    {
        return $this->pib;
    }

    /**
     * @param String$pib
     */
    public function setPib($pib)
    {
        $this->pib = $pib;
    }

    /**
     * @return String
     */
    public function getMb()
    {
        return $this->mb;
    }

    /**
     * @param String $mb
     */
    public function setMb($mb)
    {
        $this->mb = $mb;
    }

    /**
     * @return int
     */
    public function getStateID()
    {
        return $this->placeID;
    }

    /**
     * @return String
     */
    public function getOtherInfo()
    {
        return $this->otherInfo;
    }

    /**
     * @param String $otherInfo
     */
    public function setOtherInfo($otherInfo)
    {
        $this->otherInfo = $otherInfo;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array  = parent::__getArray();
        $array["name"] = $this->name;
        $array["address"] = $this->address;
        $array["pib"] = $this->pib;
        $array["mb"] = $this->mb;
        $array["place_id"] = $this->placeID;
        $array["other_info"] = $this->otherInfo;

        return $array;
    }

    public function fillMe($vals){

        parent::fillMe($vals);

        $this->setName($vals["name"]);
        $this->setAddress($vals["address"]);
        $this->setOtherInfo($vals["other_info"]);
        $this->setPib($vals["pib"]);
        $this->setMb($vals["mb"]);
        $this->setPlaceID($vals["place_id"]);
    }

    /**
     * @param $placeID
     */
    public function setPlaceID($placeID)
    {
        $this->placeID = (int)$placeID;
    }



}