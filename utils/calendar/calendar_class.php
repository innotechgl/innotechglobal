<?php

class calendar_class extends util_class
{
    public $get_url = '';
    public $first_year = 0;
    public $last_year = 0;
    public $active_year = 0;
    public $active_month = 0;
    public $insert_div = '';

    public function  __construct()
    {
        parent::__construct();
        $this->first_year = date("Y", strtotime("-4 years"));
        $this->last_year = date("Y", strtotime("+2 months"));
        $this->active_year = date("Y");
        $this->active_month = date("m");
        $this->insert_div = "calendar";
    }
}

?>
