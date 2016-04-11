<?php
class modelMenu extends menu_class {


    public function __construct(){
        parent::__construct();

    }

    /**
     * @return array
     */
    public function getAll(){

        $menus = array();

        $menus_raw = $this->get_array(false,"*","","no","asc");
        foreach($menus_raw as $key=>$val){
            $menuItem = new menu_item();
            $menuItem->fillMe($val);
            $menus[] = $menuItem->__toArray();
        }

        return $menus;
    }

    /**
     * @param Int $id
     * @return array
     */
    public function load($id){
        $menuItem = new menu_item();
        $menus_raw = $this->get_array(false,"*","WHERE id=".(int)$id,"no","asc");
        $menuItem->fillMe($menus_raw[0]);
        return $menuItem->__toArray();
    }

    /**
     * @param array $data
     * @return bool|Int
     */
    public function addNew(array $data){
        $menuItem = new menu_item();

        $menuItem->fillMe($data);

        $res = parent::_add($menuItem);
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
        return $res;
    }

    public function updateMenu(array $data){
        
        $menuItem = new menu_item();
        $menuItem->fillMe($data);

        $res = parent::_update($menuItem);
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
        return $res;
    }

    /**
     * @param Int $id
     */
    public function activate($id){
        parent::activate_deactivate($id,1);
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
    }

    /**
     * @param Int $id
     */
    public function deactivate($id){
        parent::activate_deactivate($id,0);
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
    }

    /**
     * @param Int $id
     */
    public function _delete($id){
        parent::delete($id);
        $this->engine->updater->addDeletedItem($this->page, $id);
    }

    /**
     * @return array|SimpleXMLElement
     */
    public function getOptions(){
        # Read predefined settings(XML)
        $xml_settings = array();

        if (file_exists(_ROOT_ . 'pages/' . $this->page . '/settings/settings.xml')) {
            $xml = simplexml_load_file(_ROOT_ . 'pages/' . $this->page . '/settings/settings.xml');
            $xml_settings = $xml->children();
        }

        return $xml_settings;
    }

    /**
     * @return mixed
     */
    public function getPages(){

        global $engine;

        $query = "SELECT * FROM `pages` WHERE visible=1";
        $engine->dbase->query($query,false);
        return $engine->dbase->rows;
    }
    
    /**
     * 
     * @param array $menus
     */
    public function _sort($menus){
        foreach($menus as $key=>$val){
            $query = "UPDATE ".$this->table." SET `no`='".$key."' WHERE id='".(int)$val."' LIMIT 1;";
            $this->engine->dbase->insertQuery($query);
        }
    }

}