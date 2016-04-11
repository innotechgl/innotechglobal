<?php

abstract class event_class
{
    private $id = 0;
    private $name = '';
    private $event_type = '';
    private $fire_in_pages = array();
    private $fire_type = '';
    private $data = array();

    private $fired_at = '';

    public function __construct()
    {
    }

    /**
     * Fire event
     *
     */
    abstract public function fire();

    public function __destruct()
    {
        $this->data = array();
    }
}

?>