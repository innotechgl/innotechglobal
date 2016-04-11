<?php

class bannersController extends controller {

    /**
     * @var bannersModel
     */
    protected $model;

    public function __construct(bannersModel $model){
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
        $title = filter_input(INPUT_POST,"title",FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST,"description",FILTER_SANITIZE_STRING);

        $result = $this->model->uploadPhotos($files['photo'], (int)$_POST["category_id"], $title, $description);

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
        $data = $this->model->getAllBanners();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }


    /**
     *
     */
    protected function edit(){

        $data = $this->getRequestData();
        $res = $this->model->edit($data["data"]["banner"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));

    }

    /**
     *
     */
    protected function delete(){
        $rawData = $this->getRequestData();
        $this->model->_delete((int)$rawData["data"]["id"]);

    }


    protected function addCategory(){

        $model = new modelCategory();
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
        $model = new modelCategory();

        $rawData = $this->getRequestData();
        $data = $rawData["data"];

        $resultData = $model->_delete($data["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $resultData);

    }

    protected function activate(){
        $rawData = $this->getRequestData();
        $data = $rawData["data"];

        $res = $this->model->activate_deactivate($data["id"],1);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }
    protected function deactivate(){
        $rawData = $this->getRequestData();
        $data = $rawData["data"];

        $res = $this->model->activate_deactivate($data["id"],0);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }
    
    /**
     * Sort
     */
    protected function sort(){
        $rawData = $this->getRequestData();
        $this->model->_sort($rawData["banners"]);
    }

}