<?php

class log
{

    public $error_log = array();
    public $success_log = array();
    public $rel_id = 0;
    public $rel_page = '';
    public $settings;
    public $table_hits = 'hits';
    public $errors = array();
    protected $query_log = array();
    protected $engine;
    private $levels = array("notice", "warning", "High importance");
    private $dir = 'logs';

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
        $this->dir = $engine->path . $this->dir . "/" . date("Y") . "/" . date("m") . "/";
    }

    public function addQueryToLog($query)
    {
        $this->query_log[] = $query;
    }

    public function getQueriesFromLog()
    {
        return $this->query_log;
    }

    /**
     * @name  Get array
     *
     */
    public function get_array_hits($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . "
                  FROM " . $this->table_hits . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     *
     * Write LOG file
     * @param string $user
     * @param string $type
     * @param string $log
     * @param int $level
     */
    public function write($user = '', $type = '', $log = '', $level = 0)
    {
        // Check if there is a need to write log
        $arr = (array)$this->engine->settings->log;
        if (!in_array($level, $arr)) {
            exit();
        }
        // Create dir if it doesn't exist
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
        // Set filename
        $file = date("d") . ".txt";
        // Setup content
        $content = "[level:" . $level . "][date:" . date("Y-m-d h:m:s") . "][user:" . $user . "][log:" . $log . "][atempt_from:" . $_SERVER['REMOTE_ADDR'] . "]\r";

       /* // Open file for append
        $f = fopen($this->dir."/".$file,'a');
        
        // Write to file
        fwrite($f,$content,strlen($content));
        
        // Close file
        fclose($f);*/
    }

    /**
     * @param string $class
     * @param string $log
     *
     * Adds error to error_log
     */
    public function add_error_log($class = "", $log = "")
    {
        global $engine;
        // Add error
        $this->error_log[$class][] = $log;
        //session_register('log_'.$class.'error');
        $_SESSION['log_' . $class . 'error'] = $this->error_log[$class];
    }

    /**
     *
     * @param string $class
     * @param string $log
     */
    public function add_success_log($class = "", $log = "")
    {
        global $engine;
        // Add success
        $this->success_log[$class][] = $log;
        //session_register('log_'.$class.'success');
        $_SESSION['log_' . $class . 'success'] = $this->success_log[$class];
    }

    /**
     *
     * @param string $class
     */
    public function display_error_log($class = '')
    {
        global $engine;
        $html_error = '';
        if (isset($_SESSION['log_' . $class . 'error'])) {
            $properties = $_SESSION['log_' . $class . 'error'];
            if (count($properties) > 0 && $properties !== false) {
                $errors = implode("<br />", $properties);
                ob_start();
                include $engine->path . 'pages/log/html/error.php';
                $html = ob_get_contents();
                ob_end_clean();
                $html_error = preg_replace("/<scms:error>/", $errors, $html);
            }
            // Destroy it
            unset($_SESSION['log_' . $class . 'error']);
        }
        // 
        return $html_error;
    }

    /**
     *
     * @param string $class
     */
    public function display_success_log($class = '')
    {
        global $engine;
        $html_success = '';
        if (isset($_SESSION['log_' . $class . 'success'])) {
            // 
            $properties = $_SESSION['log_' . $class . 'success'];
            if (count($properties) > 0 && $properties !== false) {
                $success = implode(", ", $properties);
                ob_start();
                include $engine->path . 'pages/log/html/success.php';
                $html = ob_get_contents();
                ob_end_clean();
                $html_success = preg_replace("/<scms:success>/", $success, $html);
                //return $html_success;
            }
            // Destroy it
            unset($_SESSION['log_' . $class . 'success']);
        }
        // return
        return $html_success;
    }

    /**
     * Add new hit!
     * @param string $page
     * @param int $rel_id
     */
    public function add_hit($page = '', $rel_id = 0)
    {
        global $engine;
        # Check if hit exists in db
        $query = "SELECT id, hits FROM " . $this->table_hits . " WHERE rel_id=" . $rel_id . " AND page='" . $page . "'";
        $engine->dbase->query($query);
        $row = $engine->dbase->rows;
        $hits = 1;
        if (count($row) > 0) {
            $hits = $row[0]['hits'] + 1;
            $query = "UPDATE " . $this->table_hits . " SET hits=" . $hits . " WHERE id=" . $row[0]['hits'] . " LIMIT 1;";
        } else {
            $query = "INSERT INTO " . $this->table_hits . " (rel_id, page, hits) VALUES (" . $rel_id . ",'" . $page . "', " . $hits . ")";
        }
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete hits
     * @param string $page
     * @param int $rel_id
     */
    public function delete_hits($rel_id = 0, $rel_page = '')
    {
        global $engine;
        if ($id == NULL) {
            return false;
            exit();
        }
        if (is_array($id) && count($id) > 0) {
            $this->rel_id = implode(",", $id);
        } else {
            $this->rel_id = $id;
        }
        $query = "DELETE FROM $this->table WHERE
                rel_id=" . $this->rel_id . " AND page LIKE '" . $rel_page . "' LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $page
     * @param string $error
     * @param string $type
     */
    public function add_error($page, $error, $type = null)
    {
        if ($type !== null) {
            $this->error_log[$page][$type][] = $error;
        } else {
            $this->error_log[$page][] = $error;
        }
    }

    /**
     * Get error
     * @param string $page
     * @param string $type
     * @return boolean|multitype:
     */
    public function get_error($page, $type = null)
    {
        if (!isset($this->error_log[$page])) {
            return false;
        } else {
            if ($type !== null) {
                return $this->error_log[$page][$type];
            } else {
                return $this->error_log[$page];
            }
        }
    }

    /**
     *
     * @param string $page
     * @param string $type
     * @return boolean|multitype:
     */
    public function print_error($page, $type = null)
    {
        if (!isset($this->error_log[$page])) {
            return false;
        } else {
            if ($type !== null) {
                return $this->error_log[$page][$type];
            } else {
                return $this->error_log[$page];
            }
        }
    }
}

?>