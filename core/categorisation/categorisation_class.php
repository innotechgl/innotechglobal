<?php
include_once $engine->path . 'core/categorisation/abstract/category_item.php';

class categorisation extends page_class
{

    public $table = '';
    public $page = '';
    public $id = 0;
    public $name = '';
    public $parent_id = 0;
    public $active = 0;
    public $created = '0000-00-00 00:00:00';
    public $creator = 0;
    public $modified = '0000-00-00 00:00:00';
    public $modifier = 0;
    public $image = '';
    public $description = '';
    protected $currentPath = array();
    public $lang;
    public $alias;
    // Photo
    public $photo_core;
    public $dir = '';
    public $sizes = array(46, 200, 470, 4000);
    public $prefixes = array("icon_", "thumb_", "main_", "");
    // list of all categories
    public $categories = array();
    public $founded_categories = array();
    public $children = array();
    public $parents = array();
    // Individual settings array
    public $settings = array();
    // Setup check for duplicates
    public $check_for_duplicates = false;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param bool $addopt
     * @param string $what_to_get
     * @param string $filter_params
     * @param string $order_by
     * @param string $order_direction
     * @param string $table
     * @return array
     */
    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC', $table = '')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . " 
                  FROM " . $this->table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /*
     * INSERT NEW
     */
    public function add()
    {
        global $engine;
        $engine->load_util('photos', utils::by_real_name);
        $this->photo_core = new photos_class();
        $this->photo_core->dir = $engine->path . $this->photo_core->dir . $this->page;
        $requested = array("alias", "name", "active", "parent_id", "language", "description");
        $vars = $engine->security->get_vals($requested);
        $this->name = (string)$vars['name'];
        $this->language = (string)$vars['language'];
        if ($engine->sef->sef_params['id']) {
            $this->parent_id = (int)$engine->sef->sef_params['id'];
        } else {
            $this->parent_id = (int)$vars['parent_id'];
        }
        $this->active = (int)$vars['active'];
        $this->alias = $vars['alias'];
        // Set alias
        if (trim($vars['alias']) !== '') {
            $this->alias = $engine->sef->filename_sef($vars['alias']);
        } else {
            $this->alias = $engine->sef->filename_sef($vars['names']);
        }
        $this->description = (string)$vars['description'];
        /*if ($this->check_duplicated($this->name)) {
            return false;
        }*/
        $requested_files = array("photo");
        $vars_files = $engine->security->get_files($requested_files, array("jpg", "png", "gif", "swf"));
        $query_array = array();
        // Set creator
        $this->creator = (int)$engine->users->get_id();
        $engine->log->write("0", "gallery", "Trying to upload " . count($requested_files) . ", named:" . $requested_files[0]['name'], 1);
        // Go through vars
        // Create resized images
        $this->photo_core->dir .= '/' . $this->parent_id . '/';
        $imgs = $this->photo_core->createResizedImg($this->sizes, $this->prefixes);
        if (count($imgs) > 0) {
            foreach ($imgs as $key_f => $var_f) {
                $this->image = $engine->security->get_val($var_f);
            }
        }
        $query = "INSERT INTO " . $this->table . "
            (alias,name, parent_id, active, language, image, description, created, creator) VALUES
            ('" . $this->alias . "','" . $this->name . "','" . $this->parent_id . "','" . $this->active . "','" . $this->language . "', '" . $this->image . "', '" . $this->description . "', NOW(), '" . $engine->users->get_id() . "');";
        echo $query;
        $engine->log->write($engine->users->get_id(), 'article_categories', 'QUERY: ' . $query, 0);
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $name
     */
    private function check_duplicated($name = '')
    {
        global $engine;
        if ($this->check_for_duplicates === false) {
            return false;
        }
        $array = $this->get_array(false, 'id', 'WHERE parent_id=' . (int)$engine->sef->sef_params['id'] . ' AND name LIKE "' . $name . '"');
        if (count($array) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * UPDATE 
     */
    public function update()
    {
        global $engine;
        $engine->load_util('photos');
        $this->photo_core = new photos_class();
        $this->photo_core->dir = $engine->path . $this->photo_core->dir . $this->page;
        $requested = array("alias", "name", "active", "language", "parent_id", "description");
        $vars = $engine->security->get_vals($requested);
        $this->id = (int)$engine->sef->sef_params['id'];
        $this->name = (string)$vars['name'];
        $this->parent_id = (int)$vars['parent_id'];
        $this->active = (int)$vars['active'];
        $this->language = (string)$vars['language'];
        $this->description = (string)$vars['description'];
        $this->alias = $vars['alias'];
        $requested_files = array("photo");
        $vars_files = $engine->security->get_files($requested_files, array("jpg", "png", "gif", "swf"));
        $query_array = array();
        // Set creator
        $this->creator = (int)$engine->users->get_id();
        $engine->log->write("0", "gallery", "Trying to upload " . count($requested_files) . ", named:" . $requested_files[0]['name'], 1);
        // Go through vars
        // Create resized images
        $this->photo_core->dir .= '/' . $this->parent_id . '/';
        echo $this->photo_core->dir;
        $imgs = array();
        if (isset($_FILES['photo'])) {
            $imgs = $this->photo_core->createResizedImg($this->sizes, $this->prefixes);
        }
        $change_img = '';
        if (count($imgs) > 0) {
            $change_img = "image='" . $engine->security->get_val($imgs[0]) . "',";
        }
        $query = "UPDATE " . $this->table . "
            SET name='" . $this->name . "', 
                alias='" . $this->alias . "',
                parent_id='" . $this->parent_id . "',
                active='" . $this->active . "',
                description='" . $this->description . "',
                $change_img
		language='" . $this->language . "',
                modified=NOW(),
                modifier='" . $engine->users->get_id() . "'
                WHERE id=" . $this->id . " LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * DELETE 
     */
    public function delete($id = NULL)
    {

        if ($id == NULL) {
            return false;
            exit();
        }
        if (is_array($id) && count($id) > 0) {
            $this->id = implode(",", $id);
        } else {
            $this->id = $id;
            $cats = $this->get_array(false);
            $ids = $this->get_all_children($cats, $this->id);
            $ids[] = $this->id;
        }
        $query = "DELETE FROM $this->table WHERE
                id IN (" . implode(",", $ids) . ");";
        if ($this->engine->dbase->insertQuery($query)) {
            foreach ($ids as $id) {
                $this->engine->updater->addDeletedItem($this->page, $id);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param int $id_parent
     */
    private function get_children($collection = array(), $id_parent = 0)
    {
        foreach ($collection as $key => $val) {
            if ($val['parent_id'] == $id_parent) {
                $this->children[] = $val['id'];
                $this->get_children($collection, $val['id']);
            }
        }
    }

    public function get_all_children($categories, $id_parent)
    {
        $this->children = array();
        $this->get_children($categories, $id_parent);
        return $this->children;
    }

    public function count_subcategories()
    {
        $subcats = array();
        foreach ($this->categories as $key => $val) {
            // Set NULL for ID
            if (!isset($subcats[$val['id']])) {
                $subcats[$val['id']] = 0;
            }
            // Set NULL for PARENT ID
            if (!isset($subcats[$val['parent_id']])) {
                $subcats[$val['parent_id']] = 0;
            }
            $subcats[$val['parent_id']]++;
        }
        return $subcats;
    }

    public function get_path($id)
    {
        // Check ID
        if ($id == 0) {
            $this->founded_categories[0]['id'] = 0;
            $this->founded_categories[0]['name'] = "root";
            $this->founded_categories[0]['parent_id'] = -1;
            $this->founded_categories[0]['alias'] = '';
            $this->founded_categories = array_reverse($this->founded_categories);
            return null;
        }
        if (count($this->categories) == 0) {
            $this->categories = $this->get_array(false, '*');
        }
        foreach ($this->categories as $key => $val) {
            if ($val['id'] == $id) {
                // Add to array
                $this->founded_categories[$val['id']]['id'] = $val['id'];
                $this->founded_categories[$val['id']]['name'] = $val['name'];
                $this->founded_categories[$val['id']]['parent_id'] = $val['parent_id'];
                $this->founded_categories[$val['id']]['alias'] = $val['alias'];
                if (isset($val['link'])) {
                    $this->founded_categories[$val['id']]['link'] = $val['link'];
                }
                break;
            }
        }
        $this->get_path($val['parent_id']);
    }

    public function get_path_by_alias($alias)
    {
        // Check ID
        if ($alias == '') {
            $this->founded_categories[0]['id'] = 0;
            $this->founded_categories[0]['name'] = "root";
            $this->founded_categories[0]['parent_id'] = -1;
            $this->founded_categories[0]['alias'] = '';
            $this->founded_categories = array_reverse($this->founded_categories);
            return null;
        }
        foreach ($this->categories as $key => $val) {
            if ($val['alias'] == $alias) {
                // Add to array
                $this->founded_categories[$val['id']]['id'] = $val['id'];
                $this->founded_categories[$val['id']]['name'] = $val['name'];
                $this->founded_categories[$val['id']]['parent_id'] = $val['parent_id'];
                $this->founded_categories[$val['id']]['alias'] = $val['alias'];
                $this->founded_categories[$val['id']]['link'] = $val['link'];
                break;
            }
        }
        @$this->get_path($val['parent_id']);
    }

    public function get_founded_categories()
    {
        return $this->founded_categories;
    }

    /**
     * @param String $alias
     * @return bool
     */
    public function findByAlias($alias)
    {
        foreach ($this->categories as $key => $val) {
            if (@$val['alias'] == $alias) {
                return $val;
                break;
            }
        }
        return false;
    }

    /**
     * @name get name of categorie
     * @param int $id
     */
    public function get_name($id = 0)
    {
        foreach ($this->categories as $key => $val) {
            if ($val['id'] == $id) {
                return $val['name'];
                exit();
            }
        }
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
        $query = "UPDATE " . $this->table . " SET active=" . $active . " WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
            return true;
        } else {
            return false;
        }
    }

    public function categorise($parent_id = 0, $active = '', $lang = '')
    {
        $extend_params = array();
        $where = '';
        if (trim($active) !== '') {
            $extend_params[] = $active;
        }
        if (trim($lang) !== '') {
            $extend_params[] = $lang;
        }
        if (!preg_match("/WHERE/i", $active) && count($extend_params) > 0) {
            $where = 'WHERE ';
        }
        $arr = $this->get_array(false, '*', $where . implode(' AND ', $extend_params), 'no', 'ASC');
        $subcats = array();
        foreach ($arr as $val) {
            if ($val['parent_id'] !== 0) {
                if (isset($subcats[$val['parent_id']])) {
                    $subcats[$val['parent_id']]['number']++;
                } else {
                    $subcats[$val['parent_id']]['number'] = 1;
                }
            }
        }
        $this->subcat_generator($arr, $subcats, $parent_id);
    }

    public function load($id)
    {
        $item = $this->get_array(false, '*', 'WHERE id=' . (int)$id);
        if (count($item) > 0) {
            return $item[0];
        } else {
            return false;
        }
    }

    /**
     * Sredjujemo podkategorije
     * @param array $niz
     * @param array $niz_podredjene
     * @param int $id_nadredjeni
     * @example submenu_generator($niz, $niz_podredjene, $id_nadredjeni=0)
     */
    function subcat_generator($arr, $arr_children, $parent_id = 0)
    {
        foreach ($arr as $val) {
            if ($parent_id == 0 && $val['parent_id'] == 0) {
                // Open item
                $this->categories[] = 'item_open';
                // Write name
                foreach ($val as $key_item => $val_item) {
                    $item['item'][$key_item] = $val[$key_item];
                }
                $this->categories[] = $item;
                $arCH = 0;
                if (isset($arr_children[$val['id']]['number'])) {
                    $arCH = $arr_children[$val['id']]['number'];
                }
                if ($arCH > 0) {
                    // Open subcat
                    $this->categories[] = 'subcat_open';
                    // Reopen
                    $this->subcat_generator($arr, $arr_children, $val['id']);
                    // close subcat
                    $this->categories[] = 'subcat_close';
                }
                // Close item
                $this->categories[] = 'item_close';
            } else {
                if (@$val['parent_id'] == $parent_id) {
                    $this->categories[] = 'item_open';
                    // Write name
                    foreach ($val as $key_item => $val_item) {
                        $item['item'][$key_item] = $val[$key_item];
                    }
                    $this->categories[] = $item;
                    $arCH = 0;
                    if (isset($arr_children[$val['id']]['number'])) {
                        $arCH = $arr_children[$val['id']]['number'];
                    }
                    if ($arCH > 0) {
                        $this->categories[] = 'subcat_open';
                        $this->subcat_generator($arr, $arr_children, $val['id']);
                        $this->categories[] = 'subcat_close';
                    }
                    $this->categories[] = 'item_close';
                } else {
                    // echo 'nije: '.$parent_id."<br />";
                }
            }
        }
    }

    /**
     * Sort
     * */
    public function sort($position = '', $ids = array())
    {
        global $engine;
        $this->position = $position;
        $this->id = $ids;
        foreach ($ids['sort'] as $key => $val) {
            $query_array[] = "UPDATE " . $this->table . " SET no=$key WHERE id=$val LIMIT 1;";
        }
        foreach ($query_array as $key => $val) {
            if (!$engine->dbase->insertQuery($val)) {
                exit();
                return false;
            }
        }
        return true;
    }

    /**
     * Get parent categorie
     * @param int $id
     */
    public function get_parent($id = 0)
    {
        # get parent
        $parent_array = $this->get_array(false, '*', 'WHERE id=' . $id);
        if (count($parent_array) > 0) {
            return $parent_array[0];
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
            if (@count($options_db[0]['options']) > 0) {
                $settings_dbase = json_decode($options_db[0]['options']);
            }
        }
        # Read predefined settings(XML)
        $xml_settings = array();

        if (file_exists($engine->path . 'pages/' . $this->page . '/settings/settings.xml')) {
            $xml = simplexml_load_file($engine->path . 'pages/' . $this->page . '/settings/settings.xml');
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
                if (trim($ar['type']) == 'drop') {
                    $this->settings['default'][$i]['drop_options'] = (array)$ar['fieldOptions'];
                }
                if ($ar['defaultValue'] !== 'null') {
                    $default_value = $ar['defaultValue'];
                }
                $i++;
            }
            # settings
            if (isset($settings_dbase)) {
                /* print_r($settings_dbase); */
                foreach ($settings_dbase as $key => $val) {
                    $this->settings['written'][$key] = $val;
                }
            }
            // Set quick readable setting
            foreach ($this->settings['default'] as $key_s => $val_s) {
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
                    if (isset($val_s['default_value'])) {
                        if (@$val_s['default_value'] !== null) {
                            $value = $val_s['default_value'];
                        }
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
            $query_part[$val] = $engine->security->get_val($vals[$val]);
        }
        $options = json_encode($query_part);
        $query = "UPDATE " . $this->table . " SET options='" . $options . "' WHERE id=" . $id . " LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if blog belong to registered user
     * @param int $id
     */
    public function categorie_owner_check($id = 0)
    {
        global $engine;
        $allowed = false;
        # get active user_group type
        if ($engine->users->check_user_group_type(2) || $engine->users->check_user_group_type(2)) {
            $allowed = true;
        } else {
            # get creator of blog
            $creator = $this->get_array(false, 'creator', 'WHERE creator=' . $engine->users->get_id() . ' AND id=' . $id);
            if (count($creator) > 0) {
                $allowed = true;
            }
        }
        return $allowed;
    }

    /**
     * Gets image of category
     * @param int $id
     * @return string
     */
    public function get_image($id, $prefix)
    {
        global $engine;
        $this->id = $engine->security->get_val($id);
        $categorie = $this->get_array(false, 'image,parent_id', 'WHERE id=' . $this->id);
        if (count($categorie) == 0) {
            return false;
        } else if (trim($categorie[0]['image']) == '') {
            return false;
        } else if (file_exists($engine->path . 'media/images/' . $this->page . '/' . $categorie[0]['parent_id'] . '/' . $prefix . $categorie[0]['image'])) {
            return $engine->path . 'media/images/' . $this->page . '/' . $categorie[0]['parent_id'] . '/' . $prefix . $categorie[0]['image'];
        } else {
            return false;
        }
    }

    /**
     * Remove image from category
     * @param int $id
     *
     */
    public function removeIMG($id = 0)
    {
        global $engine;
        $cat = $this->get_array(false, '*', 'WHERE id=' . $id);
        if (count($cat) > 0) {
            foreach ($this->prefixes as $key_p => $val_p) {
                // Delete images
                unlink("/media/images/" . $this->page . "/" . $cat[0]['parent_id'] . "/" . $val_p . $cat[0]['image']);
            }
            // Remove img from dbase
            $query = "UPDATE " . $this->table . " SET image='' WHERE id=" . $id . ' LIMIT 1;';
            if ($engine->dbase->insertQuery($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     *
     * @param array $array
     */
    public function setCurrentPath($array)
    {
        $this->currentPath = $array;
    }

    /**
     *
     * @return multitype:array
     */
    public function getCurrentPath()
    {
        return $this->currentPath;
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public function isInCurrentPath($id)
    {
        $found = false;
        foreach ($this->currentPath as $val) {
            if ($val['id'] == $id) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}

?>