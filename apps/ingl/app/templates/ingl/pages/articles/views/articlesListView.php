<?php

class articlesListView
{
    protected $data;
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function view($view = "list")
    {

        require_once _APP_DIR_ . "templates/" .
            $this->engine->settings->general->template
            . "/pages/articles/views/html/".$view.".php";
    }

}