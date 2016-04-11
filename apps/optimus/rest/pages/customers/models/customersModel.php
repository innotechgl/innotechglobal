<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/24/2016
 * Time: 12:46 PM
 */


class customersModel extends page_class
{
    protected $table;
    protected $engine;
    protected $customers;

    public function __construct()
    {
        parent::__construct();
    }

    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $customers = new customersObject();
        $customers ->setName($data["name"]);
        $customers ->setAddress($data["address"]);
        $customers ->setTaxNo($data["pib"]);
        $customers ->setRegNo($data["mb"]);
        $customers ->setPlaceID($data["place_id"]);
        $customers ->setOtherInfo($data["other_info"]);

        $this->_add($customers);
    }

    protected function _add(customersObject $customersModels){

    }

    private function checkData(array $data){
        return true;
    }

    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $customers = new customersObject();
        $customers ->setName($data["name"]);
        $customers ->setAddress($data["address"]);
        $customers ->setTaxNo($data["pib"]);
        $customers ->setRegNo($data["mb"]);
        $customers ->setPlaceID($data["place_id"]);
        $customers ->setOtherInfo($data["other_info"]);

        $this->_update($customers);
    }

    protected function _update(customersObject $customers){

        $query = "UPDATE ".$this->table." SET name='".$customers->getName()."', address='".$customers->getAddress()."',pib='".$customers->getTaxNo()."',mb='".$customers->getRegNo()."',place_id='".$customers->getPlaceID()."',other_info='".$customers->getOtherInfo()."' WHERE id='".$customers->getID()."'";

        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

    public function delete($id){

        /**
         * Product
         */
        $this->customers->deleteByID($id);

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

    protected function _validate($engine){
        $engine->load_util('validator');

        $data = $_POST;

        $requested_for_validation = array
        (
            "name" => array(
                validator_class::_REQUESTED_,
                validator_class::_MIN_LENGTH_ => 2
            ),
            "address" => array(
                validator_class::_REQUESTED_,
                validator_class::_MIN_LENGTH_ =>10
            ),
            "pib" => array(
                validator_class::_REQUESTED_
            ),
            "mb" => array(
                validator_class::_REQUESTED_
            ),
            "place_id" => array(
                validator_class::_REQUESTED_
            )
        );

        if ($engine->util_validator->validate_all($requested_for_validation, $_POST)) {
            print_r($engine->util_validator->error_log);
        }
        else {

        }

    }
}