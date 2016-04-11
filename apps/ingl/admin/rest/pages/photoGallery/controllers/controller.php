<?php

class controllerPhotoGallery extends controller {

    public function __construct(modelPhotoGallery $model){

        parent::__construct($model);

        $this->resolve();
    }

    protected function getAll(){

    }

}