<?php

final class accommodations_connector_class extends util_class
{

    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->table = "rel_accommodations_connector";
        $this->engine =& $engine;
    }

    public function getConnectionForRelated($rel_page, $rel_id)
    {
        $room_id = 0;
        $query = "SELECT room_id FROM " . $this->table . " WHERE rel_page='" . $rel_page . "' AND rel_id='" . $rel_id . "'";
        $this->engine->dbase->query($query);
        if (count($this->engine->dbase->rows) > 0) {
            $room_id = $this->engine->dbase->rows[0]['room_id'];
        }
        return $room_id;
    }

    /**
     *
     * @param string $rel_page
     */
    public function getAllRelated($rel_page)
    {
        $related = array();
        $query = "SELECT room_id,rel_id FROM " . $this->table . " WHERE rel_page='" . $rel_page . "'";
        $this->engine->dbase->query($query);
        foreach ($this->engine->dbase->rows as $rel) {
            $related[] = $rel;
        }
        return $related;
    }

    public function getAccommodationRooms()
    {
    }

    public function updateConnection($rel_page, $rel_id, $room_id)
    {
        $this->deleteConnection($rel_page, $rel_id, $room_id);
        $query = "INSERT INTO " . $this->table . " (rel_page, rel_id, room_id) VALUES ('" .
            $rel_page . "', '" . $rel_id . "', '" . $room_id . "')";
        $id = $this->engine->dbase->insertQuery($query);
        return $id;
    }

    public function deleteConnection($rel_page, $rel_id, $room_id)
    {
        // Remove previous
        $query = "DELETE FROM " . $this->table . " WHERE rel_id=" . (int)$rel_id . " AND rel_page='" . $rel_page . "'";
        $this->engine->dbase->query($query);
    }
}