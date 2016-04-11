<?php

/**
 * Class customerOrdersModel
 *
 */

class customerOrdersModel extends page_class
{

    /**
     * @var customerOrdersModel
     */
    protected $customers;
    protected $table;
    protected $engine;


    public function __construct()
    {
        parent::__construct();

        $this->customer_id = new customersModel();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {

        if (!$this->checkData($data)) {
            return false;
        }

        $customerOrders = new customerOrdersObject();
        $customerOrders->setTitle($data["title"]);
        $customerOrders->setDate($data["date"]);
        $customerOrders->setPaid($data["paid"]);
        $customerOrders->setTotalSum($data["total_sum"]);

        $id = $this->_add($customerOrders);
        $data["customer_id"] = $id;

        $customer_id = $this->_addCustomerID($data);
    }

    private function checkData(array $data)
    {
        return true;
    }

    /**
     * @param customerOrdersObject $customerOrders
     * @return mixed
     */
    protected function _add(customerOrdersObject $customerOrders)
    {
        $query = "SELECT " . $this->table . " WHERE id='" . $customerOrders->getID() . "'";
        $id = $this->engine->dbase->insertQuery($query);
        return $id;
    }

    /*
     * @todo add validation
     */

    /**
     * @param $data
     * @return bool
     */
    protected function _addCustomerID($data)
    {
        $res = $this->customer_id->add($data);
        return $res;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        if (!$this->checkData($data)) {
            return false;
        }

        $customerOrders = new customerOrdersObject();
        $customerOrders->setTitle($data["title"]);
        $customerOrders->setDate($data["date"]);
        $customerOrders->setPaid($data["paid"]);
        $customerOrders->setTotalSum($data["total_sum"]);

        $this->_update($customerOrders);

    }

    /**
     * @$param customerOrdersObject $customerOrders
     * @return mixed
     */
    protected function _update(customerOrdersObject $customerOrders)
    {

        $query = "UPDATE " . $this->table . " SET title='" . $customerOrders->getTitle() . "', date='" . $customerOrders->getDate() . "',paid='" . $customerOrders->getPaid() . "',total_sum='" . $customerOrders->getTotalSum() . "',
        customer_id='" . $customerOrders->getCustomerID() . "' WHERE id='" . $customerOrders->getID() . "'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {

        /**
         * Product
         */
        $this->customer_orders->deleteByID($id);

        /**
         * Delete product
         */
        $this->_delete($id);
    }


    /**
     * @param int $id
     * @return bool
     */
    protected function _delete($id)
    {

        $query = "DELETE FROM " . $this->table . " WHERE id='" . (int)$id . "' LIMIT 1;";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    protected function _validate($engine)
    {
        $engine->load_util('validator');

        $data = $_POST;

        $requested_for_validation = array
        (
            "title" => array(
                validator_class::_REQUESTED_,
                validator_class::_MIN_LENGTH_ => 2
            ),
            "date" => array(
                validator_class::_REQUESTED_
            ),
            "paid" => array(
                validator_class::_REQUESTED,
                validator_class::_MIN_LENGTH_ => 1
            ),
            "total_sum" => array(
                validator_class::_REQUESTED_,
                validator_class::_MIN_LENGTH_ => 1
            )
        );

        if ($engine->util_validator->validate_all($requested_for_validation, $_POST)) {
            print_r($engine->util_validator->error_log);
        }
        else{
            $result = $this ->$data= array('title', 'date', 'paid', 'total_sum');
            return $result;
        }
    }
}









