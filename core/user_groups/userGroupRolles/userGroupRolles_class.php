<?php
require_once($engine->path . '/core/user_groups/userGroupAccess/userGroupAccess_class.php');

class userGroupRolles_class
{

    protected $id;
    protected $group_id;
    protected $access_id;
    protected $name;
    protected $description;

    protected $table = 'user_group_rolles';
    protected $rel_table = 'rel_rolles_access';
    protected $rel_table_groups = 'rel_rolls_groups';

    protected $user_rolles = array();

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setGroupId($group_id)
    {
        $this->group_id = (int)$group_id;
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setAccessId($access_id)
    {
        $this->access_id = (int)$access_id;
    }

    public function getAccessId()
    {
        return $this->access_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    protected function _add($access_ids)
    {
        global $engine;
        $query = "INSERT INTO " . $this->table . " (name, description) VALUES ('" . $this->name . "', '" . $this->description . "')";
        $this->id = $engine->dbase->insertQuery($query);
        // Prepare REL Query
        foreach ($access_ids as $key => $val) {
            $query_rel_add[] = "(" . $val . "," . $this->id . ")";
        }
        $query_rel = "INSERT INTO " . $this->rel_table . " (access_id, roll_id) VALUES " . implode(",", $query_rel_add);
        $res = $engine->dbase->insertQuery($query_rel);
        return $res;
    }

    protected function _delete()
    {
        global $engine;
        $query = "DELETE FROM " . $this->table . " WHERE id=" . $this->id;
        $engine->dbase->insertQuery($query);
        $query = "DELETE FROM " . $this->rel_table . " WHERE roll_id=" . $this->id;
        $engine->dbase->insertQuery($query);
    }

    public function add($access_ids)
    {
        $problems = array();
        $error = false;
        if (!is_array($access_ids)) {
            $problems[] = 'no access IDS';
            $error = true;
        }
        if (trim($this->name) == '') {
            $problems[] = 'name must be defined';
            $error = true;
        }
        if (trim($this->description) == '') {
            $problems[] = 'description must be defined';
            $error = true;
        }
        if ($error) {
            return array(false, $problems);
        } else {
            $this->_add($access_ids);
        }
    }

    /**
     * Delete
     */
    public function delete()
    {
        // return problems
        $problems = array();
        if ($this->id > 0) {
            // Call delete function
            $res = $this->_delete();
            return $res;
        } // return error
        else {
            $problems[] = 'no ID';
            return array(false, $problems);
        }
    }

    /**
     * Connect Rolles to groups
     */
    public function connectRollesToGroup($rolles_ids)
    {
        global $engine;
        $query_add = array();
        foreach ($rolles_ids as $key => $val) {
            $query_add[] = "(" . $this->group_id . "," . $val . ")";
        }
        $query = "INSERT INTO " . $this->rel_table_groups . " (group_id, roll_id) VALUES " . implode(",", $query_add);
        $res = $engine->dbase->insertQuery($query);
        return $res;
    }

    public function getRollesConnectedToGroups($groups_ids)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->rel_table_groups . " WHERE group_id IN (" . implode(",", $group_ids) . ")";
        $engine->dbase->query($query, false);
        return $engine->dbase->rows;
    }

    public function getGroupsConnectedToRolles($rolles_ids)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->rel_table_groups . " WHERE roll_id IN (" . implode(",", $rolles_ids) . ")";
        $engine->dbase->query($query, false);
        return $engine->dbase->rows;
    }

    public function getAccessConnectedToRoles($rolles_ids)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->rel_table . " WHERE roll_id IN (" . implode(",", $rolles_ids) . ")";
        $engine->dbase->query($query, false);
        return $engine->dbase->rows;
    }

    /* Read */
    public function readAccessibles($group_ids)
    {
        // Define access
        $user_groups_access = new userGroupAccess_item_class;
        $rolles_raw = $this->getRollesConnectedToGroups($group_ids);
        $roll_ids = array();
        foreach ($rolles_raw as $key => $val) {
            $roll_ids[] = $val['id'];
        }
        $accesses = $this->getAccessConnectedToRoles($roll_ids);
        $prepared_access = array();
        foreach ($prepared_access as $key => $val) {
            $prepared_access[$val['roll_id']][] = $val['access_id'];
        }
        $user_groups_access_accesses = $user_groups_access->getAccessByIds($prepared_access);
        // Go through
        foreach ($rolles_raw as $key_r => $val_r) {
            $this->user_rolles[$key_r]['id'] = $val_r['id'];
            $this->user_rolles[$key_r]['name'] = $val_r['name'];
            $this->user_rolles[$key_r]['description'] = $val_r['description'];
            $this->user_rolles[$key_r]['access'] = array();
            // Go through accesses
            foreach ($user_groups_access_accesses as $key_a => $val_a) {
                if (in_array($val_a['id'], $prepared_access[$val_r['id']])) {
                    $this->user_rolles[$key_r]['access'][$val_a['type']][$val_a['rel_page']][] = $val_a['task'];
                }
            }
        }
    }

    /*
        Check if user has access to acction
    */
    public function isAccessible($rel_page, $task, $type)
    {
        $found = false;
        foreach ($this->user_rolles as $key => $val) {
            if (in_array($task, $val['access'][$type][$rel_page])) {
                $found = true;
                break;
            }
        }
        return $found;
    }

    public function getAllRolles()
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table;
        $engine->dbase->limit = 0;
        $engine->dbase->query($query);
        return $engine->dbase->rows;
    }
}

?>