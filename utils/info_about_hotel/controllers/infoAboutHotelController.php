<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 */

class infoAboutHotelController extends controller
{

    /** @var rest_class
     */
    protected $rest;

    /**
     * @param infoAboutHotelModel $model
     */
    public function __construct(infoAboutHotelModel $model)
    {
        infoAboutHotel::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add($data)
    {
        return $this->model->add($data);
    }
}