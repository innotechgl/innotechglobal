<?php

class modelLang extends language {

    public function __construct(){
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getAll(){
        $array = $this->get_array(false);
        return $array;
    }
}