<?php

/**
 *
 * @author dajana
 *
 */
abstract class page_class
{

    public $page = '';
    public $page_settings = array();
    protected $table = '';
    /**
     * @var sCMS_engine
     */
    protected $engine;
    private $id = 0;
    private $name = '';
    private $created = '';
    private $creator = 0;
    private $modifier = 0;
        private $modified = ''; // Unique page ID
private $page_id = 0;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
    }

    /**
     * @name get page id
     * @package sCMS ver 2.0
     * @return int
     *
     */
    public function get_page_id()
    {
        return $this->page_id;
    }

    /**
     * @name Sets page_id
     * @param int $id
     * @package sCMS ver 2.0
     */
    public function set_page_id($id)
    {
        $this->page_id = (int)$id;
    }

    public function getFullSettings()
    {
        global $engine;
        if ($this->id > 0) {
            # Read widget info
            $options_db = $this->get_array(false, 'options', 'WHERE id=' . $id);
            # setup settings array
            $settings_dbase = array();
            if (count($options_db[0]['options']) > 0) {
                $settings_dbase = json_decode($options_db[0]['options']);
            }
        }
        # Read predefined settings(XML)
        $xmlSettings_standard = $engine->path . 'pages/' . $this->page . '/settings/settings.xml';
        $xmlSettings_from_template = $engine->path . "templates/" . $engine->settings->general->template . '/pages/' . $this->page . '/settings/settings.xml';
        if (file_exists($xmlSettings_from_template)) {
            $xmlSettingsFile = $xmlSettings_from_template;
        } else {
            $xmlSettingsFile = $xmlSettings_standard;
        }
        $xml_settings = array();
        if (file_exists($xmlSettingsFile)) {
            $xml = simplexml_load_file($xmlSettingsFile);
            $xml_settings = $xml->children();
        }
        $this->settings = $xml_settings;
    }

    /**
     *
     * @param boolean $addopt
     * @param string $what_to_get
     * @param string $filter_params
     * @param mixed $order_by
     * @param string $order_direction
     * @param string $table
     * @return array
     * @global $engine
     */
    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id',
                              $order_direction = 'ASC', $table = '')
    {
        global $engine;
        // Check if we are using default table
        if ($table == '') {
            $table = $this->table;
        }
        // Check if we have array in $order_by
        if (is_array($order_by)) {
            $order_by = implode(", ", $order_by);
            // empty order direction
            $order_direction = '';
        }
        // Set QUERY
        $query = "SELECT " . $what_to_get . "
                  FROM " . $table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        // Do Query
        $engine->dbase->query($query, $addopt, true);
        // Return rows
        return $engine->dbase->rows;
    }

    /**
     *
     * @param array $new_settings
     */
    public function override_loaded_settings($new_settings = array())
    {
        if (count($new_settings) > 0) {
            foreach ($new_settings as $key => $val) {
                $set = explode(":", $val);
                # add default settings to array
                $value = '';
                if (isset($set[1])) {
                    $value = $set[1];
                }
                $this->settings['written'][$i]['name'] = $set[0];
                $this->settings['written'][$i]['value'] = $value;
                $i++;
            }
        }
        // Set quick readable setting
        foreach ($this->settings['default'] as $key_s => $val_s) {
            $value = '';
            # check value
            if (isset($this->settings['written'])) {
                foreach ($this->settings['written'] as $key_w => $val_w) {
                    if ($val_w['name'] == $val_s['fieldName']) {
                        $value = $val_w['value'];
                        break;
                    }
                }
            }
            if ($value == '') {
                if ($val_s['default_value'] !== null) {
                    $value = $val_s['default_value'];
                }
            }
            $this->settings['readable'][$val_s['fieldName']] = $value;
        }
    }

    /**
     * @param mixed $id
     * @name Deactivate util
     *
     */
    public function activate_deactivate($id = NULL, $active = 0)
    {
        global $engine;
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=" . $active . " WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get settings
     * @param int $id
     */
    public function get_settings($id = 0)
    {
        global $engine;
        if ($id > 0) {
            # Read widget info
            $options_db = $this->get_array(false, 'options', 'WHERE id=' . $id);
            # setup settings array
            $settings_dbase = array();
            if (count($options_db[0]['options']) > 0) {
                $settings_dbase = json_decode($options_db[0]['options']);
            }
        }

        # Read predefined settings(XML)
        $xmlSettings_standard = $engine->path . 'pages/' . $this->page . '/settings/settings.xml';
        $xmlSettings_from_template = $engine->path . "templates/" . $engine->settings->general->template . '/pages/' . $this->page . '/settings/settings.xml';
        if (file_exists($xmlSettings_from_template)) {
            $xmlSettingsFile = $xmlSettings_from_template;
        } else {
            $xmlSettingsFile = $xmlSettings_standard;
        }
        $xml_settings = array();
        if (file_exists($xmlSettingsFile)) {
            $xml = simplexml_load_file($xmlSettingsFile);
            $xml_settings = $xml->children();
        }
        # create array of settings
        $this->settings = array();
        $i = 0;
        if (isset($xml_settings->options)) {
            $options = (array)$xml_settings->options;
            //print_r($options);
            foreach ($options['option'] as $key => $val) {
                $ar = get_object_vars($val);
                foreach ($ar as $key_o => $val_o) {
                    $this->settings['default'][$key][$key_o] = $val_o;
                    if ($key_o == 'type' && $val_o == 'drop') {
                        $this->settings['default'][$i]['drop_options'] = (array)$val['fieldOptions'];
                    }
                }
                $default_value = '';
                if ($val['type'] == 'drop') {
                    $this->settings['default'][$i]['drop_options'] = (array)$val['fieldOptions'];
                }
                if ($val['defaultValue'] !== 'null') {
                    $default_value = $val['defaultValue'];
                }
                $i++;
            }
            # settings
            if (isset($settings_dbase)) {
                foreach ($settings_dbase as $key => $val) {
                    $this->settings['written'][$key] = $val;
                }
            }
            // Set quick readable setting
            $default = array();
            if (isset($this->settings['default'])) {
                $default = $this->settings['default'];
            }
            foreach ($default as $key_s => $val_s) {
                $value = '';
                # check value
                if (isset($this->settings['written'])) {
                    foreach ($this->settings['written'] as $key_w => $val_w) {
                        if ($key_w == $val_s['fieldName']) {
                            $value = $val_w;
                            break;
                        }
                    }
                }
                if ($value == '') {
                    if (@$val_s['default_value'] !== null) {
                        $value = $val_s['default_value'];
                    }
                }
                $this->settings['readable'][$val_s['fieldName']] = $value;
            }
        }
    }

    /**
     * @param int $id
     * Write special settings
     *
     */
    public function write_settings($id = 0)
    {
        global $engine;
        $xmlSettings_standard = $engine->path . 'pages/' . $this->page . '/settings/settings.xml';
        $xmlSettings_from_template = $engine->path . "templates/" . $engine->settings->general->template . '/pages/' . $this->page . '/settings/settings.xml';

        if (file_exists($xmlSettings_from_template)) {
            $xmlSettingsFile = $xmlSettings_from_template;
        } else {
            $xmlSettingsFile = $xmlSettings_standard;
        }

        # Read predefined settings(XML)
        $xml = simplexml_load_file($xmlSettingsFile);
        $xml_settings = $xml->children();
        $options = (array)$xml_settings->options;
        # Get name of fields
        $requested = array();
        foreach ($options['option'] as $key => $val) {
            $val = (array)$val;
            # add default settings to array
            if (isset($val['fieldName'])) {
                $requested[] = $val['fieldName'];
            }
        }
        # Request
        $vals = $engine->security->get_vals($requested);
        # SETUP update query
        foreach ($requested as $key => $val) {
            $query_part[$val] = $engine->security->get_val($vals[$val]);
        }
        $options = '';
        if (isset($query_part)) {
            $options = json_encode($query_part);
        }
        $query = "UPDATE " . $this->table . " SET options='" . $options . "' WHERE id=" . $id . " LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    protected function loadLanguage()
    {
        global $engine;
        // INCLUDE LANGUAGE PACK
        include_once $engine->path . 'language/pages/' . $this->page . '/' .
            $this->page . '_' . $engine->get_system_lang() . '.php';
        $lang_class = $this->page . "_language";
        $this->lang = new $lang_class();
    }
}

?>
