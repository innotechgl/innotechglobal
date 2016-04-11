<?php

class controllerVideoGallery extends controller {

    /**
     * @var modelVideoGallery
     */
    protected $model;

    public function __construct(modelVideoGallery $model){

        parent::__construct($model);
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function getAll(){
        $data = $this->model->getAll();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function load(){
        $data = $this->getRequestData();

        $videoItem = $this->model->load($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $videoItem);
    }

    /**
     * Add
     */
    protected function add(){
        $data = $this->getRequestData();

        $videoItem = $this->model->_add($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $videoItem);
    }

    /**
     * Update
     */
    protected function update(){
        $data = $this->getRequestData();

        $res = $this->model->_update($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }

    /**
     * Delete
     */
    protected function delete(){
        $data = $this->getRequestData();

        $res = $this->model->_delete($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }

    /**
     * Activate
     */
    protected function activate(){
        $data = $this->getRequestData();

        $res = $this->model->activate($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }

    /**
     * Deactivate
     */
    protected function deactivate(){

        $data = $this->getRequestData();

        $res = $this->model->deactivate($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }



}