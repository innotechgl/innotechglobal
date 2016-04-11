<?php

final class places_connector_class extends util_class
{

    public $table = 'places_connector';

    public $id = 0;
    public $place_id = 0;
    public $rel_id = 0;
    public $rel_page = '';

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

    public function add($rel_page, $rel_id, $place_ids = array())
    {
        // Delete connected galleries for renew
        $this->delete($rel_page, $rel_id);
        if (count($place_ids) > 0) {
            foreach ($place_ids as $place_id) {
                // Add them
                $this->add_new($rel_id, $place_id, $rel_page);
            }
        }
    }

    /**
     * @name Delete items
     * @param string $rel_page
     * @param int $rel_id
     *
     */
    public function delete($rel_page, $rel_id)
    {
        global $engine;
        $this->rel_id = (int)$rel_id;
        $this->rel_page = (string)$rel_page;
        // Set query
        $query = "DELETE FROM " . $this->table . " WHERE rel_id=" . $this->rel_id . " AND rel_page LIKE '" . $this->rel_page . "'";
        // Result
        $result = $engine->dbase->insertQuery($query);
        // Set result
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $rel_id
     * @param int $place_id
     * @param string $rel_page
     * @return boolean
     *
     */
    public function add_new($rel_id, $place_id, $rel_page)
    {
        global $engine;
        // Prepare VALS
        $this->rel_id = (int)$rel_id;
        $this->place_id = (int)$place_id;
        $this->rel_page = (string)$rel_page;
        // CREATE QUERY
        $query = 'INSERT INTO ' . $this->table . '(rel_id, place_id, rel_page) VALUES (' . $this->rel_id . ', ' . $this->place_id . ', "' . $this->rel_page . '");';
        // INSERT INTO BASE
        $this->id = $engine->dbase->insertQuery($query);
    }

    /**
     * Check existance
     * @param string $rel_page
     * @param int $rel_id
     */
    public function check_existance($rel_page, $rel_id)
    {
        global $engine;
        $this->rel_id = $engine->security->get_val($rel_id);
        $this->rel_page = $engine->security->get_val($rel_page);
        $query = "SELECT COUNT(*) as num_of FROM " . $this->table . " WHERE rel_id=" . $this->rel_id . " AND rel_page LIKE '" . $this->rel_page . "';";
        $engine->dbase->query($query);
        $rows = $engine->dbase->rows;
        if ($rows[0]['num_of'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function create_connetctions_TEMP()
    {
        global $engine;
        $widgets = $engine->widgets->get_widgets(false, 'id', 'WHERE native_name LIKE "gallery"');
        foreach ($widgets as $val) {
            $engine->widgets->get_settings($val['id']);
            $this->add_new($engine->widgets->settings['written'][1]['value'], $engine->widgets->settings['written'][2]['value'], 'articles');
            //print_r($engine->widgets->settings);
        }
    }
}

?>