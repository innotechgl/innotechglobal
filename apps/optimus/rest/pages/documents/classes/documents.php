<?php

class documents extends mainObject
{
    /**
     * @var String
     */
    protected $title;
    /**
     * @var String
     */
    protected $fileName;

    /**
     * @return String
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle($title){
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getFileName(){
        return $this->fileName;
    }

    /**
     * @param String $fileName
     */
    public function setFileName($fileName){
        $this->fileName = $fileName;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["title"] = $this->title;
        $array["file_name"] = $this->fileName;

        return $array;
    }

    public function fillMe($vals){

        parent::fillMe($vals);

        $this->setTitle($vals["title"]);
        $this->setFileName($vals["file_name"]);
    }


}