<?php

class comments_class extends categorisation
{
    public $real_name = '';
public $rel_id = 0;
    public $rel_name = '';  # related item id
    public $comment = ''; # related page name
        public $name = ''; # comment
    public $mail = '';
    public $configuration = array();
public $registered_only = true;
    protected $util_id = 0;
    private $count = 0; # check for registered user

    public function __construct()
    {
        $this->table = 'comments';
        $this->page = 'comments';
        global $engine;
        $engine = $engine;
        // INCLUDE LANGUAGE PACK
        include $engine->path . 'language/pages/' . $this->page . '/' . $this->page . '_lat.php';
        $this->lang = new comments_language();
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
     * Get util ID
     * @return int
     */
    public function get_util_id()
    {
        return $this->util_id;
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
        } else {
            // Write log
            $engine->log->write($engine->users->get_name(), 'ERROR', 'File doesn\'t exists! ' . $filename, 0);
        }
    }

    public function add($comment = '', $parent_id = 0, $rel_page = '', $rel_id = 0)
    {
        global $engine;
        # Get first data
        $this->comment = (string)$comment;
        # get parent id
        $this->parent_id = (int)$parent_id;
        # get rel info
        $this->rel_id = (int)$rel_id;
        $this->rel_name = (string)$rel_page;
        // TO DO: Add default value from SETTINGS
        $engine->active = 1;
        $query_array = array();
        // Set creator
        $this->creator = (int)$engine->users->get_id();
        # set query
        $query = "INSERT INTO " . $this->table . "
            (rel_id, rel_name, name, comment, parent_id, active, created, creator) VALUES
            ('" . $this->rel_id . "','" . $this->rel_name . "', '" . $this->name . "',
             '" . $this->comment . "', " . $this->parent_id . ",
             '" . $this->active . "', NOW(), '" . $this->creator . "');";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
        continue;
    }

    /**
     *
     * Delete all comments related to page and id
     *
     * @param string $rel_page
     * @param int $rel_id
     * @return bool
     *
     */
    public function delete_all_comments($rel_page, $rel_id)
    {
        /**
         * Load global
         *
         */
        global $engine;
        // Set rels
        $this->rel_page = $engine->security->get_val($rel_page);
        $this->rel_id = $engine->security->get_val($rel_id);
        $query = "DELETE FROM " . $this->table . " WHERE rel_page LIKE '" . $this->rel_page . "' AND rel_id=" . $this->rel_id . ";";
        $result = $engine->dbase->insertQuery($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @name delete comments
     * @param array /int $id
     * @return bool
     */
    public function delete($id = null)
    {
        global $engine;
        if ($id == NULL) {
            return false;
            exit();
        }
        if (is_array($id) && count($id) > 0) {
            $this->id = implode(",", $id);
        } else {
            $this->id = $id;
        }
        $query = "DELETE FROM $this->table WHERE
                id=" . $this->id . " LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @name Show comments
     * @param string $rel_name
     * @param string $rel_id
     */
    public function show_comments($rel_name = null, $rel_id = null)
    {
        $this->count++;
        // Get comments
        $comments = $this->get_comments($rel_name, $rel_id);
        if (count($comments) > 0) {
            $users = $this->get_users_of_comments($rel_name, $rel_id);
        }
        include 'utils/comments/html/list.php';
    }

    /**
     * @name Get related comments
     * @param string $rel_name
     * @param string $rel_id
     */
    public function get_comments($rel_name = null, $rel_id = null)
    {
        $comments = $this->get_array(false, '*', 'WHERE rel_name LIKE "' . $rel_name . '" AND rel_id LIKE "' . $rel_id . '"', 'created', 'DESC');
        return $comments;
    }

    /**
     * Get users who commented
     *
     */
    public function get_users_of_comments($rel_name = null, $rel_id = null)
    {
        global $engine;
        $users_to_get = array();
        $engine->load_page('user_profile');
        $creators = $this->get_array(false, 'creator', 'WHERE rel_name LIKE "' . $rel_name . '" AND rel_id LIKE "' . $rel_id . '" GROUP BY creator', 'created', 'DESC');
        foreach ($creators as $key_u => $val_u) {
            $users_to_get[] = $val_u['creator'];
        }
        $users_raw = $engine->user_profile->get_array(false, '*', 'WHERE user_id IN (' . implode(",", $users_to_get) . ')');
        foreach ($users_raw as $key_u => $val_u) {
            $users[$val_u['user_id']]['avatar'] = $val_u['avatar'];
            $users[$val_u['user_id']]['first_name'] = $val_u['first_name'];
            $users[$val_u['user_id']]['last_name'] = $val_u['last_name'];
        }
        return $users;
    }

    public function get_count()
    {
        return $this->count;
    }

    /**
     * Show form
     */
    public function show_form()
    {
        include 'utils/comments/html/form.php';
    }
}

?>