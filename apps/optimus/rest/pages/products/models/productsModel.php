<?php

/**
 * Class productsModel
 *
 */

class productsModel extends page_class
{

    /**
     * @var imeiModel
     */
    protected $imeis;


    public function __construct()
    {
        parent::__construct();

        $this->imeis = new imeiModel();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $product = new productsObject();
        $product ->setModelID($data["model_id"]);
        $product ->setSerialNumber($data["serial_number"]);

        $id = $this->_add($product);
        $data["product_id"] = $id;

        $imeiID = $this->_addIMEI($data);
    }

    /**
     * @param productsObject $product
     * @return mixed
     */
    protected function _add(productsObject $product){
        $query = "SELECT ".$this->table." WHERE id='".$product->getID()."'";
        $id = $this->engine->dbase->insertQuery($query);
        return $id;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function _addIMEI($data){
        $res = $this->imeis->add($data);
        return $res;
    }

    /*
     * @todo add validation
     */
    private function checkData(array $data){
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $product = new productsObject();
        $product ->setModelID($data["model_id"]);
        $product ->setSerialNumber($data["serial_number"]);
        $product->setID($data["id"]);

        $this->_update($product);
    }

    /**
     * @param productsObject $product
     * @return mixed
     */
    protected function _update(productsObject $product){

        $query = "UPDATE ".$this->table." SET model_id='".$product->getModelID()."', serial_number='".$product->getSerialNumber()."',
        product_id='".$product->getProductID()."' WHERE id='".$product->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    /**
     * @param $id
     */
    public function delete($id){

        /**
         * Product
         */
        $this->imeis->deleteByProductIDd($id);

        /**
         * Delete product
         */
        $this->_delete($id);
    }


    /**
     * @param int $id
     * @return bool
     */
    protected function _delete($id){

        $query = "DELETE FROM ".$this->table." WHERE id='".(int)$id."' LIMIT 1;";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

}






