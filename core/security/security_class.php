<?php

/**
 * SECURITY CLASS
 * Protects variables AND pages
 *
 */
class sCMS_security
{

    const VAL_STRING = 'STRING';
    const VAL_INT = 'INT';
    const VAL_HTML = 'HTML';
    const VAL_MAIL = 'MAIL';
    const INVALID = 'invalid';
    const VALID = 'valid';
    public $writing;
    public $recording;
    public $vars;

    // SET CONST
    public $lang;
    protected $engine;
    protected $page = 'security';
    private $table_groups_component = 'group_page_xref';
    private $table_user_component = 'user_page_xref';
    private $table_user_content = 'ownership';

    public function __construct()
    {
        global $engine;
        $this->engine = &$engine;
        include_once $this->engine->path . 'core/language/language_class.php';
        include_once $this->engine->path . 'language/core/security/security_lat.php';
        $this->lang = new security_language();
    }

    /**
     * @name Get secured files from $_FILES
     * @return array
     */
    public function get_files($requested_vals = array(), $allowed = array())
    {
        $arr = array();
        $var = $_FILES;
        foreach ($requested_vals as $key => $val) {
            $this->engine->log->write("0", "article_photo", "Requested vals: " . $val, 1);
            if (isset($var[$val])) {
                // Check if it is an array
                if (is_array($var)) {
                    // Go through array
                    $this->engine->log->write("0", "article_photo", "Ukupno imena: " . count($var[$val]['name']), 1);
                    $this->engine->log->write("0", "article_photo", "Ime: " . $var[$val]['name'], 1);
                    if (is_array($var[$val]['name'])) {
                        for ($i = 0; $i < count($var[$val]['name']); $i++) {
                            // Check names
                            if ($this->check_file(strtolower($var[$val]['name'][$i]), $allowed)) {
                                // Write if is not risky
                                $arr[$val][$i]['name'] = $var[$val]['name'][$i];
                                $arr[$val][$i]['type'] = $var[$val]['type'][$i];
                                $arr[$val][$i]['tmp_name'] = $var[$val]['tmp_name'][$i];
                                $arr[$val][$i]['error'] = $var[$val]['error'][$i];
                                $arr[$val][$i]['size'] = $var[$val]['size'][$i];
                            }
                        }
                    } else {
                        if ($this->check_file(strtolower($var[$val]['name']), $allowed)) {
                            $this->engine->log->write("0", "article_photo", "Dozvoljen: " . $var[$val]['name'], 1);
                            $arr[$val][0]['name'] = $var[$val]['name'];
                            $arr[$val][0]['type'] = $var[$val]['type'];
                            $arr[$val][0]['tmp_name'] = $var[$val]['tmp_name'];
                            $arr[$val][0]['error'] = $var[$val]['error'];
                            $arr[$val][0]['size'] = $var[$val]['size'];
                        } else {
                            $this->engine->log->write("0", "article_photo", "Nije dozvoljen: " . $var[$val]['name'], 1);
                        }
                    }
                } else {
                    if ($this->check_file(strtolower($var[$val]), $allowed)) {
                        $this->engine->log->write("0", "article_photo", "Dozvoljen: " . $var[$val]['name'], 1);
                        $arr[$val][0]['name'] = $var[$val]['name'];
                        $arr[$val][0]['type'] = $var[$val]['type'];
                        $arr[$val][0]['tmp_name'] = $var[$val]['tmp_name'];
                        $arr[$val][0]['error'] = $var[$val]['error'];
                        $arr[$val][0]['size'] = $var[$val]['size'];
                    } else {
                        $this->engine->log->write("0", "article_photo", "Nije dozvoljen: " . $var[$val]['name'], 1);
                    }
                }
            } else {
                $arr[$val] = NULL;
            }
        }
        return $arr;
    }

