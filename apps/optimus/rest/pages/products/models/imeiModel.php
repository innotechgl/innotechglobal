<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:22 PM
 */


class imeiModel
{

    protected $table;
    protected $engine;

    public function __construct()
    {
        global $engine;

        $this->engine =& $engine;

        $this->table = "imeis";
    }

    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $imei = new imeiObject();
        $imei->setIMEIOne($data["imei_one"]);
        $imei->setIMEITwo($data["imei_two"]);
        $imei->setProductID($data["product_id"]);

        $this->_add($imei);
    }

    private function checkData(array $data){
        return true;
    }

    protected function _add(imeiObject $imeiObject){

    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $imei = new imeiObject();
        $imei->setIMEIOne($data["imei_one"]);
        $imei->setIMEITwo($data["imei_two"]);
        $imei->setProductID($data["product_id"]);
        $imei->setID($data["id"]);

        $this->_update($imei);
    }

    protected function _update(imeiObject $imei){

        $query = "UPDATE ".$this->table." SET imei_one='".$imei->getIMEIOne()."', imei_two='".$imei->getIMEITwo()."',
        product_id='".$imei->getProductID()."' WHERE id='".$imei->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id){

        $query = "DELETE FROM ".$this->table." WHERE id='".$id."' LIMIT 1;";
        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteByProductIDd($id){

        $query = "DELETE FROM ".$this->table." WHERE product_id='".(int)$id."' LIMIT 1;";
        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

}