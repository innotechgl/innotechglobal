<?php

class report_class extends util_class
{
    private $rel_page = ''; // Rel page
    private $rel_id = ''; // Rel id
    private $description = ''; // Description
    private $types = array('racism' => 'racism', 'hate' => 'hate', 'spam' => 'spam');
    private $type = ''; // Type of report

    public function __construct()
    {
        $this->table = 'reports';
    }

    /*
    * @name Sets relation page
    */
    public function set_rel_page($rel_page)
    {
        global $engine;
        $this->rel_page = $engine->security->get_val($rel_page);
    }

    public function get_types()
    {
        return $this->types;
    }

    /*
    @name Sets related id
    */
    public function set_rel_id($rel_id)
    {
        global $engine;
        $this->rel_id = $engine->security->get_val($rel_id);
    }

    /*
    * @name adds type of report
    */
    public function add_type($type)
    {
        $this->types[$type] = $type;
    }

    /**
     * Add new
     * @param string $description
     * @param string $type
     */
    public function add_report($description, $type)
    {
        global $engine;
        $this->description = $engine->security->get_val($description);
        $this->type = $engine->security->get_val($type);
        $query = "INSERT INTO " . $this->table . " ( rel_page, rel_id, description, type, created, creator ) VALUES ('" . $this->rel_page . "','" . $this->rel_id . "','" . $this->description . "','" . $this->type . "',NOW(),'" . $engine->users->get_id() . "');";
        echo $query;
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get count of reports
     *
     * @param array $rel_id
     *
     */
    public function get_count_of_reports($rel_id = array())
    {
        $counts = array();
        $counts_raw = $this->get_array(false, 'COUNT(*) as counted, rel_id', 'WHERE rel_page LIKE "' . $this->rel_page . '" AND rel_id IN (' . implode(",", $rel_id) . ')');
        //print_r($counts_raw);
        foreach ($rel_id as $key => $val) {
            $counts[$val] = 0;
            foreach ($counts_raw as $key_c => $val_c) {
                if ($val_c['rel_id'] == $val) {
                    $counts[$val] = $val_c['counted'];
                    break;
                }
            }
        }
        return $counts;
    }

    /*
    * @name Show report
    * @param int $rel_id
    * @param string $rel_page
    * @param string $view_type
    */
    public function show_report($rel_id, $rel_page, $view_type = "link_view")
    {
        $this->rel_id = $rel_id;
        $this->rel_page = $rel_page;
        $filename = "utils/report/html/" . $view_type . ".php";
        if (file_exists($filename)) {
            include $filename;
        }
    }
}

?>