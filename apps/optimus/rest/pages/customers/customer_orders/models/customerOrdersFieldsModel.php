<?php

class customerOrderFieldsModel extends page_class
{

    /**
     * @var customerOrderFieldsModel
     */
    protected $customers;
    protected $table;
    protected $engine;


    public function __construct()
    {
        parent::__construct();

        $this->order_id = new customerOrdersModel();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data, $customerOrderFields){

        if (!$this->checkData($data)){
            return false;
        }

        $customerOrderFields = new customerOrderFieldsObject();
        $customerOrderFields ->setDescription($data["description"]);
        $customerOrderFields ->setValue($data["value"]);
        $customerOrderFields ->setCurrency($data["currency"]);

        $id = $this->_add($customerOrderFields);
        $data["order_id"] = $id;

        $customer_id = $this->_addCustomerID($data);
    }

    private function checkData(array $data){
        return true;
    }

    /**
     * $@param customerOrderFieldssObject $customerOrderFields
     * @return mixed
     */
    protected function _add(customerOrderFieldsObject $customerOrderFields){
        $query = "SELECT ".$this->table." WHERE id='".$customerOrderFields->getID()."'";
        $id = $this->engine->dbase->insertQuery($query);
        return $id;
    }

    /*
     * @todo add validation
     */

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $customerOrderFields = new customerOrderFieldsObject();
        $customerOrderFields ->setDescription($data["description"]);
        $customerOrderFields ->setValue($data["value"]);
        $customerOrderFields ->setCurrency($data["currency"]);

        $this->_update($customerOrderFields);

    }

    /**
     * @$param customerOrderFieldsObject $customerOrderFields
     * @return mixed
     */
    protected function _update(customerOrderFieldsObject $customerOrderFields){

        $query = "UPDATE ".$this->table." SET description='".$customerOrderFields->getDescription()."', value='".$customerOrderFields->getValue()."',currency='".$customerOrderFields->getCurrency()."',
        order_id='".$customerOrderFields->OrderID()."' WHERE id='".$customerOrderFields->getID()."'";

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
        $this->customer_order_fields->deleteByID($id);

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

    /**
     * @param $data
     * @return bool
     */
    protected function _addOrderID($data){
        $res = $this->order_id->add($data);
        return $res;
    }

}






