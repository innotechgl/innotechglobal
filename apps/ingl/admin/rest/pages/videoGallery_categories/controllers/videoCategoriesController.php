<?php

class videoCategoriesController extends controller
{
    /**
     * @var rest_class
     */
    protected $rest;
    protected $view;

    /**
     * @param videoCategoriesModel $model
     */
    public function __construct(videoCategoriesModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
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

    protected function getOptions(){
        $options = $this->model->getOptions();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $options);
    }

    protected function load(){
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

        $data = $this->model->load($id);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getAllLanguages(){
        $langs = $this->engine->language->get_array(false);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $langs);
    }

    protected function add()
    {
        $rawData = $this->getRequestData();

        $data = $this->model->_add($rawData["data"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function update()
    {
        $rawData = $this->getRequestData();

        $data = $this->model->_update($rawData["data"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function delete()
    {

        $rawData = $this->getRequestData();

        $data = $this->model->_delete($rawData["data"]["id"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($data));
    }
}