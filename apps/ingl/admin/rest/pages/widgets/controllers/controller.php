<?php
class controllerWidgets extends controller {

    /**
     * @var modelWidgets
     */
    protected $model;

    public function __construct(modelWidgets $model){
        parent::__construct($model);

        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function getPositions(){
        $data = $this->model->get_positions();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getAllAvailableWidgets(){
        $data = $this->model->getAllAvailableWidgets();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getAll(){
        $data = $this->model->getAll();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function loadWidget(){

        $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);

        $data = $this->model->loadWidget($id);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
           $data);

    }

    protected function save(){
        $rawData = $this->getRequestData();

        $data = $this->model->save($rawData["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }

    protected function update(){
        $rawData = $this->getRequestData();

        $data = $this->model->_update($rawData["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }

    protected function delete(){
        $rawData = $this->getRequestData();

        $data = $this->model->_delete($rawData["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }

    protected function activate(){
        $rawData = $this->getRequestData();
        $data = $this->model->activate_deactivate($rawData["id"],1);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }

    protected function deactivate(){
        $rawData = $this->getRequestData();
        $data = $this->model->activate_deactivate($rawData["id"],0);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }

    protected function sort(){
        $rawData = $this->getRequestData();
        $data = $rawData["widgets"];

        $res = $this->model->saveSort($data);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }
}