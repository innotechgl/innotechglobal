<?php

class userGroupAccess_item_class
{

    protected $id;
    protected $page_rel;
    protected $task;
    protected $type;

    protected $table = 'user_access';
    protected $roles;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getPageRel()
    {
        return $this->page_rel;
    }

    public function setPageRel($page_rel)
    {
        $this->page_rel = (string)$page_rel;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function addAccess()
    {
        global $engine;
        $query = "INSERT INTO " . $this->table . "(page_rel, task, type) VALUES ('" . $this->page_rel . "', " . $this->task . ", '" . $this->type . "')";
        $this->id = $engine->dbase->insertQuery($query);
        return $this->id;
    }

    /**
     * delete user role
     */
    public function deleteAccess()
    {
        global $engine;
        $query = "DELETE FROM " . $this->table . " WHERE id=" . $this->id . " LIMIT 1;";
        $result = $engine->dbase->insertQuery($query);
        return $result;
    }

    public function copyAccess($previous_id_roll, $new_id_roll)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table . " WHERE id=" . $previous_id_roll;
        $engine->dbase->query($query);
        $row = $engine->dbase->rows[0];
        //
        $this->task = $row['task'];
        $this->page_rel = $row['page_rel'];
        $this->type = $row['type'];
        // Add
        $this->addRole();
    }

    /**
     */
    public function getAccessByIds($ids)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table . " WHERE id IN (" . implode(",", $ids) . ")";
        $engine->dbase->query($query);
        return $engine->dbase->rows;
    }

    public function writeAccessForRoles($array)
    {
        global $engine;
        foreach ($array as $key => $val) {
            $this->setPageRel($val['rel_page']);
            $this->setTask($val['task']);
            $this->setType($val['type']);
            $query_add[] = "('" . $this->page_rel . "', " . $this->task . ", '" . $this->type . "')";
        }
        if (count($query_add) > 0) {
            $query = "INSERT INTO " . $this->table . "(page_rel, task, type) VALUES " . implode(",", $query_add);
            $this->id = $engine->dbase->insertQuery($query);
        }
        return $this->id;
    }
}

?>