    public function check_file($file_name = '', $allowed = array())
    {
        $ext = explode(".", $file_name);
        if (in_array($ext[count($ext) - 1], $allowed)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $name
     * @param array $uploaded_arrays
     * @return string
     */
    public function get_type_of_uploaded_file($name = '', $uploaded_arrays = array())
    {
        $type = null;
        foreach ($uploaded_arrays as $key => $val) {
            foreach ($val as $key_v => $val_v) {
                if ($val_v['name'] == $name) {
                    $type = $val_v['type'];
                }
            }
        }
        return $type;
    }

    /**
     *
     * @param string $type
     * @return array
     */
    public function get_all_vars($type = 'get')
    {
        $arr = array();
        switch (strtolower($type)) {
            case 'get':
                $var = $_GET;
                break;
            case 'post':
                $var = $_POST;
                break;
        }
        foreach ($var as $key => $val) {
            // Check if it is an array
            if (is_array($var[$key])) {
                foreach ($var[$key] as $key_two => $val_two) {
                    $arr[$key][] = $this->check_it($val_two);
                }
            } else {
                $arr[$key] = $this->check_it($var[$key]);
            }
        }
        return $arr;
    }

    /**
     *
     * @global object $engine
     * @param string $val
     * @param string $type
     * @return mixed
     */
    protected function check_it($val = '', $type = '')
    {
        // if type is defined check it
        //echo $val."<br />".$type."<BR />";
        switch ($type) {
            // remove everything from_string except No and Letters
            case self::VAL_STRING :
                $val = filter_var($val, FILTER_DEFAULT);
                break;
            // Convert to int
            case self::VAL_INT:
                $val = filter_var($val, FILTER_VALIDATE_INT);
                break;
            case self::VAL_HTML:
                $val = $val;
                break;
            default:
                $val = filter_var($val, FILTER_DEFAULT);
                break;
        }
        // real escape string
        $checked_val = $this->engine->dbase->realEscape($val);
        // Check variable
        return $checked_val;
    }

    /**
     * Save group security settings
     * @param string $type
     *
     */
    public function save_group_security_settings($type = '', $group = 0)
    {
        $requested = $this->engine->pages->pages;
        $vars = $this->engine->security->get_vals($requested);
        $querys = array();
        // Protect type
        $type = $this->engine->security->get_val($type, sCMS_security::VAL_STRING);
        foreach ($this->engine->pages->pages as $key => $val) {
            $page = $this->engine->security->get_val($val, sCMS_security::VAL_STRING);
            // Remove previous
            $query = "DELETE FROM " . $this->table_groups_component . "
                WHERE group_id=" . (int)$group . " AND page='" . $page . "'
                    AND type='" . $type . "'";
            $this->engine->dbase->insertQuery($query);
            $settings = $this->engine->get_page_settings($val, $type);
            if ($settings !== false) {
                $arr = array();
                if (isset($settings->tasks)) {
                    $arr = (array)$settings->tasks;
                }
                // Do this if we have array of tasks
                if (isset($arr['task'])) {
                    if (is_array($arr['task'])) {
                        foreach ($arr['task'] as $key_sett => $val_sett) {
                            if (isset($vars[$val]) && count($vars[$val]) > 0) {
                                if (in_array($val_sett, $vars[$val])) {
                                    $querys[] = "('" . $val . "', '" . $val_sett . "',
										'" . $group . "', '" . $type . "')";
                                }
                            }
                        }
                    } else {
                        if (isset($vars[$val]) && count($vars[$val]) > 0) {
                            if (in_array($arr['task'], $vars[$val])) {
                                $querys[] = "('" . $val . "', '" . $arr['task'] . "',
									'" . $group . "', '" . $type . "')";
                            }
                        }
                    }
                }
            }
        }
        // INSERT NEW
        if (count($querys) > 0) {
            $query_insert = "INSERT INTO " . $this->table_groups_component . "
                (page, task, group_id, type) VALUES " . implode(",", $querys);
            $this->engine->dbase->insertQuery($query_insert);
        }
        return true;
    }

    /**
     * @name Get secured variables from $_POST, $_GET, $_SESSION...
     * @example $val = sCMS_security::get_vals(array("post1"=>"expected type of value","post2"=>"expected type of value"));
     */
    public function get_vals($requested_vals = array(), $type = 'post')
                    {
                        $arr = array();
                        switch (strtolower($type)) {
                            case 'get':
                                $var = $_GET;
                                break;
                            case 'post':
                                $var = $_POST;
                                break;
                        }
                        foreach ($requested_vals as $key => $val) {
                            if (isset($var[$val])) {
                                // Check if it is an array
                                if (is_array($var[$val])) {
                                    foreach ($var[$val] as $key_two => $val_two) {
                                        $arr[$val][] = $this->check_it($val_two, $key_two);
                                    }
                } else {
                    $arr[$val] = $this->check_it($var[$val]);
                }
            } else {
                $arr[$val] = NULL;
            }
        }
        return $arr;
    }

    /**
     * @name Get secured value from variable
     * @param mixed $val
     * @param string $type
     * @param string $default
     * @return mixed
     */
    public function get_val($val, $type = '', $default = '')
    {
        if ($val == '') {
            $val = $default;
        } else {
            $val = $this->check_it($val, $type);
        }
        return $val;
    }

    /**
     *
     * @param int $id_group
     * @todo dovrsiti!
     */
    public function load_group_security_settings($id_group, $type)
    {
        $query = "SELECT * FROM  " . $this->table_groups_component . "
            WHERE group_id=" . $id_group . " AND type LIKE '$type'";
        $this->engine->dbase->query($query);
        return $this->engine->dbase->rows;
    }

