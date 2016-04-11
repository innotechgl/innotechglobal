<?php
require_once('core/security/security_items/abstract/security_item.php');

final class security_usergroup_item extends security_item
{

    protected $group_id;
    protected $access_type;
    protected $default_action;
    protected $access;

    /**
     *
     */
    public function __construct()
    {
        $this->table = "rel_group_object_access";
    }

    /**
     * return int
     */
    public function get_group_id()
    {
        return $this->group_id;
    }

    /**
     *
     * @param int $group_id
     */
    public function set_group_id($group_id)
    {
        $this->group_id = (int)$group_id;
    }

    /**
     * Get access value
     */
    public function get_access()
    {
        return $this->access;
    }

    /**
     *
     * @param bool $access
     */
    protected function set_access($access)
    {
        $this->access = $access;
    }

    /**
     * Write access info
     */
    public function write_access_info()
    {
        global $engine;
        $doQuery = false;
        if (!$this->access) {
            if ($this->remove_access_info()) {
                return true;
            } else {
                return false;
            }
        } else {
            $doQuery = true;
            if (!$this->get_group_access_info()) {
                $query = "INSERT INTO (user_id, access_type, object_page, object_id) " . $this->table . " VALUES (" . $this->user_id . "," . $this->access_type . ", " . $this->object_page . "," . $this->object_id . ")";
            }
        }
        if ($doQuery) {
            $doQuery = false;
            if ($engine->dbase->insertQuery($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Remove access info from DB
     *
     */
    public function remove_access_info()
    {
        $query = "DELETE FROM " . $this->table . " WHERE access_type LIKE '" . $this->access_type . "' AND user_id=" . $this->user_id . " AND object_id=" . $this->object_id . " AND object_page LIKE '" . $this->object_page . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param int $group_id
     */
    public function get_group_access_info()
    {
        global $engine;
        $query = "SELECT COUNT(*) AS group_access_num FROM `" . $this->table . "` WHERE access_type LIKE '" . $this->access_type . "' AND group_id=" . $this->group_id . " AND object_id=" . $this->object_id . " AND object_page LIKE '" . $this->object_page . "'";
        $engine->dbase->query($query);
        $row = $engine->dbase->rows;
        if ($row[0]['group_acces_num'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>