<?php

/**
 * Class placesModel
 *
 */

class placesModel extends page_class
{

    /**
     * @var placesModel
     */

    protected $places;
    protected $table;
    protected $engine;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $places = new placesObject();
        $places ->setParentID($data["parent_id"]);
        $places ->setTitle($data["title"]);
        $places ->setPostalCode($data["postal_code"]);
        $places ->setStateID($data["state_id"]);

        $id = $this->_add($places);
        }


    private function checkData(array $data){
        return true;
    }

    /**
     * @param $data
     * @return bool
     */

    /*
     * @todo add validation
     */
    /**
     * @param placesObject $places
     * @return mixed
     */
    protected function _add(placesObject $places){
        $query = "SELECT ".$this->table." WHERE id='".$places->getID()."'";
        $id = $this->engine->dbase->insertQuery($query);
        return $id;
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

        $places = new placesObject();
        $places ->setParentID($data["parent_id"]);
        $places ->setTitle($data["title"]);
        $places ->setPostalCode($data["postal_code"]);
        $places ->setStateID($data["state_id"]);

        $this->_update($places);

    }

    /**
     * @$param customerOrdersObject $customerOrders
     * @return mixed
     */
    protected function _update(placesObject $places){

        $query = "UPDATE ".$this-> table." SET parent_id='".$places->getParentID()."', title='".$places->getTitle()."', postal_code ='".$places->getPostalCode()."',state_id ='".$places->getStateID()."' WHERE id='".$places->getID()."'";

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
        $this->places->deleteByID($id);

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






