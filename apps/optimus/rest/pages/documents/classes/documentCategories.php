<?php


class documentCategories extends mainObject
{
    /**
     * @var Int
     */
    protected $parentID;
    /**
     * @var String
     */
    protected $name;

    /**
     * @return Int
     */
    public function getParentID(){
        return $this->parentID;
    }

    /**
     * @param Int $parentID
     */
    public function setParentID($parentID){
        $this->parentID = (int)$parentID;
    }

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
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["name"] = $this->name;
        $array["parent_id"] = $this->parentID;

        return $array;
    }

    public function fillMe($vals){

        parent::fillMe($vals);

        $this->setName($vals["name"]);
        $this->setParentID($vals["parent_id"]);

    }


}