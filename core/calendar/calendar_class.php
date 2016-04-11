<?php

/**
 *
 * Calendar class
 * @author Dajana Nestorovic
 * @name Calendar
 * @package sCMS ver 2.0
 * @copyright ExploreIT by Innotech Global
 */
class calendar
{

    public $max_forward = 2008;
    public $max_backward = 2012;

    public $year = 2010;
    public $month = 01;
    public $day = 01;

    public $ajax_link = '';

    public $calendar_no = 1;

    public $div = '';
    public $page = 'calendar';
    private $engine;

    public function  __construct()
    {
        global $engine;
        $engine = $engine;
        $this->year = date("Y");
        $this->month = date("m");
        $this->day = date("d");
        // INCLUDE LANGUAGE PACK
        include $engine->path . 'language/core/' . $this->page . '/' . $this->page . '_lat.php';
        $lang_class = $this->page . "_language";
        $this->lang = new $lang_class();
    }

    public function add_calendar($div = '', $link = '', $d = '', $m = '', $y = '')
    {
        $this->div = $div;
        $this->ajax_link = $link;
        if (isset($d)) {
            $this->day = $d;
        }
        if (isset($m)) {
            $this->day = $d;
        }
        if (isset($y)) {
            $this->day = $d;
        }
        include $this->path . 'core/calendar/html/insert.php';
        $this->calendar_no++;
    }

    public function load_calendar()
    {
        include $this->path . 'core/calendar/html/initialize.php';
    }

    /**
     * Get number of days until custom date
     *
     * @param int $month
     * @param int $day
     * @param int $year
     * @return int
     */
    public function get_days_until($month, $day, $year)
    {
        $days = (int)((mktime(0, 0, 0, $month, $day, $year) - time()) / 86400);
        return $days;
    }

    /**
     * Deljenje datuma
     * @param date $datum
     * @return dan, mesec, godina
     */
    public function razdeli_datum($datum)
    {
        $datum = explode("-", $datum);
        return array(dan => $datum[2], mesec => $datum[1], godina => $datum[0]);
    }

    /**
     * Deljenje datuma
     * @param date $datum
     * @return dan, mesec, godina
     */
    public function separate_date_time($datum)
    {
        $datum = explode(" ", $datum);
        $vreme = $datum[1];
        $datum = $datum = explode("-", $datum[0]);
        $vreme = explode(":", $vreme);
        return array('day' => $datum[2], 'month' => $datum[1], 'year' => $datum[0], 'hour' => $vreme[0], 'minute' => $vreme[1], 'second' => $vreme[2]);
    }

    /**
     * Konvertovanje datuma iz USA u SR
     * @param date $datum
     */
    public function konvert_datum_usa_u_sr($datum, $br = null)
    {
        $datum = $datum = explode("-", $datum);
        $dan = $this->dan_naziv[strtolower(date("l", mktime(0, 0, 0, $datum[1], $datum[2], $datum[0])))];
        return cir_lat("$dan,$br $datum[2].$datum[1].$datum[0].");
    }

    /**
     * Konvertovanje datuma i vremena iz USA u SR
     * @param date $datum
     */
    public function konvert_datum_vreme_usa_u_sr($datum, $br = null)
    {
        $datum = explode(" ", $datum);
        $vreme = $datum[1];
        $datum = $datum = explode("-", $datum[0]);
        $dan = $this->dan_naziv[strtolower(date("l", mktime(0, 0, 0, $datum[1], $datum[2], $datum[0])))];
        return "$dan, $br $datum[2].$datum[1].$datum[0]. $vreme ";
    }

    /**
     * Konvertovanje datuma i vremena iz USA u SR
     * @param date $datum
     */
    public function konvert_datum_vreme_usa_u_sr_forma($datum, $prikaz_vreme = true)
    {
        $datum = explode(" ", $datum);
        if ($prikaz_vreme == true) {
            $vreme = " " . $datum[1];
        }
        $datum = $datum = explode("-", $datum[0]);
        return "$datum[2].$datum[1].$datum[0].$vreme";
    }

    /**
     * Konvertovanje datuma i vremena iz SR u USA
     * @param date $datum
     */
    public function konvert_datum_vreme_sr_u_usa_forma($datum)
    {
        $datum = explode(" ", $datum);
        $vreme = $datum[1];
        $datum = $datum = explode(".", $datum[0]);
        return $datum[2] . "-" . $datum[1] . "-" . $datum[0] . " " . $vreme;
    }

    /**
     * Konvertujemo SR u USA datum
     *
     * @param string $datum
     */
    public function sr_usa($datum)
    {
        $datum_nov = explode(".", $datum);
        $datum_export = $datum_nov[2] . "-" . $datum_nov[1] . "-" . $datum_nov[0];
        return $datum_export;
    }

    /**
     * Konvertujemo SR u USA datum
     *
     * @param string $datum
     */
    public function usa_sr($datum = '')
    {
        $datum_nov = explode("-", $datum);
        return "$datum_nov[2].$datum_nov[1].$datum_nov[0].";
    }

    /**
     * Konvertor datuma po standardu
     *
     * @param string $datum
     * @return string
     */
    public function datum_vreme_usa_DATE_RFC822($datum = '')
    {
        $datum_vreme = explode(" ", $datum);
        $datum_vreme_datum = explode("-", $datum_vreme[0]);
        $datum_vreme_vreme = explode(":", $datum_vreme[1]);
        # Kreiramo prikaz datuma po standardu
        $datum = date("r", mktime($datum_vreme_vreme[0], $datum_vreme_vreme[1], $datum_vreme_vreme[2], $datum_vreme_datum[1], $datum_vreme_datum[2], $datum_vreme_datum[0]));
        return $datum;
    }
}

?>