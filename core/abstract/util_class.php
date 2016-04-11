<?php

abstract class util_class extends page_class
{
    public $real_name = '';
    public $id = 0;
    public $configuration = array();
    public $table = '';
    protected $util_id = 0;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * Set util configuration
     *
     */
    public function set_configuration($config)
    {
        $this->configuration = $config;
    }

    /**
     *
     * Get util ID
     * @return int
     */
    public function get_util_id()
    {
        return $this->util_id;
    }

    /**
     *
     * Set id of util
     * @param int $id
     *
     */
    public function set_util_id($id)
    {
        $this->util_id = $id;
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
    public function show_util()
    {
        global $engine;
        // Include file
        $filename = $engine->path . 'utils/' . $this->real_name . '/index.php';
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
     * Load js
     */
    public function load_js($string)
    {
        global $engine;
        $template_file = $engine->path . $engine->settings->general->template . "/utils/" . $this->real_name . "/js/" . $string;
        $standard_file = $engine->path . "utils/" . $this->real_name . "/js/" . $string;
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
        $template_file = $engine->path . $engine->settings->general->template . "/utils/" . $this->real_name . "/html/" . $string;
        $standard_file = $engine->path . "utils/" . $this->real_name . "/html/" . $string;
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            include $standard_file;
        }
    }
}

?>