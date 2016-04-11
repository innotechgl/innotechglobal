<?php

final class blokada_class extends util_class
{
    public $id = 0;
    public $mb = 0;
    public $status = 0;

    public $id_notified = 0;
    public $user_id_notified = 0;
    public $mb_notified = 0;
    public $account = '';
    public $last_notification = '';

    public $notified_list = array();

    public function __construct()
    {
        $this->table = 'blokada_info';
    }

    /**
     * Get notified persons
     */
    public function get_notified($mb)
    {
        global $engine;
        $query = "SELECT * FROM " . $this->table . " WHERE mb=" . $mb;
        $engine->dbase->query($query);
        $n = $engine->dbase->rows;
        return $n;
    }

    /**
     * Check notified
     */
    public function delete_old_notifications($older_than)
    {
        global $engine;
        $date = date("Y-m-d h:m:s", strtotime($older_than));
        $query = "DELETE FROM " . $this . " WHERE last_notification <='" . $date . "'";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check notified
     * @param int $user_id
     * @param int $mb
     */
    public function check_notified($user_id, $mb)
    {
        $noted = false;
        foreach ($this->notified_list as $key => $val) {
            if ($val['mb'] == $mb && $user_id == $val['user_id']) {
                $noted = true;
                break;
            }
        }
        return $noted;
    }

    /**
     * Add notified
     * @param int $mb
     * @param int $user_id
     */
    public function add_notified($mb, $user_id, $status, $account)
    {
        global $engine;
        # check existance
        $query = "SELECT COUNT(*) as num_notifications FROM " . $this->table . " WHERE user_id=" . (int)$user_id . " AND mb=" . (int)$mb . " AND account='" . $account . "'";
        $engine->dbase->query($query);
        $notified = $engine->dbase->rows[0]['num_notifications'];
        if ($notified > 0) {
            $query = "UPDATE " . $this->table . " SET user_id=" . (int)$user_id . ", last_notification=NOW(), status=" . (int)$status . " WHERE user_id=" . (int)$user_id . " AND mb=" . (int)$mb . " AND account='" . $account . "'";
        } else {
            $query = "INSERT INTO " . $this->table . " SET user_id=" . (int)$user_id . ", last_notification=NOW(), mb=" . (int)$mb . ", status=" . (int)$status . ", account='" . $account . "'";
        }
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }
}

?>