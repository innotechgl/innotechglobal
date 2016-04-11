<?php

/**
 * @name     WIDGET CLASS
 * @author   Dajana Nestorovic
 * @package  sCMS ver. 3
 * @category Widget
 *
 */
abstract class widget_class
{

    public $data = array();
    protected $id = 0;
    protected $real_name = '';
    protected $name = '';
    protected $settings = array();
    protected $position = '';
    protected $view = '';

    /**
     * @var sCMS_engine
     */
    protected $engine;

    /**
     *
     * Create widget
     */
    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
    }

    public function get_view()
    {
        return $this->view;
    }

    /**
     * Set widget view
     * @param string $view
     */
    public function set_view($view)
    {
        global $engine;
        $this->view = $engine->security->get_val($view,
            sCMS_security::VAL_STRING);
    }

    /**
     * Set position of widget
     * @name set_position
     * @example $widget->set_position('left');
     * @param string $position
     */
    public function set_position($position)
    {
        $this->position = $position;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    // Get runtime variables

    /**
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     *
     * Set util configuration
     *
     */
    public function set_settings($settings)
    {
        $this->settings = $settings;
    }

    /**
     *
     * Set id of util
     * @param int $id
     *
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_real_name()
    {
        return $this->real_name;
    }

    /**
     *
     * Set real name of util
     * @param string $name
     *
     */
    public function set_real_name($name = '')
    {
        $this->real_name = $name;
    }

    /**
     *
     * Load index.php of util and show it!
     *
     */
    public function show()
    {
        global $engine;
        // Include file
        $filename = _APP_DIR_ . 'widgets/' . $this->real_name . '/index.php';
        // Check existance of file
        if (file_exists($filename)) {
            // include it
            include $filename;
        } else {
            // Write log
            $engine->log->write($engine->users->get_name(), 'ERROR', 'File doesn\'t exists! ' . $filename, 0);
        }
    }

    /**
     *
     * @global object $engine
     * @param string $js_file
     */
    public function load_js($js_file)
    {
        global $engine;
        $template_file = $engine->path . $engine->settings->general->template . "/widgets/" . $this->real_name . "/js/" . $js_file;
        $standard_file = _APP_DIR_ . "widgets/" . $this->real_name . "/js/" . $js_file;
        if (file_exists($template_file)) {
            $file = $template_file;
        } else {
            $file = $standard_file;
        }
        echo "<script type=\"text/javascript\" src=\"/" . $file . "\"></script>";
    }

    /**
     * @param string $string
     * @param array $pass_vars
     *
     */
    public function load_html($string, $pass_vars = array())
    {
        // Global
        global $engine;
        foreach ($pass_vars as $key => $var) {
            ${$key} = $var;
        }
        $template_file = $engine->path . $engine->settings->general->template . "/widgets/" . $this->real_name . "/html/" . $string;
        $standard_file = _APP_DIR_. "widgets/" . $this->real_name . "/html/" . $string;
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            include $standard_file;
        }
    }

    /**
     * Place widget on his position
     * @name place_me
     * @
     */
    public function place_me()
    {
        global $engine;
        // set file
        switch ($engine->type) {
            case "standard":
                $add = _ROOT_;
                break;
            case "mobile":
                $add = 'mobile/';
                break;
            case "admin":
                $add = _APP_ADMIN_DIR_;
                break;
        }
        $file = _APP_DIR_ . 'widgets/' . $this->real_name . '/html/' . $this->view . '.php';
        // Show widget if we have file and view
        if ($this->view !== NULL && file_exists($file)) {
            include $file;
            //echo ' UKLJUCEN JE: '.$file;
        }
    }

    /**
     * Cache this widget
     */
    protected function cacheMe()
    {
    }
}

?>