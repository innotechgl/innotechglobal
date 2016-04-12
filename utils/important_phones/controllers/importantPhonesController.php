<?php

class importantPhonesController extends controller
{

    /** @var rest_class
     */
    protected $rest;

    /**
     * @param importantPhonesModel $model
     */
    public function __construct(importantPhonesModel $model)
    {
        importantPhones::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add($data)
    {
        return $this->model->add($data);
    }
}