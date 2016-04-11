<?php

class articleCategoriesView
{
    protected $data;
    protected $rest;
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
        $this->rest = new rest_class();
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function show()
    {
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            json_encode($this->data));
    }
}