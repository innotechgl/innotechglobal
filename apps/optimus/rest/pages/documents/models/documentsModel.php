<?php

/**
 * Class documentsModel
 *
 */

class documentsModel extends page_class
{

    /**
     * @var documentsCategoryModelModel
     */
    protected $documentCategory;
    protected $table;
    protected $engine;


    public function __construct()
    {
        parent::__construct();

        $this->documentCategory = new documentsCategoryModel();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data){

        if (!$this->checkData($data)){
            return false;
        }

        $documents = new documentsObject();
        $documents ->setTitle($data["title"]);
        $documents ->setFilename($data["filename"]);

        $id = $this->_add($documents);
        $data["category_id"] = $id;

        $categoryID = $this->_adddocumentCategory($data);
    }

    private function checkData(array $data){
        return true;
    }

    /**
     * @param documentsObject $documents
     * @return mixed
     */
    protected function _add(documentsObject $documents){
        $query = "SELECT ".$this->table." WHERE id='".$documents->getID()."'";
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

        $documents = new documentsObject();
        $documents ->setTitle($data["title"]);
        $documents ->setFilename($data["filename"]);
        $documents->setID($data["id"]);

        $this->_update($documents);
    }

    /**
     * $@param documentsObject $documents
     * @return mixed
     */
    protected function _update(documentsObject $documents){

        $query = "UPDATE ".$this->table." SET title='".$documents->getTitle()."', filename='".$documents->getFilename()."',
        category_id='".$documents->getdocumentCategoryID()."' WHERE id='".$documents->getID()."'";

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
        $this->documentCategory->deleteBydocumentCategoryIDd($id);

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
    protected function _adddocumentsCategory($data){
        $res = $this->documentCategory->add($data);
        return $res;
    }

}






