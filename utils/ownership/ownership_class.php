<?php

class ownership_class extends util_class
{
    public $name = '';
    public $id = 0;
    public $rel_id = 0;
    public $group_id = 0;
    public $user_id = 0;
    public $rel_page = '';

    public $groups = array();
    public $users = array();
    public $tasks = array();

    public $table = '';

    public function __construct()
    {
        $this->table = 'ownership';
    }

    /**
     *
     * Set util configuration
     *
     */
    public function set_configuration($config)
    {
        $this->configuration = $config;
    }

    /**
     *
     * Set id of util
     * @param int $id
     *
     */
    public function set_util_id($id)
    {
        $this->util_id = $id;
    }

    /**
     *
     * Get util ID
     * @return int
     */
    public function get_util_id()
    {
        return $this->util_id;
    }

    /**
     *
     * Set real name of util
     * @param string $name
     *
     */
    public function set_real_name($name = '')
    {
        $this->real_name = $name;
    }

    /**
     *
     * Load index.php of util and show it!
     *
     */
    public function show_util()
    {
        global $engine;
        // Include file
        $filename = $engine->path . 'utils/' . $this->real_name . '/index.php';

        // Check existance of file
        if (file_exists($filename)) {
            // include it
            include $filename;
        }
        else {
            // Write log
            $engine->log->write($engine->users->get_name(), 'ERROR', 'File doesn\'t exists! ' . $filename, 0);
        }
    }

    /**
     * Load js
     */
    public function load_js($string)
    {
        global $engine;
        $template_file = $engine->path . $engine->settings->general->template . "/utils/" . $this->real_name . "/js/" . $string;
        $standard_file = $engine->path . "utils/" . $this->real_name . "/js/" . $string;
        if (file_exists($template_file)) {
            $file = $template_file;
        } else {
            $file = $standard_file;
        }
        echo "<script type=\"text/javascript\" src=\"/" . $file . "\"></script>";
    }

    /**
     *
     */
    public function load_html($string = 'view.php')
    {
        global $engine;
        $args = func_get_args();
        $template_file = $engine->path . $engine->settings->general->template . "/utils/" . $this->real_name . "/html/" . $string;
        $standard_file = $engine->path . "utils/" . $this->real_name . "/html/" . $string;
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            include $standard_file;
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

    public function make_group_ownership($name, $rel_id, $group_id)
    {
        global $engine;
        // Delete if previous exists
        $this->delete_group_ownership($name, $rel_id, $group_id);
        $this->name = $engine->security->get_val($name);
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->group_id = $engine->security->get_val($group_id);
        $query = "INSERT INTO " . $this->table . " (name, id, group_id) VALUES ('" . $this->name . "', " . $this->rel_id . ", " . $this->group_id . ")";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete ownership of group
     *
     */
    public function delete_group_ownership($name, $id, $group_id)
    {
        global $engine;
        $query = "DELETE FROM " . $this->table . " WHERE group_id=" . $this->group_id . " AND id=" . $this->rel_id . " AND name LIKE '" . $this->name . "' LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function make_user_ownership($name, $rel_id, $user_id)
    {
        global $engine;
        // Delete if previous exists
        $this->delete_user_ownership($name, $rel_id, $user_id);
        $this->name = $engine->security->get_val($name);
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->user_id = $engine->security->get_val($user_id);
        $query = "INSERT INTO " . $this->table . " (name, id, user_id) VALUES ('" . $this->name . "', " . $this->rel_id . ", " . $this->user_id . ")";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete ownership of group
     *
     */
    public function delete_user_ownership($name, $id, $user_id)
    {
        global $engine;
        $query = "DELETE FROM " . $this->table . " WHERE user_id=" . $this->user_id . " AND id=" . $this->rel_id . " AND name LIKE '" . $this->name . "' LIMIT 1;";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check user ownership
     */
    public function check_user_ownership($name, $id, $user_id)
    {
        global $engine;
        $this->name = $engine->security->get_val($name);
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->user_id = $engine->security->get_val($user_id);
        $query = "SELECT COUNT(*) as num_of FROM " . $this->table . " WHERE user_id=" . $this->user_id . " AND id=" . $this->rel_id . " AND name LIKE '" . $this->name . "';";
        $engine->dbase->query($query);
        $rows = $engine->dbase->rows;
        if ($rows[0]['num_of'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check group ownership
     */
    public function check_group_ownership($name, $id, $group_id)
    {
        global $engine;
        $this->name = $engine->security->get_val($name);
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->group_id = $engine->security->get_val($group_id);
        $query = "SELECT COUNT(*) as num_of FROM " . $this->table . " WHERE group_id=" . $this->group_id . " AND id=" . $this->rel_id . " AND name LIKE '" . $this->name . "';";
        $engine->dbase->query($query);
        $rows = $engine->dbase->rows;
        if ($rows[0]['num_of'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>