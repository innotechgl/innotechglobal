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
    private $levels = array("notice", "warning", "High importance");
    private $dir = 'logs';
    private $engine;

    public function __construct()
    {
        global $engine;
        $this->dir = $engine->path . $this->dir . "/" . date("Y") . "/" . date("m") . "/";
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
        global $engine;
        // Check if there is a need to write log
        $this->settings = $engine->get_setting();
        $arr = (array)$this->settings->log;
        if (!in_array($level, $arr['allowed'])) {
            exit();
        }
        // Create dir if it doesn't exist
        @mkdir($this->dir, 0777, true);
        // Set filename
        $file = date("d") . ".txt";
        // Setup content
        $content = "[level:" . $level . "][date:" . date("Y-m-d h:m:s") . "][user:" . $user . "][log:" . $log . "][atempt_from:" . $_SERVER['REMOTE_ADDR'] . "]\r";
        // Open file for append
        $f = fopen($this->dir . "/" . $file, 'a');
        // Write to file
        fwrite($f, $content, strlen($content));
        // Close file
        fclose($f);
    }

    /**
     * @param string $class
     * @param string $log
     *
     * Adds error to error_log
     */
    public function add_error_log($class = "", $log = "")
    {
        // Add error
        $this->error_log[$class][] = $log;
    }

    /**
     *
     * @param string $class
     * @param string $log
     */
    public function add_success_log($class = "", $log = "")
    {
        // Add success
        $this->success_log[$class][] = $log;
    }

    /**
     *
     * @param string $class
     */
    public function display_error_log($class = '')
    {
        global $engine;
        if (isset($this->error_log[$class])) {
            $errors = implode("<br />", $this->error_log[$class]);
            ob_start();
            include $engine->path . 'pages/log/html/error.php';
            $html = ob_get_contents();
            ob_end_clean();
            $html_error = preg_replace("%<scms:error>%", $errors, $html);
            return $html_error;
        }
    }

    /**
     *
     * @param string $class
     */
    public function display_success_log($class = '')
    {
        global $engine;
        if (isset($this->success_log[$class])) {
            $errors = implode(", ", $this->success_log[$class]);
            ob_start();
            include $engine->path . 'pages/log/html/success.php';
            $html = ob_get_contents();
            ob_end_clean();
            $html_error = preg_replace("%<scms:success>%", $errors, $html);
            return $html_error;
        }
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
}

?>