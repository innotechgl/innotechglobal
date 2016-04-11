<?php

/**
 * Created by PhpStorm.
 * User: dajana
 * Date: 5.6.15
 * Time: 11:51
 */
class bed_controller extends controller
{
    protected $model;
    protected $rest;

    public function __construct(accommodation_class $model)
    {
        parent::__construct($model);
        $this->rest = new rest_class();
    }
}