<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 */

class productModelsController extends controller
{

    /** @var rest_class
     */
    protected $rest;

    /**
     * @param productModelsModel $model
     */
    public function __construct(productModelsModel $model)
    {
        productsModel::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add($data)
    {
        return $this->model->add($data);
    }

    protected function addProductToGroup()
    {

    }
}