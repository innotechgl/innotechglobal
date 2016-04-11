<?php


class factoryOrders extends mainObject
{
    /**
     * @var String
     */
    protected $title;
    /**
     * @var DateTime
     */
    protected $date;
    /**
     * @var Int
     */
    protected $customerID;
    /**
     * @var boolean
     */
    protected $paid;

    /**
     * @return String
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle($title){
        $this->title = $title;
    }

    /**
     * @return DateTime
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date){
        $this->date = $date;
    }

    /**
     * @return String
     */
    public function getCustomerID(){
        return $this->customerID;
    }

    /**
     * @param Int $customerID
     */
    public function setCustomerID($customerID){
        $this->customerID = (int)$customerID;
    }

    /**
     * @return boolean
     */
    public function getPaid(){
        return $this->paid;
    }

    /**
     * @param boolean $paid
     */
    public function setPaid($paid){
        $this->paid = $paid;
    }

    /**
     * @return array
     */
    public function __getArray(){
        $array = parent::__getArray();
        $array["customer_id"] = $this->customerID;
        $array["title"] = $this->title;
        $array["date"] = $this->date;
        $array["paid"] = $this->paid;

        return $array;
    }

    public function fillMe($data){

        parent::fillMe($data);
        $this->setTitle($data["title"]);
        $this->setCustomerID($data["customer_id"]);
        $this->setDate($data["date"]);
        $this->setPaid($data["paid"]);

    }

}