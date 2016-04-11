<?php

class productGroupController{
    /**
     * @var rest_class
     */
    protected $rest;

    /**
     * @param productGroupsModel $model
     */
    public function __construct(productGroupsModel $model)
    {
        productGroupsModel::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

        protected function add($data){
        return $this-> model-> add($data);
    }

    protected function addProductToGroup(){

    }
}