<?php

class custom_rels
{
    public $id = 0;
    public $rel_id = 0;
    public $rel_page = '';
    public $rel_custom_id = '';
    public $rel_custom_name = '';
    public $no = 0;
    public $modified = '0000-00-00 00:00:00';
    public $table = 'rel_customs';

    /**
     *
     * @param int $rel_id
     * @param string $rel_page
     * @param int $rel_custom_id
     * @param string $rel_custom_name
     * @param int $no
     */
    public function add($rel_id = 0, $rel_page = '', $rel_custom_id = 0, $rel_custom_name = '')
    {
        global $engine;
        # check if there is duplicate
        if ($this->check_for_duplicates($rel_id, $rel_page, $rel_custom_id, $rel_custom_name)) {
            if ($this->update($this->id, $rel_id, $rel_page, $rel_custom_id, $rel_custom_name)) {
                return true;
            } else {
                return false;
            }
        }
        $query = "INSERT INTO " . $this->table . "(rel_id, rel_page, rel_custom_id, rel_custom_name, no, modified) VALUES "
            . "(
                    {$rel_id},
                    '{$rel_page}',
                    {$rel_custom_id},
                    '{$rel_custom_name}',
                    0,
                    NOW()
                    )";
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param int $rel_id
     * @param string $rel_page
     * @param int $rel_custom_id
     * @param string $rel_custom_name
     */
    public function check_for_duplicates($rel_id, $rel_page, $rel_custom_id, $rel_custom_name = '')
    {
        $rows = $this->get_array(false, '*', 'WHERE rel_id=' . $rel_id . ' AND rel_page LIKE "' . $rel_page . '" AND rel_custom_id="' . $rel_custom_id . '" AND rel_custom_name LIKE "' . $rel_custom_name . '"');
        if (count($rows) > 0) {
            $this->id = $rows[0]['id'];
            return true;
        } else {
            return false;
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

    public function update($id, $rel_id = 0, $rel_page = '', $rel_custom_id = 0, $rel_custom_name = '')
    {
        global $engine;
        $query = "UPDATE " . $this->table . " SET
            rel_id={$rel_id},
            rel_page='{$rel_page}',
            rel_custom_id='{$rel_custom_id}',
            rel_custom_name='{$rel_custom_name}',
            modified=NOW() WHERE id=" . $id;
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * DELETE
     */

    public function delete($id = NULL, $rel_page = '')
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
                rel_id=" . $this->rel_id . " AND rel_page LIKE '" . $rel_page . "' LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes all rows using rel_id
     *
     */
    public function delete_all_using_rel_id($rel_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE rel_id=" . $rel_id . " AND rel_page='" . $rel_page . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_all_using_custom_id($custom_id, $rel_page)
    {
        $query = "DELETE FROM " . $this->table . " WHERE custom_id=" . $rel_id . " AND rel_page='" . $rel_page . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_all_using_rel_page($rel_page)
    {
        $query = "DELETE FROM " . $this->table . " WHERE rel_page LIKE '" . $rel_page . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_all_using_rel_custom_name($custom_name)
    {
        $query = "DELETE FROM " . $this->table . " WHERE rel_custom_name LIKE '" . $custom_name . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     */
    public function delete_single($custom_id = null, $rel_id = null, $rel_page = null, $rel_custom_name = null)
    {
        global $engine;
        $params = array();
        // Check custom_id
        $custom_id !== null ? $params[] = 'rel_custom_id=' . $custom_id : FALSE;
        $rel_id !== null ? $params[] = 'rel_id=' . $rel_id : FALSE;
        $rel_page !== null ? $params[] = 'rel_page LIKE "' . $rel_page . '"' : FALSE;
        $rel_custom_name !== null ? $params[] = 'rel_custom_name LIKE "' . $rel_custom_name . '"' : FALSE;
        if (count($params) > 0) {
            $param = implode(" AND ", $params);
            $query = "DELETE FROM " . $this->table . " WHERE " . $param;
            echo $query;
            if ($engine->dbase->insertQuery($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get number of related ids
     * @param int $rel_id
     * @param string $rel_string
     */
    public function get_number_of_rel_ids($rel_id = 0, $rel_page = '')
    {
    }
}

?>