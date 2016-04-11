<?php

class articlePhotosController extends controller {

    /**
     * @var modelArticlePhotos
     */
    protected $model;

    public function __construct(modelArticlePhotos $model){
        parent::__construct($model);

        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    /**
     * Upload photos
     */
    protected function upload(){

        $files = $this->engine->security->get_files(array("photo"), array("png","jpg"));

        $result = $this->model->uploadPhotos($files['photo'], (int)$_POST["category_id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $result);

    }

    protected function getAllCategories(){

        $data = $this->model->getAllCategories();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }


    protected function getAllPhotos(){
        $data = $this->model->getAllPhotos();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }


    /**
     *
     */
    protected function update(){

    }

    /**
     *
     */
    protected function delete(){
        $rawData = $this->getRequestData();
        $this->model->_delete((int)$rawData["data"]["id"]);

    }


    protected function addCategory(){

        $model = new modelArticlePhotosCategory();
        $rawData = $this->getRequestData();
        $data = $rawData["data"];
        $title = filter_var($data["title"],FILTER_SANITIZE_STRING);
        $parentID = (int)$data["parent_id"];
        if (trim($title)!==""){
            $dataSaved = $model->_add($title,$parentID);

            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                $dataSaved);
            }
        else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                array("msg"=>"ERROR: No title."));
            }

    }

    protected function deleteCategory(){
        $model = new modelArticlePhotosCategory();

        $rawData = $this->getRequestData();
        $data = $rawData["data"];

        $resultData = $model->_delete($data["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $resultData);

    }

}