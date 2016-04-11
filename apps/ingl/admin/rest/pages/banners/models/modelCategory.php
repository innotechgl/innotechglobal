<?php
class modelCategory extends banner_categories_class{

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param String $title
     * @param Int $parentID
     * @return Array
     */
    public function _add($title, $parentID){
        $query = "INSERT INTO ".$this->table." (name,parent_id,created, creator, modified, modifier)
         VALUES ('".$title."','".(int)$parentID."',NOW(),'".$this->engine->users->active_user->get_id()."', NOW(),'".
            $this->engine->users->active_user->get_id()."')";
        $id = $this->engine->dbase->insertQuery($query);

        $cat = new category_item();
        $cat->set_id($id);
        $cat->set_name($title);
        $cat->set_parent_id($parentID);

        mkdir(_APP_DIR_."media/images/banners/".$id);

        return $cat->__toArray();
    }

    /**
     * @param Int $id
     * @param String $title
     * * @return Boolean
     */
    public function _update($id,$title){
        $query = "UPDATE ".$this->table." SET name='".$title."' WHERE id=".(int)$id." LIMIT 1;";
        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    /**
     * @param Int $id
     * @return array
     */
    public function _delete($id){


        // get child categories
        $cats = $this->findChildrenCategories($id);

        // add parent category
        $cats[] = $id;

        $removedImages = $this->_removePicturesFromCategories($cats);

        $query = "DELETE FROM ".$this->table." WHERE id IN (".implode(",",$cats).");";
        $this->engine->dbase->insertQuery($query);

        $result = array("removed_images"=>$removedImages,"removed_categories"=>$cats);

        return $result;

    }

    /**
     * @param Int $id
     * @return array
     */
    protected function findChildrenCategories($id){
        $this->categories = $this->get_array(false,"*");
        $ch = $this->get_all_children($this->categories,$id);
        return $ch;
    }

    /**
     * @param array $categories
     * @return array
     */
    protected function _removePicturesFromCategories(Array $categories){
        $this->engine->load_page('banners');

        $idsToRemove = array();
        $filesToRemove = array();

        $photos = $this->engine->banners->get_array(false,"id,name,categorie_id","WHERE categorie_id IN (".
            implode(",",$categories).")");

        foreach($photos as $key=>$val){

            $idsToRemove[] = $val["id"];

            foreach($this->engine->article_photos->getSizes() as $size){
                $file = _APP_DIR_."media/images/banners/".$val["categorie_id"]
                    ."/".$size["prefix"].$val["name"];
                $filesToRemove[] = "media/images/banners/".$val["categorie_id"]
                ."/".$size["prefix"].$val["name"];

                if (is_file($file)){
                    unlink($file);
                }

            }
        }
        if (count ($idsToRemove) > 0){
            $query = "DELETE FROM ".$this->engine->banners->table." WHERE id IN (".implode(",",$idsToRemove).")";
            $this->engine->dbase->insertQuery($query);
        }

        return $filesToRemove;
    }
}