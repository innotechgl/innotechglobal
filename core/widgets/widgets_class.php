<?php

/**
 * Class WIDGETS
 */
class widgets
{

    public $id = 0;
    public $name = '';
    public $real_name = '';
    public $position = '';
    public $no = '';
    public $active = '';
    public $type = '';
    public $settings = array();
    public $created = '0000-00-00 00:00:00';
    public $creator = 0;
    public $modified = '0000-00-00 00:00:00';
    public $modifier = 0;
    public $table = 'widgets';
    public $table_available_widgets = 'widgets_installed';
    public $page = 'widgets';
    public $lang;
    public $data = array();
    public $widgets_list = array();

    protected $positions;
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
        // INCLUDE LANGUAGE PACK
        include_once $this->engine->path . 'language/core/' . $this->page . '/'
            . $this->page . '_lat.php';
        $this->lang = new widgets_language();
        $this->get_predefined_positions();
        //var_dump($this->positions);
    }

    // Get runtime variables

    /**
     *
     * @global object $engine
     * @param string $type
     */
    public function get_predefined_positions($type = 'standard')
    {
        $getFrom = array();

        switch ($type) {
            case "mobile":
                $getFrom = $this->engine->settings->mobilePositions->position;
                $template = $this->engine->settings->general->mobileTemplate;
                break;
            case "admin":
                $getFrom = $this->engine->settings->adminPositions->position;
                $template = $this->engine->settings->general->adminTemplate;
                break;
            default:
            case 'standard':
                if (isset($this->engine->settings->positions)) {
                    $getFrom = $this->engine->settings->positions->position;
                }
                $template = $this->engine->settings->general->template;
                break;
        }

        $template_custom_settings_files = _APP_DIR_ . "templates/" .
            $template . "/settings/settings.xml";

        if (file_exists($template_custom_settings_files)) {
            $xml = simplexml_load_file($template_custom_settings_files);

            $i = 0;
            foreach ($xml->positions->position as $tagName => $position) {
                $this->positions['positions'][$i]['position'] = (string)$position[0];
                $this->positions['positions'][$i]['name'] = (string)$position->attributes()->name;
                $this->positions['positions'][$i]['description'] = (string)$position->attributes()->description;
                $this->positions['positions'][$i]['available_in'] = explode(",", (string)$position->attributes()->available_in);
                $i++;
            }
        } else {
            foreach ($getFrom as $key => $val) {
                $this->positions['positions'][] = $val;
            }
        }
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
     * @return array
     */
    public function get_positions()
    {
        return $this->positions;
    }

    public function sort($position = '', $ids = array())
    {
        global $engine;
        $this->position = $position;
        $this->id = $ids;
        foreach ($ids['widget'] as $key => $val) {
            $query_array[] = "UPDATE " . $this->table .
                " SET no=$key WHERE id=$val LIMIT 1;";
        }
        foreach ($query_array as $key => $val) {
            echo $val . "<br />";
            if (!$engine->dbase->insertQuery($val)) {
                exit();
                return false;
            }
        }
        return true;
    }

    public function delete($id = 0)
    {
        if ($id <= 0) {
            return false;
            exit();
        }
        $this->id = $id;
        $query = "DELETE FROM " . $this->table . " WHERE id=" . $this->id
            . " LIMIT 1;";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function link_widgets($widget_id, $menus)
    {
        global $engine;
        // Set variables
        $widget_id = (int)$widget_id;
        $prequery = "DELETE FROM rel_widgets_menus WHERE widget_id="
            . $widget_id;
        if (count($menus) <= 0) {
            $prequery = "DELETE FROM rel_widgets_menus WHERE widget_id="
                . $widget_id;
            $prechecker = $engine->dbase->insertQuery($prequery);
            return true;
        }
        $prechecker = $engine->dbase->insertQuery($prequery);
        foreach ($menus as $value) {
            $query = "INSERT INTO rel_widgets_menus
            (widget_id, menu_id) VALUES
            ('" . $widget_id . "', '" . $value . "');";
            $checker = $engine->dbase->insertQuery($query);
            $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
        }
        if ($checker > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Place Widgets
     * @global object $engine
     * @param string $html
     * @param string $type
     * @return string
     */
    public function place_widgets($html = '', $type = 'standard')
    {
        global $engine;
        // Create array of replaced
        $replaced = array();
        // Get all widget places in HTML
        preg_match_all('|[<]+[scms]+[:]+[widget]+[:]+(.*)[>]|U',
            $html, $result, PREG_PATTERN_ORDER);
        // Prepare db query
        $query_search = array();
        foreach ($result[1] as $key => $val) {
            $query_search[] = " position LIKE '"
                . $engine->security->get_val($val) . "' ";
        }
        // if We are searching by positions
        if (count($query_search) > 0) {
            // Add query search as string
            $query_search = "" . implode(" OR ", $query_search);
            // Preparing query
            $query = "SELECT * FROM widgets WHERE (" . $query_search .
                ") AND active=1 AND type LIKE '" . $type .
                "' ORDER BY position,no ASC;";
            // Doing query
            $engine->dbase->query($query);
            // Getting rows of widgets
            $widget_rows = $engine->dbase->rows;
            // Preparing variables
            $widgets = array();
            $positions = array();
            $ids = array();
            # GET ids of All widgets
            foreach ($widget_rows as $key => $val) {
                $ids[] = $val['id'];
            }
            # Get show in menu_ids
            $rels = array();
            // If we have widgets
            if (count($ids) > 0) {
                $rels = $this->get_array_rel(false, '*',
                    'WHERE widget_id IN (' . implode(',', $ids) . ')');
            }
            // Collect widgets and menu rels
            $widget_collected = array();
            foreach ($rels as $key_r => $val_r) {
                $widget_collected[$val_r['widget_id']][] = (int)$val_r['menu_id'];
            }
            //print_r($widget_collected);
            // Get all widgets according to positions in template
            foreach ($widget_rows as $key => $val) {
                // If widget have related menus
                if (isset($widget_collected[$val['id']])) {
                    // If menu ID is in
                    $menu_id = 0;
                    if (isset($engine->sef->sef_params['menu_id'])) {
                        $menu_id = (int)$engine->sef->sef_params['menu_id'];
                    }
                    if (in_array($menu_id,
                        $widget_collected[$val['id']])) {
                        // Add widget to array
                        $widgets[$val['position']][$key]['id'] = $val['id'];
                        $widgets[$val['position']][$key]['options'] = json_decode($val['options'],true);
                        $widgets[$val['position']][$key]['name'] = $val['name'];
                        $widgets[$val['position']][$key]['native_name'] = $val['native_name'];
                        // Add position to array
                        $positions[$val['position']] = 1;
                    }
                } // Widget doesn't have attached menus and by default we put him in array
                else {
                    // Add widget to array
                    $widgets[$val['position']][$key]['id'] = $val['id'];
                    $widgets[$val['position']][$key]['options'] =  json_decode($val['options'],true);
                    $widgets[$val['position']][$key]['name'] = $val['name'];
                    $widgets[$val['position']][$key]['native_name'] = $val['native_name'];
                    // Add position to array
                    $positions[$val['position']] = 1;
                }
            }
            //print_r($widgets);
            // Go through positions and replace them with widgets
            $i = 0;
            foreach ($positions as $key => $val) {
                ob_start();
                foreach ($widgets[$key] as $key_w => $val_w) {
                    // add replaced to array
                    if (!in_array($key, $replaced)) {
                        $replaced[] = $key;
                        $position = $key;
                        // Load widget toolbar
                        if ($engine->type == 'standard') {
                            include(_ROOT_ . 'core/widgets/html/widget_toolbar.php');
                        }
                    }
                    $this->id = $val_w['id'];
                    /*$options = array();
                    if (trim($val_w['options']) !== '') {
                        $options = explode(";", $val_w['options']);
                    }
                    $widget_options = array();
                    foreach ($options as $key_o => $val_o) {
                        $opt = explode(":", $val_o);
                        $option_name = $opt[0];
                        $option_value = str_replace(array(">>>", "|||"),
                            array(";", ":"), $opt[1]);
                        $widget_options[$option_name] = $option_value;
                    }*/
                    /**
                     * load and place widget
                     */
                    $this->load_and_place($val_w['id'], $val_w['native_name'],
                        $val_w['name'], $val_w['options'], $i);
                    // Prepare us for next one
                    $i++;
                }
                //print_r($this->widgets_list);
                //echo $html_widget;
                $html_widget = ob_get_contents();
                // Add widgets to page
                $html = preg_replace('/[<]+[scms]+[:]+[widget]+[:]+' .
                    $key . '+[>]/', $html_widget, $html);
                ob_end_clean();
            }
        }
        // add widgets
        // Return HTML
        return $html;
    }

    public function get_array_rel($addopt = true, $what_to_get = '*',
                                  $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . " FROM rel_widgets_menus "
            . $filter_params . " ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     * Load & place widget on the wrigth position
     *
     * @name load_and_place
     * @param int $id
     * @param string $native_name
     * @param string $name
     * @param array $settings
     * @param int $i
     * @
     */
    protected function load_and_place($id, $native_name, $name, $settings, $i)
    {
        $class_name = 'widget_' . $native_name . "_class";
        $include_class = 'widgets/' . $native_name . '/' .
            $native_name . '_class.php';
        // Load widget class
        switch ($this->engine->type) {
            case "admin":
                include_once _APP_ADMIN_DIR_ . $include_class;
                break;
            default:
                include_once _APP_DIR_ . $include_class;
                break;
        }
        // create new widget
        $this->widgets_list[$i] = new $class_name;
        $this->widgets_list[$i]->set_id($id);
        $this->widgets_list[$i]->set_settings($settings);
        $this->widgets_list[$i]->set_real_name($native_name);
        /**
         * Prepare widget with everything that we need
         *
         */
        $this->widgets_list[$i]->prepare_me();
        // Place widget
        $this->widgets_list[$i]->place_me();
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function update($id)
    {
        global $engine;
        # Get settings
        $this->get_settings($id);
        # get vals
        $requested = array("name", "active", "position");
        foreach ($this->settings['default'] as $key => $val) {
            # Add requested
            $requested[] = $val['name'];
        }
        # vals
        $vals = $engine->security->get_vals($requested);
        # Setup query
        $items = array();
        foreach ($this->settings['default'] as $key => $val) {
            # add item
            if (is_array($vals[$val['name']])) {
                $items[] = $val['name'] . ":" . str_replace(array(";", ":"),
                        array(">>>", "|||"), implode(",", $vals[$val['name']]));
            } else {
                $items[] = $val['name'] . ":" . str_replace(array(";", ":"),
                        array(">>>", "|||"), $vals[$val['name']]);
            }
        }
        # set query part
        $query_part = $engine->security->get_val(implode(";", $items));
        # set query
        $query = "UPDATE $this->table SET name='" . $vals['name'] . "',
            active='" . $vals['active'] . "',  position='" . $vals['position']
            . "', options='" . $query_part . "' WHERE id=" . $id .
            " LIMIT 1;";
        echo $query;
        $result = $engine->dbase->insertQuery($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @global object $engine
     * @param int $id
     * @return array
     */
    public function get_settings($id = 0)
    {
        global $engine;

        # Read widget info
        $widget = $this->get_widgets(false, 'native_name, options,type',
            'WHERE id=' . (int)$id);
        $this->real_name = $widget[0]['native_name'];

        # setup settings array
        $settings_dbase = explode(";", $widget[0]['options']);
        $add = '';
        switch ($widget[0]['type']) {
            case "admin":
                $add = 'admin/';
                break;
            case "mobile":
                $add = 'mobile/';
                break;
        }
        # check for file existance
        if (!file_exists($engine->path . $add . 'widgets/' .
            $this->real_name . '/settings/settings.xml')
        ) {
            return false;
        }
        # Read predefined settings(XML)
        $xml = simplexml_load_file($engine->path . $add . 'widgets/' .
            $this->real_name . '/settings/settings.xml');
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
                    $this->settings['default'][$i]['default_value']
                        = $default_value;
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
            if (isset($set[1])) {
                $value = str_replace(array(">>>", "|||"), array(";", ":"),
                    $set[1]);
            }
            $this->settings['written'][$i]['name'] = $set[0];
            $this->settings['written'][$i]['value'] = $value;
            $i++;
        }
        return $this->settings;
    }

    /**
     * @name  Get array of widgets
     *
     */
    public function get_widgets($addopt = true, $what_to_get = '*',
                                $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . " FROM " . $this->table .
            " " . $filter_params . " ORDER BY " . $order_by . " "
            . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    public function copy($id)
    {
        global $engine;
        $widget = $this->get_widgets(false, '*', 'WHERE id=' . $id);
        $query = "INSERT INTO $this->table (name, native_name, options,
                position, active, type, created, creator)
                VALUES ('" . $widget[0]['name'] . "_copy','"
            . $widget[0]['native_name'] . "','" . $widget[0]['options'] .
            "', '" . $widget[0]['position'] . "', 0, '" . $widget[0]['type']
            . "', NOW(),'" . $engine->users->get_id() . "');";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add new widget
     */
    public function add()
    {
        global $engine;
        $vals = $engine->security->get_vals(array('name', 'real_name', 'type'));
        $this->name = $vals['name'];
        $this->real_name = $vals['real_name'];
        $this->type = $vals['type'];
        $query = "INSERT INTO " . $this->table . "(name, native_name, type)
            VALUES('" . $this->name . "','" . $this->real_name . "','"
            . $this->type . "');";
        $this->id = $engine->dbase->insertQuery($query);
        return $this->id;
    }

    /**
     * @param mixed $id
     * @name Deactivate user account
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
        $query = "UPDATE " . $this->table . " SET active=" . $active . "
            WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all widgets detected in directory
     * @return array
     * */
    public function get_all_widgets()
    {
        global $engine;
        // Set dirs
        $dir = array(
            'mobile' => $engine->path . 'mobile/widgets',
            'admin' => $engine->path . 'admin/widgets',
            'standard' => $engine->path . 'widgets'
        );
        // Go through dirs
        foreach ($dir as $key => $val) {
            // Open dir
            $handle = opendir($val);
            // Precreate array
            $found[$key] = array();
            if ($handle) {
                /* This is the correct way to loop over the directory. */
                while (false !== ($file = readdir($handle))) {
                    // Go through dir
                    if ($file != "." && $file != "..") {
                        // Add founded in to array
                        if (is_dir($val . "/" . $file)) {
                            $found[$key][] = $file;
                        }
                    }
                }
                sort($found[$key]);
                // Close handler
                closedir($handle);
            }
        }
        return $found;
    }
}

?>
