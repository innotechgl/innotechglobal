<?php

class controllerLang extends controller {
    /**
     * @var modelLang
     */
    protected $model;

    public function __construct(modelLang $model){
        parent::__construct($model);

        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function getAll(){

        $data = $this->model->getAll();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }
}