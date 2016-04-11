<?php

/**
 * Class googleMapObject
 */
class googleMapObject extends mainObject
{

    protected $lat;
    protected $lng;
    protected $title;
    protected $description;
    protected $zoom;
    protected $relPage;
    protected $relID;
    protected $active;
    protected $url;
    protected $icon;

    /**
     * @var array
     */
    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = array();
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getZoom()
    {
        return $this->zoom;
    }

    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getRelPage()
    {
        return $this->relPage;
    }

    public function setRelPage($relPage)
    {
        $this->relPage = $relPage;
    }

    public function getRelID()
    {
        return $this->relID;
    }

    public function setRelID($relID)
    {
        $this->relID = $relID;
    }

    public function getURL()
    {
        return $this->url;
    }

    public function setURL($url)
    {
        $this->url = $url;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getIcon(){
        return $this->icon;
    }

    public function setIcon($icon){
        $this->icon = $icon;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param string $key
     * @param mixed $val
     */
    public function setData($key, $val){
        $this->data[$key] = $val;
    }

    /**
     * @param array $vals
     */
    public function fillMe($vals)
    {
        parent::fillMe($vals);
        $this->setDescription($vals["description"]);
        $this->setLat($vals["lat"]);
        $this->setLng($vals["lng"]);
        if (isset($vals["rel_id"])) {
            $this->setRelID($vals["rel_id"]);
        }
        if (isset($vals["rel_page"])) {
            $this->setRelPage($vals["rel_page"]);
        }
        $this->setTitle($vals["title"]);
        if (isset($vals["url"])) {
            $this->setURL($vals["url"]);
        }
        $this->setZoom($vals["zoom"]);

        $this->setIcon($vals["icon"]);
    }

    /**
     * @return array
     */
    public function __getArray()
    {
        $array = parent::__getArray();

        $array["lng"] = $this->lng;
        $array["lat"] = $this->lat;
        $array["title"] = $this->title;
        $array["zoom"] = $this->zoom;
        $array["description"] = $this->description;
        $array["rel_page"] = $this->relPage;
        $array["rel_id"] = $this->relID;
        $array["url"] = $this->url;
        $array["data"] = $this->data;
        $array["icon"] = $this->icon;

        return $array;
    }
}