    /**
     *
     * @param array $user_groups
     * @param string $page
     * @param string $task
     * @return bool
     */
    public function check_group_security($page = '', $task = '')
    {
        $ids = array();
        if (isset($_SESSION['users']['groups'])) {
            foreach ($_SESSION['users']['groups'] as $key => $val) {
                if ($this->engine->type == 'admin') {
                    if ($val['type'] == 2) {
                        $ids[] = $val['group_id'];
                    }
                } else {
                    $ids[] = $val['group_id'];
                }
            }
            $query = "SELECT COUNT(*) AS allowed FROM
            $this->table_groups_component WHERE group_id IN
                    (" . implode(",", $ids) . ") AND page LIKE '" . $page . "'
                        AND task LIKE '" . $task . "'";
            $this->engine->dbase->query($query);
            if ($this->engine->dbase->rows[0]['allowed'] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Protect page
     * @return bool
     */
    public function protect($location = "/users/login/")
    {
        $found = false;
        $query = "SELECT * FROM " . $this->table_groups_component . "
            WHERE page='" . $this->engine->sef->sef_params['page'] . "'
                AND task='" . $this->engine->sef->sef_params['task'] . "'
                    AND type='" . $this->engine->type . "'";
        $this->engine->dbase->query($query);
        $groups = $this->engine->dbase->rows;
        // Get user groups
        $user_groups_raw = $this->engine->user_groups->get_groups_related_to_users(array($this->engine->users->active_user->get_id()));
        $user_groups = array();
        foreach ($user_groups_raw as $key => $val) {
            $user_groups[] = $val['id_rel_group'];
        }
        foreach ($groups as $key => $val) {
            if (in_array($val['group_id'], $user_groups)) {
                $found = true;
                break;
            }
        }
        if ($found == false) {
            header('Location: ' . $location);
            die();
        } else {
            return true;
        }
    }

    /**
     * Protect page
     * @return bool
     */
    public function check_user_group_right($page = '', $task = '')
    {
        $found = false;
        $query = "SELECT * FROM " . $this->table_groups_component . "
            WHERE page='" . $page . "' AND task='" . $task . "'
                AND type='" . $this->engine->type . "'";
        $this->engine->dbase->query($query);
        // Get user groups
        $user_groups = $this->engine->users->get_user_groups_ids();
        foreach ($this->engine->dbase->rows as $key => $val) {
            if (in_array($val['group_id'], $user_groups)) {
                $found = true;
                break;
            }
        }
        if ($found == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * @global object $engine
     */
    public function protectOutside()
    {
        $var_code = $this->get_vals(array('code'));
        $code = (string)$this->engine->settings->general->protectCode;
        if ($_SERVER['HTTP_REFERER'] !== $_SERVER['HTTP_HOST']) {
            if ($code !== $var_code) {
                include '../error_pages/500.php';
                die();
            }
        }
    }

    /**
     *
     * @param string $page
     * @param int $relID
     * @param array $groupID
     * @return boolean
     */
    public function owner_check($page, $relID, array $groupIDS)
    {
        $allowed = false;
        $query = "SELECT COUNT(*) AS owner_count FROM " . $this->table_user_content .
            " WHERE rel_page='" . $page . "' AND rel_id=" . (int)$relID
            . " AND group_id IN (" . implode(",", $groupIDS) . ")";
        $this->engine->dbase->query($query);
        if ($this->engine->dbase->rows[0]["owner_count"] > 0) {
            return true;
        } else {
            return false;
        }
        return $allowed;
    }

    /**
     * @param $page
     * @param $relID
     * @param $userID
     */
    public function writeOwner($page, $relID, $userID)
    {
        /**
         * @todo write owner - create new class under security
         */
    }

    public function deleteOwner($page, $relID, $userID)
    {
        /**
         * @todo delete owner - create new class under security
         */
    }

    /**
     * @return string
     *
     */
    public function get_ip()
    {
        // Get ip
        $ip = $_SERVER['REMOTE_ADDR'];
        // Ip info
        $ip_info = array("ip" => "0.0.0.0", "status" => self::INVALID);
        if (preg_match('/[0-9].[0-9].[0-9].[0-9]/', $ip)) {
            $ip_info['ip'] = $ip;
            $ip_info['status'] = self::VALID;
        }
        // return ip
        return $ip_info;
    }

    /**
     * Deny IP from accessing
     *
     */
    public function deny_ip()
    {
        exec('python deny.py ' . $_SERVER['REMOTE_ADDR']);
}
}

?>