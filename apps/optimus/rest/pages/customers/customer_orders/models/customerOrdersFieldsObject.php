<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class customerOrderFieldsObject extends mainObject
{

    protected $Description;
    protected $Value;
    protected $Currency;

    public function __construct()
    {
        parent::__construct();
    }

    public function getDescription(){
        return $this->Description;
    }

    public function setDescription($Description){
        $this->Description = $Description;
    }

    public function getValue(){
        return $this->Value;
    }

    public function setValue($Value){
        $this->Value = $Value;
    }

    public function getCurrency(){
        return $this->Currency;
    }

    public function setCurrency($Currency){
        $this->Currency= $Currency;
    }
}