<?php

class articleView
{
    protected $data;
    protected $rest;

    public function __construct()
    {
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