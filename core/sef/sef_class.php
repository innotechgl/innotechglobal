<?php

Class sef
{

    public $sef_file = '';
    public $sef_params = array();
    public $sef_request = array("page", "task", "id", "id_menu");
    private $table = 'sef_pages';
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
    }

    /**
     * READ ROUTE
     *
     */
    public function readRoute()
    {
        // Check load of safe route
        switch ($this->engine->settings->general->sef) {
            case true:
                $this->sefRoute();
                break;
            default:
                $this->nonSefRoute();
                break;
        }
    }

    public function readAlias()
    {
        $aliases = explode("?", $_SERVER['REQUEST_URI']);
        $aliases = explode("/", $aliases[0]);
        $aliases = array_reverse($aliases);
        $toRemove = array();
        foreach ($aliases as $key => $val) {
            if ($val == '') {
                $toRemove[] = $key;
            }
        }
        foreach ($toRemove as $val) {
            unset($aliases[$val]);
        }
        $arr = array_values($aliases);
        //print_r($arr);
        if (!isset($arr[0])) {
            $arr[0] = '';
        }
        return $arr[0];
    }

    /**
     * @return
     */
    public function readMenuAlias()
    {
        $menu_item = null;
        $lang = $this->engine->settings->general->lang;
        $aliases = explode("?", $_SERVER['REQUEST_URI']);
        $aliases = explode("/", $aliases[0]);
        array_reverse($aliases);
        $toRemove = array();
        $url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $parts = explode("/", str_replace($this->engine->settings->general->site, "", $url));
        if ($this->engine->settings->general->multilingual) {
            $this->engine->set_lang($parts[0]);
            unset($parts[0]);
        }
        $aliases = $parts;
        foreach ($aliases as $key => $val) {
            if ($val == '') {
                $toRemove[] = $key;
            }
        }
        foreach ($toRemove as $val) {
            unset($aliases[$val]);
        }
        $aliases = array_reverse($aliases);
        $arr = array_values($aliases);

        if (!isset($arr[0])) {
            $arr[0] = '';
        } else {
            foreach ($arr as $key => $val) {
                $menuEL = $this->engine->menu->findByAlias($val);
                if ($menuEL !== false) {
                    $menu_item = $menuEL;
                    // if not first element there could be something more to route
                    if ($key > 0) {
                        $this->sef_params['route_more'] = 1;
                    }
                    break;
                }
            }
        }
        return $menu_item;
    }

    private function sefRoute()
    {
        $security = new sCMS_security();
        // Get sef FILE
        $url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $parts = explode("/", str_replace($this->engine->settings->general->site, "", $url));
        if ($this->engine->settings->general->multilingual) {
            if ($parts[0] !== "") {
                if ($this->engine->type == "standard") {
                    $this->engine->set_lang($parts[0]);
                }
                unset($parts[0]);
            } else {
                header("Location: " . $this->engine->settings->general->site . $this->engine->settings->general->lang . "/");
            }
        }
        $array_files = array_values($parts);
        // Remove junk element from array
        if (isset($array_files[0])){
            if (!in_array($this->engine->type, array("admin", "ajax", "xml", "mobile")) && !preg_match('/[.]/', $array_files[0])) {
                $array_files = array_slice($array_files, 1);
            }
        }
        foreach ($array_files as $key => $val) {
            if (isset($this->sef_request[$key])) {
                $this->sef_file[$this->sef_request[$key]] = (string)$val;
            }
        }
        foreach ($this->sef_request as $key => $val) {
            if (!isset($this->sef_file[$val])) {
                $this->sef_file[$val] = null;
            }
        }
        // Get all $_GET params
        $arr_get = $security->get_all_vars();
        $temp = array_merge($this->sef_file, $arr_get);
        # Fix potential bug with home page
        if (preg_match('/menu_id/i', $temp['page']) || preg_match('/lang/i', $temp['page'])) {
            $temp['page'] = 'home';
        }
        $this->sef_params = $temp;
    }

    private function nonSefRoute()
    {
        $security = new sCMS_security();
        $this->sef_params = $security->get_all_vars();
    }

    public function constructLink($params = array(), $file = 'index.php', $type = '')
    {

        $new_params = array();
        $non_sef_params = array();
        $add = '';
        if ($type == '') {
            $type = $this->engine->type;
        }
        switch ($type) {
            case "admin":
                $add = "admin/";
                break;
            case "ajax":
                $add = "ajax/";
                break;
            case "mobile":
                $add = "mobile/";
                break;
            case "standard":
            default:
                $add = "";
                break;
        }
        $str_link = (string)$this->engine->settings->general->server . $add;
        foreach ($params as $key => $val) {
            if (isset($this->sef_request[$key])) {
                $key_r = $this->sef_request[$key];
            } else {
                $key_r = 'undefined';
            }
            $new_params[$key_r] = $val;
        }
        if ($this->engine->type == "standard") {
            switch ($this->engine->settings->general->sef) {
                case true:
                    // Create SEF link
                    $str_link .= implode("/", $new_params);
                    $str_link .= '/' . $file;
                    break;
                default:
                    $str_link .= $file . '?';
                    foreach ($new_params as $key => $val) {
                        // Check if string exists
                        if (trim((string)$val) !== '') {
                            $non_sef_params[] = $key . '=' . $val;
                        }
                    }
                    $str_link .= implode("&", $non_sef_params);
                    break;
            }
        } else {
            $str_link = '?';
            foreach ($new_params as $key => $val) {
                // Check if string exists
                if (trim((string)$val) !== '') {
                    $non_sef_params[] = $key . '=' . $val;
                }
            }
            $str_link .= implode("&", $non_sef_params);
        }
        // Return link
        return $str_link;
    }

    public function readLink($link)
    {
        $link = str_replace('?', '', $link);
        $params_raw = explode("&", $link);
        foreach ($params_raw as $val) {
            $p = explode("=", $val);
            if (isset($p[1])) {
                $this->sef_params[$p[0]] = $p[1];
            }
        }
        //print_r($this->sef_params);
    }

    public function _readRoute()
    {
        // Take $_GET vars
        $this->nonSefRoute();
        $this->engine->load_page('menu');
        $this->engine->menu->categories = $this->engine->menu->get_array(false, 'id, parent_id, name, link,alias');
        $menu_item = $this->readMenuAlias();
        $this->engine->menu->get_path_by_alias($menu_item['alias']); // Get path for this menu
        $this->engine->menu->setCurrentPath($this->engine->menu->founded_categories); // Set current path
        $this->engine->sef->sef_params['menu_id'] = $menu_item['id'];
        if ($this->engine->sef->sef_params['menu_id'] <= 0) {
            $this->sefRoute();
        } else {
            $this->engine->sef->readLink($menu_item['link']);
        }
    }

    /**
     * @name Set url safe filename
     */
    public function filename_sef($filename = '')
    {
        global $engine;
        $changed = preg_replace("/[ \"\']/i", "-", $filename);
        $changed = preg_replace("/[\?]/i", "", $changed);
        // convert to lat
        $changed = $this->engine->language->cirToLat($changed, true);
        $changed = strtolower($changed);
        // remove serbian characters
        $for_replace = array("Č", "Ž", "Š", "Đ", "Ć", "DŽ", "č", "ž", "š", "đ", "ć", "dž", ".", " ", "'", '"', ",", "„", "“", ":");
        $replace_with = array("c", "z", "s", "dj", "c", "dz", "c", "z", "s", "dj", "c", "dz", "-", "-", "", "", "-", "", "", "");
        foreach ($for_replace as $key => $val) {
            $changed = str_replace($val, $replace_with[$key], $changed);
        }
        return $changed;
    }

    /**
     * @param String $name
     * @param String $value
     * @return string
     */
    public function changeParamInCurrentLink($name, $value)
    {
        $second = array();
        $linkParts = explode("?", $_SERVER['REQUEST_URI']);
        if (count($linkParts) == 1) {
            $parts[] = $name . "=" . $value;
        } else {
            $second = explode('&', $linkParts[1]);
            $second_parts = array();
            $thereIs = false;
            foreach ($second as $val) {
                $explodeParts = explode("=", $val);
                $second_parts[$explodeParts[0]] = $explodeParts[1];
                if ($explodeParts[0] == $name) {
                    $thereIs = true;
                }
            }
            if ($thereIs) {
                $second_parts[$name] = $value;
                foreach ($second_parts as $key => $val) {
                    $parts[] = $key . "=" . $val;
                }
            } else {
                $parts[] = $name . "=" . $value;
            }
        }
        $linkParts[1] = implode("&", $parts);
        $url = implode('?', $linkParts);
        return $url;
    }
}

?>