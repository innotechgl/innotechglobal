<?php
require_once $engine->path . "core/user_groups/classes/user_group_item.php";
require_once $engine->path . "core/user_groups/classes/user_group_items.php";

/**
 * Class user_groups
 */
class user_groups
{

    const GROUP_TYPE_GUEST = 0;
    const GROUP_TYPE_STANDARD = 1;
    const GROUP_TYPE_MODERATOR = 2;
    const GROUP_TYPE_ADMINISTRATOR = 3;
    public $types = array('standard', 'moderator', 'administrator');
    public $lang = '';
    protected $engine;
    protected $table = 'user_groups';
    protected $table_rel_users_groups = 'rel_user_group';
    protected $page = 'user_groups';
    protected $user_groups_array = array();

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
        include $this->engine->path . 'language/core/user_groups/user_groups_lat.php';
        $this->lang = new user_groups_language();
    }

    public function getPageName()
    {
        return $this->page;
    }

    /***
     * @param bool $addopt
     * @param string $what_to_get
     * @param string $filter_params
     * @param string $order_by
     * @param string $order_direction
     * @return array
     */
    public function get_user_groups($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        $query = "SELECT " . $what_to_get . " FROM " . $this->table . " " . $filter_params . " ORDER BY " . $order_by . " " . $order_direction;
        $this->engine->dbase->query($query, $addopt, true);
        return $this->engine->dbase->rows;
    }

    public function create()
    {
        $vars = array();
        $requested_vars = array(
            "name",
            "type",
            "description"
        );
        $vars = $this->engine->security->get_vals($requested_vars);
        print_r($_POST);
        $this->name = $vars['name'];
        $this->type = $vars['type'];
        $this->description = $vars['description'];
        $query = "INSERT INTO " . $this->table . "(name,type,description,created) VALUES ('" . $this->name . "','" . $this->type . "','" . $this->description . "', NOW())";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $vars = array();
        $this->id = $this->engine->security->get_vals(array("id" => 0), "GET");
        $requested_vars = array(
            "name" => "",
            "type" => "standard",
            "description" => ""
        );
        $vars = $this->engine->security->get_vals(array($requested_vars), "POST");
        $this->name = $vars['name'];
        $this->type = $vars['type'];
        $this->description = $vars['description'];
        $query = "UPDATE " . $this->table . " SET name='" . $this->name . "',
        type='" . $this->type . "',
        description='" . $this->description . "'
        WHERE id='" . $this->id . "' LIMIT 1;
        ";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $arr = array();
        $this->id = $this->engine->security->get_vals(array("id" => 0), "GET");
        $query = "SELECT * FROM " . $this->table . " WHERE id=" . $this->id . " LIMIT 0,1;";
        $this->engine->dbase->query($query);
        $arr = $this->engine->dbase->rows;
        include $this->engine->path . '/core/user_groups/html/form.php';
    }

    public function delete($id = NULL)
    {
        if ($id == NULL) {
            return false;
            exit();
        }
        if (is_array($id) && count($id) > 0) {
            $this->id = implode(",", $id);
        } else {
            $this->id = $id;
        }
        $query = "DELETE FROM " . $this->table . " WHERE id IN (" . $this->id . ");";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param mixed $id
     * @name Deactivate user account
     *
     */
    public function activate_deactivate($id = NULL, $active = 0)
    {
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=" . $active . " WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @param int $id_group
     * @return bool
     */
    public function add_user_to_group($id, $id_group)
    {
        $insert = '';
        // Check type of $id
        if (is_array($id)) {
            foreach ($id as $key => $val) {
                $insert_array[] = "($val,$id_group)";
            }
            $insert = implode(",", $insert_array);
        } else {
            $insert = "($id,$id_group)";
        }
        $query = "INSERT IGNORE INTO " . $this->table_rel_users_groups . " (id_rel_user, id_rel_group) VALUES " . $insert;
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @param int $id_group
     * @return bool
     */
    public function remove_user_from_group($id, $id_group)
    {
        // Set update limit
        $count = 0;
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "DELETE FROM " . $this->table_rel_users_groups . " WHERE id_rel_user IN (" . $this->id . ") AND id_rel_group=" . $id_group . " LIMIT " . $count . ";";
        if ($this->engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id_user
     * @param int $id_group
     * @return bool
     */
    public function check_user_existance_in_group($id_user, $id_group)
    {
        $query = "SELECT COUNT(*) as `number_of_users` FROM " . $this->table_rel_users_groups . " WHERE id_rel_user="
            . $id_user . " AND id_group=" . $id_group;
        $this->engine->dbase->query($query);
        if ($this->engine->dbase->rows[0]['number_of_users'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $userID
     * @return bool
     */
    public function checkUserBelongsToAdminGroup($userID)
    {
        $groups = $this->get_groups_related_to_user($userID);
        foreach ($groups as $val) {
            $val = new user_group_item();
            if ($val->get_type() == user_groups::GROUP_TYPE_ADMINISTRATOR) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $userID
     * @return user_group_items
     */
    public function get_groups_related_to_user($userID)
    {
        $userGroups = new user_group_items;
        //$query = "CALL getUserGroups(".(int)$userID.");";
        $query = "CALL getUserGroups(" . (int)$userID . ");";
        $this->engine->dbase->callProcedure($query);
        if (count($this->engine->dbase->rows) > 0) {
            foreach ($this->engine->dbase->rows as $key => $val) {
                $userGroup = new user_group_item();
                $userGroup->setID($val["id"]);
                $userGroup->setName($val["name"]);
                $userGroup->set_active($val["active"]);
                $userGroup->set_type($val["type"]);
                $userGroups->addGroup($userGroup);
            }
        } else {
            $userGroup = new user_group_item();
            $userGroup->setID(0);
            $userGroup->setName("Guest");
            $userGroup->set_active(1);
            $userGroup->set_type(0);
            $userGroups->addGroup($userGroup);
        }
        return $userGroups;
    }

    /**
     *
     * @global <type> $engine
     * @param <type> $group_ids
     * @return <type>
     */
    public function get_users_related_to_groups($group_ids = array())
    {
        $query = "SELECT id_rel_user, id_rel_groups FROM " . $this->table_rel_users_groups . " WHERE id_rel_groups IN (" . implode(",", $group_ids) . ")";
        if ($this->engine->dbase->query($query)) {
            return $this->engine->dbase->rows;
        } else {
            return false;
        }
    }

    /**
     *
     * @name Get groups related to users
     * @param array $user_ids
     * @return array
     */
    public function get_groups_related_to_users($user_ids = array())
    {
        if (count($user_ids) <= 0) {
            return false;
        }
        $query = "SELECT id_rel_user, id_rel_group FROM " . $this->table_rel_users_groups . " WHERE id_rel_user IN (" . implode(",", $user_ids) . ")";
        $this->engine->dbase->query($query);
        return $this->engine->dbase->rows;
    }

    /**
     * @param array $user_groups
     * @todo zavrsiti!
     */
    public function check_user_belong_to_groups($user_groups = array())
    {
        $query_part = array();
        foreach ($user_groups as $key => $val) {
            $query_part[] = ' name LIKE "' . $val[''] . '" ';
        }
        $query = "SELECT ";
    }

    /**
     * @param $name
     * @return array
     */
    public function get_users_by_group_name($name)
    {
        $query = "SELECT id_rel_user AS user_id FROM user_groups LEFT JOIN (rel_user_group)
                  ON (rel_user_group.id_rel_group=user_groups.id) WHERE user_groups.name LIKE '" . $name . "';";
        $this->engine->dbase->query($query);
        $rows = $this->engine->dbase->rows;
        $users = array();
        foreach ($rows as $key => $val) {
            $users[] = $val['user_id'];
        }
        return $users;
    }
}

?>