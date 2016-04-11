<?php

class articleCategoriesModel extends article_categories_class
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $categories = array();
        $data = $this->get_array(false, "*");

        foreach($data as $val){
            $category = new category_item();
            $category->fillMe($val);
            $category->set_options(json_decode($category->get_options(),true));
            $categories[] = $category->__toArray();
        }

        return $categories;
    }

    public function getOptions(){
        $fileToLoad = _APP_DIR_."pages/article_categories/settings/categoryOptions.xml";
        if (file_exists($fileToLoad)){
            $xml = simplexml_load_file($fileToLoad);
            $xml_settings = (array)$xml->children();

            return $xml_settings;
        }
        else {
            return "FILE Doesn't exist.";
        }
    }

    public function load($id){
        $items = $this->get_array(false,"*","WHERE id=".(int)$id);

        $category = new category_item();
        $category->fillMe($items[0]);
        $category->set_options(json_decode($category->get_options(),true));

        return $category->__toArray();

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function _add(array $data)
    {
        $category = new category_item();
        $category->fillMe($data);

        $query = "INSERT INTO ".$this->table
            ." (`name`, `parent_id`, `language`, `active`, `alias`, `meta`, `description`,
            `options`, `created`, `creator`, `modified`, `modifier`)
            VALUES ('".$category->get_name()."','".
            $category->get_parent_id()."', '".$category->get_lang()."', '".
            $category->get_active()."',
             '".$category->get_alias()."','".$category->get_meta()."','".$category->get_description()."',
            '".json_encode($category->get_options())
            ."', NOW(),'".$this->engine->users->active_user->get_id()."', NOW(),'".$this->engine->users->active_user->get_id()."')";

        $id = $this->engine->dbase->insertQuery($query);

        $category->set_id($id);

        // Write update info
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        return $category->__toArray();
    }

    /**
     * @param Int $data
     * @return mixed
     */
    public function _update($data)
    {
        $category = new category_item();
        $category->fillMe($data);

        $query = "UPDATE ".$this->table." SET `name`='".$category->get_name().
            "', `parent_id`='".$category->get_parent_id()."', `language`='".$category->get_parent_id()
            ."', `options`='".json_encode($category->get_options())
            ."', `alias`='".$category->get_alias()."',`description`='".$category->get_description()."'
            ,`active`='".$category->get_active()."', `language`='".$category->get_lang()
            ."', `modified`=NOW(), `modifier`='".$this->engine->users->active_user->get_id()
            ."' WHERE id=".(int)$category->get_id()." LIMIT 1;";

        $this->engine->dbase->insertQuery($query);

        // Write update info
        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        return $category->__toArray();
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function _delete($id)
    {
        $query = "DELETE FROM ".$this->table." WHERE id=".(int)$id." LIMIT 1;";
        $res = $this->engine->dbase->insertQuery($query);

        // Write update info
        $this->engine->updater->addDeletedItem($this->page, $id);

        return $res;
    }
}