<?php

class calendarModel extends calendar_class
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param String $startDate
     * @param String $endDate
     * @param String $relPage
     * @param Int $relID
     * @return Array
     */
    public function addCalendarItems($startDate, $endDate, $relPage, $relID)
    {
        $calendarItems = array();
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $interval = new DateInterval('P1D');
        $dates = new DatePeriod($startDate, $interval, $endDate->modify("+1 day"));
        foreach ($dates as $key => $val) {
            $calendarItem = new calendarItem();
            $calendarItem->setDate($val);
            $calendarItem->setRelPage($relPage);
            $calendarItem->setRelID($relID);
            $calendarItem->setCreator($this->engine->users->active_user->get_id());
            $calendarItem->setCreated(date("Y-m-d h:i:s"));
            $calendarItem->setModifier($this->engine->users->active_user->get_id());
            $calendarItem->setModified(date("Y-m-d h:i:s"));
            $calendarItems[] = $calendarItem;
        }
        $this->addMultiple($calendarItems);
    }

    /**
     * @param Int $month
     * @param Int $year
     * @param String $relPage
     * @param Int $relID
     * @return array
     */
    public function getDatesForMonth($month, $year, $relPage, $relID)
    {
        $calendarItems = array();
        $query = "SELECT * FROM " . $this->table . " WHERE rel_page='" . $relPage . "' AND rel_id='" . $relID .
            "' AND YEAR(Date) = " . (int)$year . " AND MONTH(Date) = " . (int)$month;
        $this->engine->dbase->query($query);
        foreach ($this->engine->dbase->rows as $val) {
            $calendarItem = new calendarItem();
            $calendarItem->setId($val["id"]);
            $calendarItem->setRelID($val["rel_id"]);
            $calendarItem->setRelPage($val["rel_page"]);
            $calendarItem->setDate(new DateTime($val["date"]));
            $calendarItem->setCreated($val["created"]);
            $calendarItem->setCreator($val["creator"]);
            $calendarItem->setModified($val["modified"]);
            $calendarItem->setModifier($val["modifier"]);
            $calendarItems[] = $calendarItem->__toArray();
        }
        return $calendarItems;
    }

    public function deleteDates($startDate, $endDate, $relPage, $relID)
    {
        $queries = array();
        echo $startDate;
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        echo $startDate->format("d.m.Y.");
        $interval = new DateInterval('P1D');
        $dates = new DatePeriod($startDate, $interval, $endDate->modify("+1 day"));
        foreach ($dates as $key => $val) {
            $queries[] = "'" . $val->format("Y-m-d") . "'";
        }
        if (count($queries) > 0) {
            $query = "DELETE FROM " . $this->table . " WHERE date IN (" . implode(",", $queries)
                . ") AND rel_page='" . $relPage . "' AND rel_id='" . $relID . "'";
            $this->engine->dbase->insertQuery($query);
        }
    }
}