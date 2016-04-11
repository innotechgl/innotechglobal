<?php

class documentsObject extends mainObject
{

    protected $Title;
    protected $Filename;

    public function __construct()
    {
        parent::__construct();
    }

    public function setTitle($Title){
        $this->Title = $Title;
    }

    public function getTitle(){
        return $this->Title;
    }

    public function setFilename($Filename){
        $this->Filename = $Filename;
    }

    public function getFilename(){
        return $this->Filename;
    }
}