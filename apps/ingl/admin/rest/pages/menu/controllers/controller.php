<?php

class controllerMenu extends controller {

    /**
     * @var modelMenu
     */
    protected $model;
    protected $rest;

    /**
     * @param $model
     */
    public function __construct($model){

        parent::__construct($model);
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function getAll(){

        $menus = $this->model->getAll();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $menus);

    }

    protected function load(){
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

        $data = $this->model->load($id);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function save(){
        $data = $this->getRequestData();

        $res = $this->model->addNew($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $res);
    }

    protected function update(){
        $data = $this->getRequestData();

        $res = $this->model->updateMenu($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $res);
    }

    protected function delete(){

        $data = $this->getRequestData();
        $this->model->delete($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

    protected function activate(){
        $data = $this->getRequestData();
        $this->model->activate($data["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

    protected function getOptions(){
        $options = $this->model->getOptions();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $options);
    }

    protected function getPages(){
        $pages = $this->model->getPages();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $pages);
    }

    protected function deactivate(){


        $data = $this->getRequestData();

        $this->model->deactivate($data["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }
    
    protected function sort(){
        $rawData = $this->getRequestData();
        $this->model->_sort($rawData["menus"]);
    }
}