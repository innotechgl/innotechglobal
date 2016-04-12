<?php
class importantPhonesObject extends mainObject
{

    protected $Title;
    protected $Content;
    protected $Link;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTitle(){
        return $this->Title;
    }

    public function setTitle($title){
        $this->Title = $title;
    }

    public function getContent(){
        return $this->Content;
    }

    public function setContent($Content){
        $this->Content = $Content;
    }

    public function getLink(){
        return $this->Link;
    }

    public function setLink($Link){
        $this->Link = $Link;
    }
}