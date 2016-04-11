<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:22 PM
 */


class productModelsModel extends page_class
{
    protected $table;
    protected $engine;


    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $productModels = new productModelsObject();
        $productModels ->setTitle($data["title"]);
        $productModels ->setDescription($data["description"]);
        $productModels ->setHasIMEI($data["has_imei"]);
        $productModels ->setDoubleIMEI($data["double_imei"]);
        $productModels ->setGroupID($data["group_id"]);

        $this->_add($productModels);
    }

    protected function _add(productsObject $productModels){

    }

    private function checkData(array $data){
        return true;
    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $productModels = new productModelsObject();
        $productModels->setTitle($data["title"]);
        $productModels->setDescription($data["description"]);
        $productModels->setHasIMEI($data["has_imei"]);
        $productModels->setDoubleIMEI($data["double_imei"]);
        $productModels ->setGroupID($data["group_id"]);

        $this->_update($productModels);
    }

    protected function _update(productModelsObject $productModels){

        $query = "UPDATE ".$this->table." SET title='".$productModels->getTitle()."', description='".$productModels->getDescription()."',has_imei='".$productModels->getHasIMEI()."',double_imei='".$productModels->getDoubleIMEI()."',group_id='".$productModels->getGroupID()."',
        product_id='".$productModels->getProductID()."' WHERE id='".$productModels->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    protected function _delete(productModelsObject $productModels){

        $query = "DELETE ".$this->table." SET title='".$productModels->getTitle()."', description='".$productModels->getDescription()."',has_imei='".$productModels->getHasIMEI()."',double_imei='".$productModels->getDoubleIMEI()."',group_id='".$productModels->getGroupID()."',
        product_id='".$productModels->getProductID()."' WHERE id='".$productModels->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }
}