<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/24/2016
 * Time: 1:22 PM
 */


class customerOrderFieldsController extends controller
{
    /**
     * @var rest_class
     */
    protected $rest;

    /**
     * @param customerOrdersModel $model
     */
    public function __construct(customerOrderFieldsModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    /**
     * Add new product
     */
    protected function add()
    {
        $data = $this->getRequestData();

        // We can fake data in here
        $result = $this->model->add($data);

        if($result){
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                $result);
        }
        else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_ERROR,
                rest_class::REST_STATUS_ERROR,
                $result);
        }
    }

    protected function update(){
        $data = $this->getRequestData();

        // We can fake data in here
        $result = $this->model->update($data);

        if($result){
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                $result);
        }
        else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_ERROR,
                rest_class::REST_STATUS_ERROR,
                $result);
        }
    }


    protected function delete(){
        $data = $this->getRequestData();

        // We can fake data in here
        $result = $this->model->delete($data["id"]);

        if($result){
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                $result);
        }
        else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_ERROR,
                rest_class::REST_STATUS_ERROR,
                $result);
        }
    }


}