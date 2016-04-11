<?php

class articleView
{
    protected $data;
    protected $engine;
    protected $viewType;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;

        $this->viewType = 'view';
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setViewType($viewType){
        $this->viewType = $viewType;
    }

    public function view()
    {
        require_once _APP_DIR_ . "templates/" .
            $this->engine->settings->general->template
            . "/pages/articles/views/html/".$this->viewType.".php";
    }

    public function viewRecept(){
        require_once _APP_DIR_ . "templates/" .
            $this->engine->settings->general->template
            . "/pages/articles/views/html/view-recepti.php";
    }
}