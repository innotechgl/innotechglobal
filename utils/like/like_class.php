<?php

class like_class extends util_class
{
    public $rel_page = '';
    public $rel_id = '';
    public $user_id = 0;
    public $rating_like = 0;
    public $rating_dislike = 0;

    public $table = '`like`';

    /**
     * Add new like
     */
    public function add_like($rel_page, $rel_id, $rating_like, $rating_dislike, $user_id)
    {
        global $engine;
        $this->rel_page = $engine->security->get_val($rel_page);
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->rating_like = $engine->security->get_val($rating_like);
        $this->rating_dislike = $engine->security->get_val($rating_dislike);
        $this->user_id = $engine->security->get_val($user_id);
        // DELETE
        $query = "DELETE FROM $this->table WHERE rel_id=" . $this->rel_id . " AND user_id=" . $this->user_id . " AND rel_page='" . $this->rel_page . "' LIMIT 1;";
        $engine->dbase->insertQuery($query);
        // ADD
        $query = "INSERT INTO $this->table (rel_page, rel_id, rating_like, rating_dislike,user_id) VALUES ('" . $this->rel_page . "', " . $this->rel_id . ", " . $rating_like . ", " . $rating_dislike . ", " . $this->user_id . ");";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete likes
     */
    public function delete_likes($rel_page, $rel_id)
    {
        global $engine;
        $this->rel_page = $engine->security->get_val($rel_page);
        $this->rel_id = $engine->security->get_val($rel_id);
        $query = "DELETE FROM $this->table WHERE rel_page='" . $rel_page . "' AND rel_id=" . $rel_id;
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Display likes
     * @param string $rel_page
     * @param int $rel_id
     */
    public function display_likes($rel_page = '', $rel_id = 0)
    {
        global $engine;
        // Count likes
        $this->count($rel_page, $rel_id);
        // Load view
        $this->load_html('view.php');
    }

    public function count($rel_page, $rel_id)
    {
        global $engine;
        $this->rel_page = $engine->security->get_val($rel_page);
        $this->rel_id = $engine->security->get_val($rel_id);
        $likes = $this->get_array(false, "rating_like, rating_dislike", "WHERE rel_page LIKE '" . $this->rel_page . "' AND rel_id='" . $this->rel_id . "'");
        $this->rating_like = 0;
        $this->rating_dislike = 0;
        foreach ($likes as $key_l => $val_l) {
            $this->rating_like = $this->rating_like + $val_l['rating_like'];
            $this->rating_dislike = $this->rating_dislike + $val_l['rating_dislike'];
        }
    }

    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . "
                  FROM " . $this->table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }
}

?>