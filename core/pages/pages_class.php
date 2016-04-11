<?php

class pages
{

    public $id = 0;
    public $name = '';
    public $active = 0;
    public $visible = 0;
    public $options = 0;
    public $pages = array();
    private $table = "pages";
    private $page = "pages";

    public function __construct()
    {
        $pages = $this->get_array(false, 'id, real_name', 'WHERE active=1', 'name', 'asc');
        foreach ($pages as $key => $val) {
            $this->pages[$val['id']] = $val['real_name'];
        }
    }

    /**
     * @name  Get array
     *
     */
    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . "
                  FROM " . $this->table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     * Load multiple pages
     * @version 1.0
     * @example page_class::load_pages("articles","gallery",...);
     */
    public function load_pages()
    {
        // Get args
        $arg_list = func_get_args();
        // Go through pages
        foreach ($arg_list as $key => $val) {
            // Load page
            $this->load_page($val);
        }
    }

    /**
     * @param string $name
     */
    public function load_page($name)
    {
        global $engine;
        $engine->log->add_success_log('pages', 'Ucitavamo: ' . $name);
        if (!class_exists($name . "_class")) {
            $engine->log->add_success_log('pages', 'Klasa ne postoji u memoriji, pokusavam da kreiram > ' . $name . "_class");
            if (in_array($name, $this->pages)) {
                $engine->log->add_success_log('pages', 'Klasa je validna (nalazi se u nizu stranica) > ' . $name . "_class");
                // File to load
                $file_to_load = $engine->path . 'pages/' . $name . '/' . $name . '_class.php';
                if (!file_exists($file_to_load)) {
                    $engine->log->add_error_log('pages', "Tried to load page: " . $name . " on adress(" . $engine->path . 'pages/' . $name . '/' . $name . '_class.php' . "), File doesn't exist.");
                    $engine->log->write("0", "engine", "Tried to load page: " . $name . " on adress(" . $engine->path . 'pages/' . $name . '/' . $name . '_class.php' . "), File doesn't exist.", 1);
                    exit();
                } else {
                    $engine->log->add_success_log('pages', 'Fajl sa nazivom > ' . $file_to_load . " postoji");
                }
                // Load Requested page
                if (!isset($this->$name)) {
                    $engine->log->add_success_log('pages', "Varijabla nije zauzeta...");
                    include_once $engine->path . 'pages/' . $name . '/' . $name . '_class.php';
                    $class = $name . "_class";
                    $page_id = array_search($name, $this->pages);
                    $engine->$name = new $class();
                    $engine->$name->set_page_id($page_id);
                    $engine->$name->settings = $this->get_settings($page_id);
                }
            } else {
                $engine->log->write("0", "engine", "Tried to load page: " . $name . ", page's not registered.", 1);
                $engine->log->add_error_log('pages', "Tried to load page: " . $name . ", page's not registered.");
                //echo $engine->log->display_error_log('pages')
            }
        } else {
            //echo 'Klasa vec postoji';
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
            # Read page options
            $query = "SELECT name, options FROM " . $this->table . " WHERE id=" . $id;
            $engine->dbase->query($query);
            $options_db = $engine->dbase->rows[0]['options'];
            $this->name = $engine->dbase->rows[0]['name'];
            # setup settings array
            $settings_dbase = array();
            if (count($options_db) > 0) {
                $settings_dbase = explode(";", $options_db);
            }
        }
        # Read predefined settings(XML)
        $xml_settings = array();
        if (file_exists($engine->path . 'pages/' . $this->pages[$id] . '/settings/settings.xml')) {
            $xml = simplexml_load_file($engine->path . 'pages/' . $this->pages[$id] . '/settings/settings.xml');
            $xml_settings = $xml->children();
        }
        # create array of settings
        $settings = array();
        $i = 0;
        if (isset($xml_settings->options)) {
            $options = (array)$xml_settings->options;
            foreach ($options['option'] as $key => $val) {
                $val = (array)$val;
                # add default settings to array
                $settings['default'][$i]['name'] = $val['name'];
                $settings['default'][$i]['fieldName'] = $val['fieldName'];
                $settings['default'][$i]['type'] = $val['type'];
                if ($val['type'] == 'drop') {
                    $settings['default'][$i]['drop_options'] = (array)$val['fieldOptions'];
                }
                $default_value = '';
                if ($val['defaultValue'] !== 'null') {
                    $default_value = $val['defaultValue'];
                }
                $settings['default'][$i]['default_value'] = $default_value;
                $i++;
            }
            # settings
            $i = 0;
            if (isset($settings_dbase)) {
                foreach ($settings_dbase as $key => $val) {
                    $set = explode(":", $val);
                    # add default settings to array
                    $value = '';
                    if (isset($set[1])) {
                        $value = $set[1];
                    }
                    $settings['written'][$i]['name'] = $set[0];
                    $settings['written'][$i]['value'] = $value;
                    $i++;
                }
            }
            // Set quick readable setting
            foreach ($settings['default'] as $key_s => $val_s) {
                $value = '';
                # check value
                if (isset($settings['written'])) {
                    foreach ($settings['written'] as $key_w => $val_w) {
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
                $settings['readable'][$val_s['fieldName']] = $value;
            }
        }
        return $settings;
    }

    /**
     * @name Write special settings
     * @param int $id
     *
     *
     */
    public function write_page_settings($id = 0)
    {
        global $engine;
        # Read predefined settings(XML)
        $xml = simplexml_load_file($engine->path . '/pages/' . $this->page . '/settings/settings.xml');
        $xml_settings = $xml->children();
        $options = (array)$xml_settings->options;
        # Get name of fields
        $requested = array();
        foreach ($options['option'] as $key => $val) {
            $val = (array)$val;
            # add default settings to array
            $requested[] = $val['fieldName'];
        }
        # Request
        $vals = $engine->security->get_vals($requested);
        # SETUP update query
        foreach ($requested as $key => $val) {
            $query_part[] = $val . ':' . $engine->security->get_val($vals[$val]);
        }
        $query = "UPDATE " . $this->table . " SET options='" . implode(";", $query_part) . "' WHERE id=" . $id . " LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }
}

?>
