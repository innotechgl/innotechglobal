<?php

class activator_class extends util_class
{

    public $ajax_link = '';
    public $id = '';
    public $active = '';
    public $id_to_listen = '';

    public function __construct()
    {
    }

    /**
     * @param string $link
     * @param string $id
     * @param string $active
     *
     * @name Add new activator
     */
    public function add_new_activator()
    {
        global $engine;
        include $engine->path . 'utils/activator/html/activator.php';
    }

    public function activator_set_listener($id_to_listen)
    {
        $this->id_to_listen = $id_to_listen;
        global $engine;
        include $engine->path . 'utils/activator/html/set_listener.php';
    }

    public function __destruct()
    {
    }
}

?>