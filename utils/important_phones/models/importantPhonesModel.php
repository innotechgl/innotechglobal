<?php

class importantPhonesModel extends page_class
{
    protected $table;
    protected $engine;


    public function add(array $data){

    if (!$this->checkData($data)){
        return false;
    }

    $importantPhones = new importantPhonesObject();
    $importantPhones ->setTitle($data["title"]);
    $importantPhones ->setContent($data["content"]);
    $importantPhones ->setLink($data["link"]);

    $this->_add($importantPhones);
}

    private function checkData(array $data){
    return true;
}

    protected function _add(importantPhonesObject $importantPhones){

}

    public function update(array $data)
{
    if (!$this->checkData($data)){
        return false;
    }

    $importantPhones = new importantPhonesObject();
    $importantPhones ->setTitle($data["title"]);
    $importantPhones ->setContent($data["content"]);
    $importantPhones ->setLink($data["link"]);

    $this->_update($importantPhones);
}

    protected function _update(importantPhonesObject $importantPhones){

    $query = "UPDATE ".$this->table." SET title='".$importantPhones->getTitle()."', content='".$importantPhones->getContent()."',link='".$importantPhones->getLink()."',
        id='".$importantPhones->getID()."' WHERE id='".$importantPhones->getID()."'";

    $res = $this->engine->dbase->insertQuery($query);

    return $res;
}

    protected function _delete(importantPhonesObject $importantPhones){

    $query = "DELETE ".$this->table." SET title='".$importantPhones->getTitle()."', content='".$importantPhones->getContent()."',link='".$importantPhones->getLink()."',
        id='".$importantPhones->getID()."' WHERE id='".$importantPhones->getID()."'";

    $res = $this->engine->dbase->insertQuery($query);

    return $res;
}
}