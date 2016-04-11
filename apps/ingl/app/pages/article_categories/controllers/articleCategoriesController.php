<?php

class articleCategoriesController extends controller
{
    /**
     * @var rest_class
     */
    protected $rest;
    protected $view;

    /**
     * @param articleCategoriesModel $model
     */
    public function __construct(articleCategoriesModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function load(){

        $time = filter_input(INPUT_GET, "time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($time==0){
            $time = "1970-01-01";
        }

        $sinceDate = new DateTime($time);

        $data = $this->model->getAll($sinceDate);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }
}