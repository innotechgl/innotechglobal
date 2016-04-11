<?php

class modelAccommodationReservations extends accommodationsReservation
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Int $accommodationID
     * @return array
     */
    public function getReservations($accommodationID)
    {
        $reservations = array();
        $query = "SELECT * FROM " . $this->table . " WHERE accommodation_id=" . (int)$accommodationID;
        $this->engine->dbase->query($query);
        foreach ($this->engine->dbase->rows as $key => $val) {
            $reservationItem = new accommodationReservation_item();
            $reservationItem->fillMeFromDB($val);
            $arr = $reservationItem->__toArray();
            $arr["created"] = date("d.m.Y.", strtotime($arr["created"]));
            $arr["info"]["arriving"] = date("d.m.Y.", strtotime($arr["info"]["arriving"]));
            $arr["info"]["leaving"] = date("d.m.Y.", strtotime($arr["info"]["leaving"]));
            $reservations[] = $arr;
        }
        return $reservations;
    }

    /**
     * @param Int $accommodationID
     * @return array
     */
    public function getPendingReservations($accommodationID)
    {
        $reservations = array();
        $query = "SELECT * FROM " . $this->table . " WHERE accommodation_id=" . (int)$accommodationID . " AND confirmed=1";
        $this->engine->dbase->query($query);
        foreach ($this->engine->dbase->rows as $key => $val) {
            $reservationItem = new accommodationReservation_item();
            $reservationItem->fillMeFromDB($val);
            $reservations[] = $reservationItem->__toArray();
        }
        return $reservations;
    }

    /**
     * @param Int $accommodationID
     * @return array
     */
    public function getAcceptedReservations($accommodationID)
    {
        $reservations = array();
        $query = "SELECT * FROM " . $this->table . " WHERE accommodation_id=" . (int)$accommodationID . " AND confirmed=0";
        $this->engine->dbase->query($query);
        foreach ($this->engine->dbase->rows as $key => $val) {
            $reservationItem = new accommodationReservation_item();
            $reservationItem->fillMeFromDB($val);
            $reservations[] = $reservationItem->__toArray();
        }
        return $reservations;
    }

    public function getRejectedReservations()
    {
    }

    public function addReservation()
    {
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function acceptReservation($id)
    {
        $query = "UPDATE " . $this->table . " SET confirmed=1 WHERE id=" . (int)$id . " LIMIT 1;";
        $result = $this->engine->dbase->insertQuery($query);
        return $result;
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function rejectReservation($id)
    {
        $query = "UPDATE " . $this->table . " SET confirmed=0 WHERE id=" . (int)$id . " LIMIT 1;";
        $result = $this->engine->dbase->insertQuery($query);
        return $result;
    }
}