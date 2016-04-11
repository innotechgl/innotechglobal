<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 */

class infoAroundHotelController extends controller
{

    /** @var rest_class
     */
    protected $rest;

    /**
     * @param infoAroundHotelModel $model
     */
    public function __construct(infoAroundHotelModel $model)
    {
        infoAroundHotel::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add($data)
    {
        return $this->model->add($data);
    }
}