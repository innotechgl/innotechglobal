<?php

class router
{

    protected $sefEnabled = true;
    protected $route;

    protected $page;
    protected $task;
    protected $view;
    protected $id;
    protected $menuId;

    public function __construct()
    {
    }

    public function getSefEnabled()
    {
        return $this->sefEnabled;
    }

    public function setSefEnabled($enabled = 0)
    {
        $this->sefEnabled = (int)$enabled;
    }

    public function getSefRoute()
    {
    }

    public function getRoute()
    {
    }
}

?>