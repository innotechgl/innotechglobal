<?php

class productGroupsModel
{
    protected $table;
    protected $engine;

    public function add(array $data){

        // Proveravamo da li je niz OK
        if (!$this->checkData($data)){
            return false;
        }

        // U slucaju da jeste...
        $productGroup = new productGroupsObject();
        $productGroup->setName($data["title"]);
        $productGroup->setDescription($data["description"]);
        $productGroup->setParentID($data["parent_id"]);

        $this->_add($productGroup);
    }

    private function checkData(array $data){
        return true;
    }

    protected function _add(productGroupsObject $productGroup){

    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $productGroups = new productGroupsObject();
        $productGroups->setName($data["title"]);
        $productGroups->setDescription($data["description"]);
        $productGroups->setParentID($data["parent_id"]);

        $this->_update($productGroups);
    }

    protected function _update(productGroupsObject $productGroups){

        $query = "UPDATE ".$this->table." SET title='".$productGroups->getName()."', description='".$productGroups->getDescription()."',parent_id='".$productGroups->getParentID()."',
        product_id='".$productGroups->getProductID()."' WHERE id='".$productGroups->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    protected function _delete(productGroupsObject $productGroups){

        $query = "DELETE ".$this->table." SET title='".$productGroups->getName()."', description='".$productGroups->getDescription()."',parent_id='".$productGroups->getParentID()."',
        product_id='".$productGroups->getProductID()."' WHERE id='".$productGroups->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }
}



