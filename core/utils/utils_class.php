<?php

/**
 *
 * Utils manipulation class
 * @author dajana
 * @uses page_class
 *
 */
class utils extends page_class
{

    const   by_name = "by_name";
    const   by_real_name = "by_real_name";
    const   by_id = "by_id";
    public $id = 0;
    public $table = 'utils';
    public $page = 'utils';
    protected $name;
    private $real_name = '';
    private $settings = array();

    public function __construct()
    {
        // Set db table
        $this->table = 'utils';
        // set page name
        $this->page = 'utils';
    }

    public function set_real_name($name)
    {
        $this->real_name = $name;
    }

    /**
     * @name  Get array of widgets
     *
     */
    public function get_utils($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . " FROM " . $this->table . " " . $filter_params . " ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     * Add input to db
     * @see page::add()
     */
    public function add($name = '', $real_name = '', $settings = '')
    {
        global $engine;
        $this->name = $engine->security->get_val($name);
        $this->real_name = $engine->security->get_val($real_name);
        $this->settings = $engine->security->get_val($settings);
        // Set query
        $query = "INSERT INTO " . $this->table . " (name, real_name, settings, creator, created) VALUES ('" . $this->name . "','" . $this->real_name . "','" . $this->settings . "','" . $engine->users->get_id() . "',NOW())";
        // Insert and get id
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Copy util
     * @param int $id
     * @return bool
     */
    public function copy($id)
    {
        global $engine;
        $util = $this->get_array(false, '*', 'WHERE id=' . $id);
        $query = "INSERT INTO $this->table (name, native_name, options, position, active, type, created, creator)
                VALUES ('" . $util[0]['name'] . "_copy','" . $util[0]['native_name'] . "','" . $util[0]['options'] . "', '" . $util[0]['position'] . "', 0, '" . $util[0]['type'] . "', NOW(),'" . $engine->users->get_id() . "');";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Auto Load all utils according to event
     * @param string $event
     *
     */
    public function auto_load_utils($event = 'DEFAULT')
    {
        switch ($event) {
            // after loading page
            case events::after_load:
                break;
            // after printing page
            case events::after_print:
                break;
            // before loading page
            case events::before_load:
                break;
            default:
                break;
        }
    }

    /**
     *
     * Load specific util
     * @param mixed $val
     * @param string $type
     */
    public function load_utils($val, $type = 'by_name')
    {
        global $engine;
        switch ($type) {
            case self::by_name:
                $query_search = ' name LIKE "' . $engine->security->get_val($val) . '" ';
                break;
            case self::by_real_name:
                $query_search = ' real_name LIKE "' . $engine->security->get_val($val) . '" ';
                break;
            case self::by_id:
                $query_search = ' id=' . $engine->security->get_val($val);
                break;
        }
        // Load utils
        $utils = $this->get_array(false, '*', 'WHERE ' . $query_search . ' AND active=1');
        foreach ($utils as $key_u => $val_u) {
            // Load util
            $engine->load_util($val_u['real_name']);
            // set name of util
            $name = 'util_' . $val_u['real_name'];
            //echo $name;
            // Set util params
            $engine->{$name}->set_util_id($val_u['id']);
            $engine->{$name}->set_real_name($val_u['real_name']);
            $engine->{$name}->set_configuration($this->load_config($val_u['id']));
            // Show utils
            $engine->{$name}->show_util();
        }
    }

    /**
     * Load config for util
     * @param int $id
     */
    private function load_config($id = 0)
    {
        global $engine;
        # Read util info
        $util = $this->get_array(false, 'real_name, options', 'WHERE id=' . $id);
        $this->real_name = $util[0]['real_name'];
        # setup settings array
        $settings_dbase = array();
        $settings_dbase = explode(";", $util[0]['options']);
        # check for file existance
        if (!file_exists($engine->path . 'utils/' . $this->real_name . '/settings/settings.xml')) {
            return false;
        }
        # Read predefined settings(XML)
        $xml = simplexml_load_file($engine->path . 'utils/' . $this->real_name . '/settings/settings.xml');
        $xml_settings = $xml->children();
        # create array of settings
        $this->settings = array();
        $i = 0;
        $options = (array)$xml_settings->options;
        foreach ($options['option'] as $key => $val) {
            # create array 
            $val = (array)$val;
            # add default settings to array
            # Go through all options
            foreach ($val as $key_i => $val_i) {
                $default_value = '';
                if ($key_i == 'defaultValue') {
                    if ($val_i !== 'null') {
                        $default_value = $val_i;
                    }
                    $this->settings['default'][$i]['default_value'] = $default_value;
                } else {
                    $this->settings['default'][$i][$key_i] = $val_i;
                }
            }
            $i++;
        }
        # settings
        $i = 0;
        foreach ($settings_dbase as $key => $val) {
            $set = explode(":", $val);
            # add default settings to array
            $value = '';
            if (isset ($set[1])) {
                $value = str_replace(array(">>>", "|||"), array(";", ":"), $set[1]);
            }
            $this->settings['written'][$i]['name'] = $set[0];
            $this->settings['written'][$i]['value'] = $value;
            $i++;
        }
        return $this->settings;
    }

    public function delete()
    {
    }
}

?>