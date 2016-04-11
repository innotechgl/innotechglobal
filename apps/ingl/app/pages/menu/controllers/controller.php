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

    protected function load(){

        $time = filter_input(INPUT_GET, "time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($time==0){
            $time = "1970-01-01";
        }

        $sinceDate = new DateTime($time);

        $menus = $this->model->getAll($sinceDate);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $menus);

    }

    protected function loadDeleted(){
        $time = filter_input(INPUT_GET, "time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($time==0){
            $time = "1970-01-01";
        }

        $sinceDate = new DateTime($time);

        $data = $this->model->loadDeleted($sinceDate);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }
}