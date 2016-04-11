<?php
require_once _ROOT_.'utils/googleMaps/objects/googleMapObject.php';

class googleMaps_class extends util_class
{
    public $table = 'googlemaps';
    private $data = array();

    public function  __construct()
    {
        parent::__construct();
        $this->data['points'] = array();
    }

    /**
     * @param bool $addopt
     * @param string $what_to_get
     * @param string $filter_params
     * @param string $order_by
     * @param string $order_direction
     * @param string $table
     * @return array
     */
    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id',
                              $order_direction = 'ASC', $table = '')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . "
                  FROM " . $this->table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /**
     * @param googleMapObject $googleMapItem
     * @return bool
     */
    public function add(googleMapObject $googleMapItem)
    {

        if ($this->check_existance($googleMapItem->getRelPage(), $googleMapItem->getRelID())) {

            $query = "UPDATE " . $this->table . " SET zoom='"
                . $googleMapItem->getZoom() . "', lat='" . $googleMapItem->getLat() . "', lng='"
                . $googleMapItem->getLng() . "', url='" . $googleMapItem->getURL() . "', title='"
                . $googleMapItem->getTitle() . "', active='".$googleMapItem->getActive()."',lang='".
                $googleMapItem->getLang()."', description='" . $googleMapItem->getDescription()
                . "', `icon`='".$googleMapItem->getIcon()."', `modified`=NOW(), `modifier`='".$this->engine->users->active_user->get_id()
                ."' WHERE rel_id=". $googleMapItem->getRelID()
                . " AND rel_page LIKE '" . $googleMapItem->getRelPage() . "' LIMIT 1;";

        } else {
            $query = "INSERT INTO " . $this->table . " SET zoom='" . $googleMapItem->getZoom() . "', lat='" .
                $googleMapItem->getLat() . "', lng='" . $googleMapItem->getLng() . "', url='" .
                $googleMapItem->getURL() . "', title='" .
                $googleMapItem->getTitle() . "', lang='".
                $googleMapItem->getLang()."',description='" . $googleMapItem->getDescription() . "', rel_id='" .
                $googleMapItem->getRelID() . "', rel_page='" . $googleMapItem->getRelPage()
                . "', icon='".$googleMapItem->getIcon()."',`modified`=NOW(), `created`=NOW(), `modifier`='"
                .$this->engine->users->active_user->get_id()
                ."', `creator`='".$this->engine->users->active_user->get_id()."';";
        }

        $id = $this->engine->dbase->insertQuery($query);
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, "map");

        return $id;
    }

    /**
     * @param string $rel_page
     * @param int $rel_id
     * @return bool
     */
    public function check_existance($rel_page, $rel_id)
    {

        $rel_page = filter_var($rel_page, FILTER_SANITIZE_STRING);
        $rel_id = filter_var($rel_id, FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT COUNT(*) as num_of FROM " . $this->table . " WHERE rel_id=" . $rel_id
            . " AND rel_page LIKE '" . $rel_page . "';";

        $this->engine->dbase->query($query);

        if ($this->engine->dbase->rows[0]['num_of'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $rel_page
     * @param string $rel_id
     * @return bool
     */
    public function delete($rel_page, $rel_id)
    {
        $rel_page = filter_var($rel_page, FILTER_SANITIZE_STRING);
        $rel_id = filter_var($rel_id, FILTER_SANITIZE_NUMBER_INT);

        $id = $this->getID($rel_page, $rel_id);

        if ($id){

            $query = "DELETE FROM " . $this->table . " WHERE `id`='".$id."' LIMIT 1;";

            if ($this->engine->dbase->insertQuery($query)) {
                $this->engine->updater->addDeletedItem("map", $id);
                return true;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * @param string $rel_page
     * @param string $rel_id
     * @return bool
     */
    protected function getID($rel_page, $rel_id){

        $rel_page = filter_var($rel_page, FILTER_SANITIZE_STRING);
        $rel_id = filter_var($rel_id, FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT `id` FROM " . $this->table . " WHERE rel_id=" . $rel_id
            . " AND rel_page LIKE '" . $rel_page . "';";

        $this->engine->dbase->query($query);

        if (count($this->engine->dbase->rows) > 0) {
            return $this->engine->dbase->rows[0]["id"];
        } else {
            return false;
        }
    }

}