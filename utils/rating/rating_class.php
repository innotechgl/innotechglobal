<?php

class rating_class extends util_class
{

    public $table = 'rating';
    public $table_voters = 'rating_voters';

    protected $rel_page = '';
    protected $rel_id = 0;
    protected $rel_rate = '';
    protected $rel_ip = '0.0.0.0';
    protected $rel_user_id = 0;

    public function __construct()
    {
    }

    public function rate_it($rate = 1, $type = 'standard')
    {
        global $engine;
        $this->set_rel_rate($rate);
        # Check if hit exists in db
        $query = "SELECT id, hits FROM " . $this->table . " WHERE rel_id='" . $this->get_rel_id() . "' AND type LIKE '" . $type . "' AND rel_page='" . $this->get_rel_page() . "' AND rate=" . $this->get_rel_rate();
        $engine->dbase->query($query);
        $row = $engine->dbase->rows;
        $hits = 1;
        $ip_info = $engine->security->get_ip();
        // Check ih we have valid IP
        if ($ip_info == sCMS_security::INVALID) {
            exit();
            return false;
        }
        $query_voter = "INSERT INTO " . $this->table_voters .
            " (`rel_id`, `rel_page`, `rel_user_id`, `rel_ip`, `date`, `type`)
                    VALUES (" . $this->get_rel_id() . ",'" . $this->get_rel_page()
            . "', " . (int)$engine->users->get_id() . ", '" . $ip_info['ip'] . "', NOW(), '" . $type . "')";
        if (count($row) > 0) {
            $hits = $row[0]['hits'] + 1;
            $query = "UPDATE " . $this->table . " SET hits=" . $hits . " WHERE id=" . $row[0]['id'] . " AND type LIKE '" . $type . "' LIMIT 1;";
        } else {
            $query = "INSERT INTO " . $this->table . " (`rel_id`, `rel_page`, `hits`, `rate`, `type`) VALUES (" . $this->get_rel_id() . ",'" . $this->get_rel_page() . "', " . $hits . ", " . $this->get_rel_rate() . ", '" . $type . "')";
        }
        if ($engine->dbase->insertQuery($query)) {
            // add voter
            if ($engine->dbase->insertQuery($query_voter)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_rel_id()
    {
        return $this->rel_id;
    }

    public function set_rel_id($val = 0)
    {
        $this->rel_id = (int)$val;
    }

    public function get_rel_page()
    {
        return $this->rel_page;
    }

    public function set_rel_page($val = '')
    {
        $this->rel_page = (string)$val;
    }

    public function get_rel_rate()
    {
        return $this->rel_rate;
    }

    public function set_rel_rate($val = 1)
    {
        $this->rel_rate = (int)$val;
    }

    /*
     * @param array $rel_id
     * 
     */

    public function delete_rate($rel_id = 0, $rel_page = '', $type = 'standard')
    {
        global $engine;
        // Set vals
        $this->set_rel_id($rel_id);
        $this->set_rel_page($rel_page);
        $query = "DELETE FROM " . $this->table . " WHERE rel_id=" . $this->get_rel_id() . " AND rel_page LIKE " . $this->get_rel_page() . " AND type LIKE '" . $type . "' LIMIT 5;";
        $result = $engine->dbase->insertQuery($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * display rating
     */
    public function render($rel_id, $rel_page)
    {
        $this->set_rel_page($rel_page);
        $rating = $this->get_rates(array($rel_id));
        if (isset($rating[$rel_id])) {
            $rating = $rating[$rel_id];
        } else {
            $rating = 0;
        }
        $this->set_rel_id($rel_id);
        include 'utils/rating/html/view.php';
    }

    public function get_rates($rel_id = array(), $type = 'standard')
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table . " WHERE rel_page LIKE '" . $this->get_rel_page() . "' AND rel_id IN (" . implode(",", $rel_id) . ") AND type LIKE '" . $type . "'";
        $engine->dbase->query($query);
        $rows = $engine->dbase->rows;
        // Rates
        $rates = array();
        $rating = array();
        foreach ($rows as $val_r) {
            $rates[$val_r['rel_id']][$val_r['rate']] = $val_r['hits'];
            $rating[$val_r['rel_id']] = $this->calc_rate($rates[$val_r['rel_id']]);
        }
        foreach ($rel_id as $val) {
            if (!isset($rating[$val])) {
                $rating[$val] = -1;
            }
        }
        unset($rates);
        return $rating;
    }

    public function calc_rate($rates = array())
    {
        $sum = 0;
        $calc = 0;
        foreach ($rates as $key_r => $val_r) {
            $calc += $key_r * $val_r;
            $sum += $val_r;
        }
        $rating = $calc / $sum;
        return (int)$rating;
    }

    /**
     *
     */
    public function check_voter($type = 'standard')
    {
        global $engine;
        $ip_info = $engine->security->get_ip();
        // Check ih we have valid IP
        if ($ip_info == sCMS_security::INVALID) {
            exit();
            return false;
        }
        $query = "SELECT COUNT(*) as num_voter FROM " . $this->table_voters . " WHERE rel_id=" . $this->get_rel_id() . " AND rel_page LIKE '" . $this->get_rel_page() . "' AND (rel_ip LIKE'" . $ip_info['ip'] . "' OR rel_user_id=" . $engine->users->get_id() . ") AND type LIKE '" . $type . "'";
        $result = $engine->dbase->query($query);
        $row = $engine->dbase->rows;
        IF ($row[0]['num_voter'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get best rated
     * @param string $rel_page
     * @param int $limit
     */
    public function get_best_rated($rel_page, $limit, $type = 'standard')
    {
        global $engine;
        // Set query
        $query = "SELECT rel_id, SUM(hits) as num_votes, SUM(rate*hits)/SUM(hits) as rates FROM " . $this->table . " WHERE rel_page LIKE '" . $rel_page . "' AND type LIKE '" . $type . "' GROUP BY rel_id HAVING SUM(rate*hits)/COUNT(rate) ORDER BY rates, num_votes DESC";
        $engine->dbase->query($query, true, $limit);
        // Return rows
        return $engine->dbase->rows;
    }
}

?>