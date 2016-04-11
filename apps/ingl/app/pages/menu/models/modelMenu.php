<?php
class modelMenu extends menu_class {


    public function __construct(){
        parent::__construct();

    }

    /**
     * @param DateTime $sinceDate
     * @return array
     */
    public function getAll(DateTime $sinceDate){

        $menus = array();
        $menus_raw = $this->get_array(false,"*","WHERE `modified` > '".$sinceDate->format("Y-m-d H:i:s")."'","no","asc");

        foreach($menus_raw as $key=>$val){
            $menuItem = new menu_item();
            $menuItem->fillMe($val);
            $menuItem->set_name($this->engine->language->cirToLat($menuItem->get_name(), true));
            $menus[] = $menuItem->__toArray();
        }

        return $menus;
    }

    /**
     * @param DateTime $sinceDate
     * @return mixed
     */
    public function loadDeleted(DateTime $sinceDate){
        $data = $this->engine->updater->loadDeleted($this->page,$sinceDate);
        return $data;
    }

}