<?php

class infoAboutHotelModel extends page_class
{
    protected $table;
    protected $engine;


    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $aboutHotel = new infoAboutHotelObject();
        $aboutHotel ->setTitle($data["title"]);
        $aboutHotel ->setContent($data["content"]);
        $aboutHotel ->setLink($data["link"]);

        $this->_add($aboutHotel);
    }

    private function checkData(array $data){
        return true;
    }

    protected function _add(infoAboutHotelObject $aboutHotel){

    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $aboutHotel = new infoAboutHotelObject();
        $aboutHotel ->setTitle($data["title"]);
        $aboutHotel ->setContent($data["content"]);
        $aboutHotel ->setLink($data["link"]);

        $this->_update($aboutHotel);
    }

    protected function _update(infoAboutHotelObject $aboutHotel){

        $query = "UPDATE ".$this->table." SET title='".$aboutHotel->getTitle()."', content='".$aboutHotel->getContent()."',link='".$aboutHotel->getLink()."',
        id='".$aboutHotel->getID()."' WHERE id='".$aboutHotel->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    protected function _delete(productModelsObject $aboutHotel){

        $query = "DELETE ".$this->table." SET title='".$aboutHotel->getTitle()."', content='".$aboutHotel->getContent()."',link='".$aboutHotel->getLink()."',
        id='".$aboutHotel->getID()."' WHERE id='".$aboutHotel->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }
}