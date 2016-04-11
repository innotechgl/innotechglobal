<?php

class friendship_class extends util_class
{
    private $user_one = 0;
    private $user_two = 0;
    private $table_requests = '';

    // Consts for user connenctions
    const REQUESTED = 'REQUESTED';
    const CONNECTED = 'CONNECTED';
    const WAITING = 'WAITING';
    const DISCONECTED = 'DISCONECTED';

    private $status_types = array('requested', '');

    public function __construct()
    {
        $this->table = 'friendship';
        $this->table_requests = 'friendship_requests';
        $this->page = 'friendship';
    }

    /**
     * Connect to user
     * @param int $user_id
     */
    public function make_request($user_id)
    {
        global $engine;
        $this->user_one = (int)$engine->users->get_id();
        $this->user_two = (int)$engine->security->get_val($user_id);
        $query = "INSERT INTO " . $this->table_requests . " (user_one,user_two,created,creator) VALUES (" . $this->user_one . "," . $this->user_two . ", NOW(),'" . $engine->users->get_id() . "')";
        $engine->dbase->insertQuery($query);
    }

    /**
     * Connect to user
     * @param int $user_id
     */
    public function connect($user_id)
    {
        global $engine;
        $this->user_one = (int)$engine->users->get_id();
        $this->user_two = (int)$engine->security->get_val($user_id);
        $query = "INSERT INTO " . $this->table . " SELECT * FROM " . $this->table_requests . ' WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ') AND creator!=' . $engine->users->get_id();
        if ($engine->dbase->insertQuery($query)) {
            $this->remove_request($user_id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if users are connected
     * @param int $id_one
     * @param int $id_two
     *
     */
    public function are_conected($id_one, $id_two)
    {
        global $engine;
        $this->user_one = (int)$engine->security->get_val($id_one);
        $this->user_two = (int)$engine->security->get_val($id_two);
        $connection = $this->get_array(false, 'COUNT(*) as counted', 'WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ')');
        return $connection[0]['counted'];
    }

    /**
     * Check if users are waiting friendship
     * @param int $id_one
     * @param int $id_two
     *
     */
    public function waiting_friendship($id_one, $id_two)
    {
        global $engine;
        $this->user_one = (int)$engine->security->get_val($id_one);
        $this->user_two = (int)$engine->security->get_val($id_two);
        $connection = $this->get_array(false, 'COUNT(*) as counted', 'WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ') AND creator=' . $engine->users->get_id(), 'id', 'ASC', $this->table_requests);
        return $connection[0]['counted'];
    }

    /**
     * Check if users are waiting friendship
     * @param int $id_one
     * @param int $id_two
     *
     */
    public function requested_friendship($id_one, $id_two)
    {
        global $engine;
        $this->user_one = (int)$engine->security->get_val($id_one);
        $this->user_two = (int)$engine->security->get_val($id_two);
        $connection = $this->get_array(false, 'COUNT(*) as counted', 'WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ') AND creator!=' . $engine->users->get_id(), 'id', 'ASC', $this->table_requests);
        return $connection[0]['counted'];
    }

    /**
     * @param int $user_id
     * @return array
     *
     */
    public function get_connections($user_id)
    {
        global $engine;
        $this->user_one = (int)$engine->security->get_val($user_id);
        $connections = $this->get_array(false, '*', 'WHERE user_one=' . $this->user_one . ' OR user_two=' . $this->user_one);
        $users = array();
        foreach ($connections as $key_u => $val_u) {
            if ($val_u['user_one'] == $this->user_one) {
                $users[] = $val_u['user_two'];
            } else {
                $users[] = $val_u['user_one'];
            }
        }
        return $users;
    }

    /**
     * @param int $user_id
     * @return array
     *
     */
    public function get_number_of_connections($user_id)
    {
        $this->user_one = (int)$engine->security->get_val($user_id);
        $connections = $this->get_array(false, 'COUNT(*) as num_of', 'WHERE user_one=' . $this->user_one . ' OR user_two=' . $this->user_one);
        return $connections[0]['num_of'];
    }

    /**
     * @param int $user_id
     * @param return bool
     */
    public function remove_connection($user_id)
    {
        global $engine;
        $this->user_one = (int)$engine->users->get_id();
        $this->user_two = (int)$engine->security->get_val($user_id);
        $query = 'DELETE FROM ' . $this->table . ' WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ') LIMIT 1;';
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes requests
     * @param int $user_id
     */
    public function remove_request($user_id)
    {
        global $engine;
        $this->user_one = (int)$engine->users->get_id();
        $this->user_two = (int)$engine->security->get_val($user_id);
        $query = 'DELETE FROM ' . $this->table_requests . ' WHERE (user_one=' . $this->user_one . ' AND user_two=' . $this->user_two . ') OR (user_one=' . $this->user_two . ' AND user_two=' . $this->user_one . ') LIMIT 1;';
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets status of two users
     * @param int $user_id
     *
     */
    public function get_user_status($user_id)
    {
        global $engine;
        if (self::waiting_friendship($engine->users->get_id(), $user_id)) {
            return self::WAITING;
        } else if (self::requested_friendship($engine->users->get_id(), $user_id)) {
            return self::REQUESTED;
        } else if ($user_id !== $engine->users->get_id() && !self::are_conected($engine->users->get_id(), $user_id)) {
            return self::DISCONECTED;
        } else if ($user_id !== $engine->users->get_id()) {
            return self::CONNECTED;
        }
    }
}

?>