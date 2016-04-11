<?php

/**
 * EVENTS
 */
class events implements interface_events
{
    // Events
    const ON_ADD = "ON_ADD";
    const ON_SAVE = "ON_SAVE";
    const ON_DELETE = "ON_DELETE";
    const ON_UPDATE = "ON_UPDATE";
    const ON_BEFORE_LOAD = "ON_BEFORE_LOAD";
    const ON_BEFORE_RENDER = "ON_BEFORE_RENDER";
    const ON_AFTER_RENDER = "ON_AFTER_RENDER";
    const ON_LIST = "ON_LIST";
    const ON_SHOW_FORM = "ON_SHOW_FORM";
    const ON_VIEW = "ON_VIEW";
    const ON_ACTIVATE = "ON_ACTIVATE";
    const ON_DEACTIVATE = "ON_DEACTIVATE";

    // Types of event
    const TYPE_STANDARD = "";
    const TYPE_AJAX = "ajax";
    const TYPE_ADMIN = "admin";
    private static $event_loaded = "LOADED";
    private static $event_error = "ERROR";
    public $name = '';
    public $event = '';
    public $type = '';
    public $active = 0;
    public $events = array();
    private $table = 'events';
    private $added_events = '';
    private $data = array();

    /**
     *
     * Dispatches event
     * @param string $event_type
     * @param array $data
     */
    public function dispatchEvent($event_type = "", $page = '', $data = array())
    {
        /*
        // Load engine
        global $engine;
        

        // Load all files with this event
        foreach ($engine->pages->pages as $key_p => $val_p) {
            // set file to include
            $filename = "pages/" . $val_p . "/events/events.php";
           

            // Check existance
            if (file_exists($filename)) {
                // include file
                include_once $filename;
            }
        }
         * */
    }

    /**
     * @param name data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Fire event
     * @param string $page
     * @param string $eventType
     * return bool
     */
    public function fireEvent($page, $eventType, $data)
    {
        global $engine;
        //echo 'fire event';
        // Go through registered events
        foreach ($this->events as $key_e => $val_e) {
            if ($val_e->event_type == $eventType
                && $val_e->fire_type == $engine->type
                && in_array($page, $val_e->fire_in_pages)
            ) {
                // Define data
                $val_e->data = $data;
                // define page
                $val_e->fired_at = $page;
                // Fire it
                $val_e->fire();
            }
        }
    }

    /**
     * @param array $array
     * @return string
     */
    public function defineEventPage(array $array)
    {
        return implode(".", $array);
    }

    /**
     * LoadEvents
     * @param string $type
     * @param string $name
     *
     */
    public function loadEvents($type, $names)
    {
        global $engine;
        //
        foreach ($names as $key => $val) {
            // Define dir
            $dir = $engine->path . $type . '/' . $val . '/events';
            // Open dir if exists
            if (file_exists($dir)) {
                // Open dir for reading
                $handle = opendir($dir);
                // go through dir
                while (false !== ($file = readdir($handle))) {
                    if ($file !== "." && $file !== '..' && $file !== '.svn' && $file !== '_notes') {
                        // Define filepath
                        $filename = $dir . '/' . $file;
                        // Set variables for template defined events
                        $dirTemplate = $engine->path . $type . 'templates/' .
                            $engine->settings->general->template . '/pages/' .
                            $val . '/events';
                        $templateFileName = $dirTemplate . '/' . $file;
                        // Check existance of file
                        if (file_exists($filename)) {
                            // Include file
                            include_once $filename;
                        }
                        // Check existance of file	
                        if (file_exists($templateFileName)) {
                            // Include file
                            include_once $templateFileName;
                        }
                    }
                }
                // Close dir
                closedir($handle);
            }
        }
    }

    /**
     * Add event
     */
    public function addEvent($event)
    {
        $this->events[] = $event;
    }

    public function __destruct()
    {
        //print_r($this->events);
        // Destroy data
        $this->data = array();
        $this->events = array();
    }
}

?>