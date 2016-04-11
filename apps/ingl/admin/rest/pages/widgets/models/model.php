<?php

class modelWidgets extends widgets {

    public function __construct(){
        parent::__construct();
    }

    public function getAll(){
        $widgets = $this->get_widgets(false,"*");
        return $widgets;
    }

    /**
     * @param Int $id
     * @return array
     */
    public function loadWidget($id){

        $query = "SELECT * FROM ".$this->table." WHERE id=".(int)$id;
        $this->engine->dbase->query($query);

        $data = $this->engine->dbase->rows[0];

        $data["widget"] = json_decode($data["options"],true);
        unset($data["options"]);
        $data["visible_at"] = $this->getWidgetVisibility($id);


        return $data;
    }

    /**
     * @param Int $widgetID
     * @return array
     */
    protected function getWidgetVisibility($widgetID){
        $data = array();

        $rawData = $this->get_array_rel(false, '*',
            "WHERE widget_id=".(int)$widgetID );

        foreach($rawData as $val){
            $data[] = $val["menu_id"];
        }

        return $data;
    }



    /**
     * @return array
     */
    public function getAllAvailableWidgets(){
        $widgets = array();
        $availableWidgets = $this->_getAllAvailableWidgets();

        foreach($availableWidgets as $key=>$widgetName){
            $options = array();

            $data = (array)$this->_getSettingsForWidget($widgetName);
            if (isset($data["options"])){
                $options = $data["options"];
            }



            $widgets[$key]["name"] = $widgetName;
            $widgets[$key]["options"] = $options;

        }

        return $widgets;

    }



    /**
     * @return array
     */
    protected function _getAllAvailableWidgets(){

        $dirToOpen = _APP_DIR_. 'widgets/';

        $handle = opendir($dirToOpen);

        $found = array();
        if ($handle) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                // Go through dir
                if ($file != "." && $file != "..") {
                    // Add founded in to array
                    if (is_dir($dirToOpen . "/" . $file)) {
                        $found[] = $file;
                    }
                }
            }
            sort($found);
            // Close handler
            closedir($handle);
        }

        return $found;
    }

    /**
     * @param String $widgetName
     * @return array|SimpleXMLElement
     */
    protected function _getSettingsForWidget($widgetName){
        $xml_settings = array();

        $file = _APP_DIR_ . 'widgets/' . $widgetName . '/settings/settings.xml';

        if (file_exists($file)) {
            $xml = simplexml_load_file($file);
            $xml_settings = (array)$xml->children();
        }

        return $xml_settings;
    }


    public function _update(array $data){
        if (!isset($data["name"])){
            return false;
        }

        $position = $data["position"];
        $widget = json_encode($data["widget"]);
        $visibleAt = $data["visible_at"];
        $name = $data["name"];
        $nativeName = $data["native_name"];
        $type = $data["type"];
        $active = $data["active"];
        $id = $data["id"];

        $query = "UPDATE " . $this->table . " SET `name`='".$name."', `native_name`='".$nativeName."',
         `type`='".$type."',`options`='".$widget."',`position`='".$position."', `modified`=NOW(),`modifier`='".
            $this->engine->users->active_user->get_id()."',`active`=".(int)$active." WHERE id=".(int)$id." LIMIT 1;";

        $res = $this->engine->dbase->insertQuery($query);

        $this->saveVisibleAt($id,$visibleAt);

        return $res;
    }

    protected function saveVisibleAt($widgetID,array $visibleAt){
        $this->link_widgets($widgetID,$visibleAt);
    }

    public function saveSort($data){
        $queryParts = array();

        foreach($data as $val){
            $queryParts[] = "UPDATE ".$this->table." SET `no`=".$val["no"]." WHERE id=".(int)$val["id"]." LIMIT 1;";
        }

        foreach($queryParts as $query){
            $this->engine->dbase->insertQuery($query);
        }

        return true;
    }

    public function _delete($id){

        $query = "DELETE FROM rel_widgets_menus WHERE widget_id=". (int)$id;
        $this->engine->dbase->insertQuery($query);
        $query = "DELETE FROM ".$this->table." WHERE id=". (int)$id;
        $this->engine->dbase->insertQuery($query);

        return true;
    }

    public function save(array $data){

        if (!isset($data["name"])){
            return false;
        }

        $position = $data["position"];
        $widget = json_encode($data["widget"]);
        $visibleAt = $data["visible_at"];
        $name = $data["name"];
        $nativeName = $data["native_name"];
        $type = $data["type"];
        $active = $data["active"];

        $query = "INSERT INTO " . $this->table . "(`name`, `native_name`, `type`,`options`,`position`,`created`,
        `creator`,`modified`,`modifier`,`no`,`active`)
            VALUES('" . $name . "','" . $nativeName . "','". $type . "', '".$widget."','".$position."',NOW(), '"
        .$this->engine->users->active_user->get_id()."',NOW(),'".$this->engine->users->active_user->get_id()."', 0, '".$active."');";

        $id = $this->engine->dbase->insertQuery($query);

        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        $this->saveVisibleAt($id,$visibleAt);

        return $id;
    }

}