<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:22 PM
 */


class documentsCategoryModel
{

    protected $table;
    protected $engine;

    public function __construct()
    {
        global $engine;

        $this->engine =& $engine;

        $this->table = "document_categories";
    }

    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $documentsCategory = new documentsCategoryObject();
        $documentsCategory->setParentID($data["imei_one"]);
        $documentsCategory->setName($data["imei_two"]);


        $this->_add($documentsCategory);
    }

    protected function _add(documentsCategoryObject $documentsCategoryObject){

    }

    private function checkData(array $data){
        return true;
    }



    public function update(array $data)
    {
        if (!$this->checkData($data)){
            return false;
        }

        $documentsCategory = new documentsCategoryObject();
        $documentsCategory->setParentID($data["parent_id"]);
        $documentsCategory->setName($data["name"]);
        $documentsCategory->setID($data["id"]);

        $this->_update($documentsCategory);
    }

    protected function _update(documentsCategoryObject $documentsCategory){

        $query = "UPDATE ".$this->table." SET parent_id='".$documentsCategory->getParentID()."', name='".$documentsCategory->getName()."',
        document_id='".$documentsCategory->getDocumentID()."' WHERE id='".$documentsCategory->getID()."'";

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
    public function deleteBydocumentCategoryIDd($id){

        $query = "DELETE FROM ".$this->table." WHERE product_id='".(int)$id."' LIMIT 1;";
        $res = $this->engine->dbase->insertQuery($query);

        return $res;
    }

}