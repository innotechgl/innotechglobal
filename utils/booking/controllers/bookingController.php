<?php

class bookingController extends controller
{

    /** @var rest_class
     */
    protected $rest;

    /**
     * @param bookingModel $model
     */
    public function __construct(bookingModel $model)
    {
        booking::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add($data)
    {
        return $this->model->add($data);
    }
}