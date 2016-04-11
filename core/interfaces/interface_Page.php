<?php

interface Page
{

    /**
     *
     * Add new record
     */
    function add();

    /**
     *
     * Edit record
     */
    function edit();

    /**
     *
     * Delete record
     */
    public function delete();

    /**
     *
     * Copy
     */
    function copy();

    /**
     *
     * Get array of records
     */
    function get_array();
}

?>