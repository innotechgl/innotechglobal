<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:18 PM
 */

class customerOrdersObject extends mainObject
{

    protected $Title;
    protected $Date;
    protected $Paid;
    protected $TotalSum;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTitle(){
        return $this->Title;
    }

    public function setTitle($Title){
        $this->Title = $Title;
    }

    public function getDate(){
        return $this->Date;
    }

    public function setDate($Date){
        $this->Date = $Date;
    }

    public function getPaid(){
        return $this->Paid;
    }

    public function setPaid($Paid){
        $this->Paid= $Paid;
    }

    public function getTotalSum(){
        return $this->TotalSum;
    }

    public function setTotalSum($TotalSum){
        $this->TotalSum = $TotalSum;
    }
}