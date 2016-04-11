<?php

Class filter_class
{

    private $vars = array();
    private $spec_params = array();
    private $predefined = array();

    private $name = 'default';
    private $type = 'default';

    /**
     * @param string $name
     * @param string $type
     *
     **/
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Set default vars
     * @param array $vars
     *
     */
    public function set_default_vars($vars = array())
    {
        global $engine;
        // Set default
        $this->predefined = $vars;
        // Read cookie
        $cookie_vars = $engine->read_cookie_property($this->name, $this->type);
        if (count($cookie_vars) > 0 && is_array($cookie_vars)) {
            foreach ($cookie_vars as $key => $val) {
                $this->predefined[$key] = $val;
            }
        }
    }

    /**
     * Get variables
     */
    public function get_vars()
    {
        $vars = array();
        foreach ($this->predefined as $key => $val) {
            if (!key_exists($key, $this->vars)) {
                $this->vars[$key] = $val;
            }
        }
        return $this->vars;
    }

    /**
     * Set vars
     * @param array $vars
     */
    public function set_vars($vars = array())
    {
        global $engine;
        $vals = $engine->security->get_vals($vars);
        foreach ($vals as $key => $val) {
            $this->vars[$key] = $val;
        }
    }
}

?>