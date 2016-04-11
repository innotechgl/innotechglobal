<?php

class locker
{

    protected $id = 0;
    protected $id_rel = '';
    protected $table_rel = '';
    protected $time = '';
    protected $locked_by = '';

    protected $table = 'locker';

    public function __construct()
    {
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = (int)$id;
    }

    public function get_id_rel()
    {
        return $this->rel_id;
    }

    public function set_id_rel($id)
    {
        $this->rel_id = (int)$id;
    }

    public function get_table_rel()
    {
        return $this->table_rel;
    }

    public function set_table_rel($table_rel)
    {
        global $engine;
        $this->table_rel = $engine->security->get_val($table_rel,
            sCMS_security::VAL_STRING);
    }

    public function get_time($format = "d.m.Y h:m:s")
    {
        return date($format, strtotime($this->time));
    }

    public function set_time($time, $format = "d.m.Y h:m:s")
    {
        global $engine;
        $this->time = date($format,
            strtotime($engine->security->get_val($time)));
    }

    public function get_locked_by()
    {
        return $this->locked_by;
    }

    public function set_locked_by($id)
    {
        $this->locked_by = (int)$id;
    }

    public function get_locked()
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table . "
            WHERE table_rel=" . $this->table_rel . ";";
        $engine->dbase->query($query);
        return $engine->dbase->rows;
    }

    /**
     *
     * @global object $engine
     * @param int $id
     * @return bool
     */
    public function check_locked($ids)
    {
        global $engine;
        if (is_array($id)) {
            $ids_ = array();
            foreach ($ids as $val) {
                $ids_[] = (int)$val;
            }
            $imploded_ids = implode(",", $ids_);
            $query = "SELECT COUNT(*) as cnt FROM " . $this->table . "
                WHERE table_rel=" . $this->table_rel . "
                    AND id_rel IN (" . $this->id_rel . ");";
        } else {
        }
        $engine->dbase->query($query);
        if ($engine->dbase->rows[0]['cnt']) {
            return true;
        } else {
            return false;
        }
    }

    public function lock($id = 0)
    {
        global $engine;
        if ($id == 0) {
            exit();
        }
        $this->id_rel = $id;
        $query = "INSERT INTO " . $this->table . " (id_rel, table_rel) VALUES ('" . $this->id_rel . "','" . $this->table_rel . "');";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function unlock($ids = array())
    {
        global $engine;
        if (count($ids) == 0) {
            exit();
        }
        $this->id_rel = implode(",", $ids);
        $query = "DELETE FROM " . $this->table . " WHERE id_rel IN ('" . $this->id_rel . "') AND table_rel='" . $this->table_rel . "';";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function unlockEverything()
    {
        global $engine;
        $query = "DELETE FROM " . $this->table . " WHERE table_rel='" . $this->table_rel . "';";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }
}

?>