<?php

/**
 * Created by PhpStorm.
 * User: dajana
 * Date: 29.6.15
 * Time: 22:33
 */
class articleModel extends articles_class
{

    /**
     * @var Array
     */
    protected $articles;

    protected $mapIconFolder;

    public function __construct()
    {
        parent::__construct();

        $this->mapIconFolder = _APP_DIR_."media/images/map_icons/";
    }

    /**
     * @param Int $id
     */
    public function loadArticle($id)
    {
        $row = $this->get_array(false, "*", "WHERE id=" . (int)$id);

        //var_dump($row);
        $article = new articleObject();
        $article->fillMe($row[0]);
        return $article;
    }

    public function edit($data){
        $article = new articleObject();
        $article->fillMe($data);
        $res = $this->updateStandard($article);

       
        return $res;
    }

    public function updateStandard(articleObject $article)
    {
        $query = "UPDATE " . $this->table . "
            SET title='" . $article->getTitle() . "',
            	date='" . $article->getDate()->format("Y-m-d h:i:s") . "',
                alias='" . $article->getAlias() . "',
                text='" . addslashes($article->getText()) . "',
                active='" . $article->getActive() . "',
                language='" . $article->getLang() . "',
                categorie_id='" . $article->getCategorieID() . "',
                article_id='" . $article->getArticleID() . "',
                meta='" . $article->getMeta() . "',
                description='" . $article->getDescription() . "',
                modified=NOW(),
                modifier='" . $article->getModifier() . "',
                created='" . $article->getCreated()->format("Y-m-d h:i:s") . "',
                author='" . $article->getAuthor() . "',
                options='".json_encode($article->getData())."'
                WHERE id=" . $article->getID() . " LIMIT 1";

        if ($this->engine->dbase->insertQuery($query)) {

            // Dispatch successfully update
            $this->engine->events->dispatchEvent(events::ON_UPDATE, $this->page, array("id" => $article->getID()));

           // $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=" . (int)$id;
        $res = $this->engine->dbase->insertQuery($query);
        return $res;
    }

    /**
     * @param array $ids
     * @return bool
     */
    public function deleteMultiple(Array $ids){
        $query = "DELETE FROM ".$this->table." WHERE id IN (".implode(",",$ids).")";
        $res = $this->engine->dbase->insertQuery($query);

        // Write update info
        foreach($ids as $id){
           // $this->engine->updater->addDeletedItem($this->page, $id);
        }
        return $res;
    }

    /**
     * @return array
     */
    public function getAll(){
        $this->articles = array();

        $rows = $this->get_array(false, "*");

        foreach($rows as $row){
            $article = new articleObjectInteractive();
            $article->fillMe($row);
            $this->articles[] = $article->__getArray();
        }

        return $this->articles;
    }

    /**
     * @param $categorieID
     * @param bool $onlyActive
     * @param int $limit
     * @param string $orderBY
     * @param string $orderDirection
     * @return Array
     */
    public function loadArticlesByCategorieID($categorieID, $onlyActive = false, $limit = 0, $orderBY = "id", $orderDirection = "ASC")
    {
        $limited = false;
        $params = array();
        $where = 'WHERE categorie_id=' . (int)$categorieID;
        if ($limit > 0) {
            $limited = true;
        }
        $this->engine->dbase->setLimit($limit);
        if ($onlyActive) {
            $params[] = "active = 1";
        }
        if (count($params) > 0) {
            $where .= implode(" AND ", $params);
        }
        $query = $this->get_array($limited, "*", $where, $orderBY, $orderDirection);
        $rows = $this->engine->dbase->query($query);
        $this->convertRowsToArticles($rows);
        return $this->articles;
    }

    /**
     * @param array $rows
     */
    protected function convertRowsToArticles(Array $rows)
    {
        $this->articles = Array();
        foreach ($rows as $key => $val) {
            $article = new articleObject();
            $article->fillMe($val);
            $this->articles[] = $article;
        }
    }

    /**
     * @return array
     */
    public function convertArticlesToArray()
    {
        $articles = array();
        foreach ($this->articles as $key => $val) {
            $article = $val;
            $articles[] = $article->__getArray();
        }
        return $articles;
    }

    public function getAllArticleCategories(){
        $cats = array();

        $this->engine->load_page("article_categories");
        $cats_raw = $this->engine->article_categories->get_array(false,"*","","no","asc");

        foreach($cats_raw as $key=>$val){
            $cats[$key]["id"]        = $val["id"];
            $cats[$key]["name"]     = $val["name"];
            $cats[$key]["parent_id"] = $val["parent_id"];
        }

        return $cats;
    }

    public function getTasksAndOptions(){
        $fileToLoad = _APP_DIR_."pages/articles/settings/menuOptions.xml";
        if (file_exists($fileToLoad)){
            $xml = simplexml_load_file($fileToLoad);
            $xml_settings = (array)$xml->children();

            return $xml_settings;
        }
        else {
            return "FILE Doesn't exist.";
        }
    }

    public function getArticleSettings(){
        $fileToLoad = _APP_DIR_."pages/articles/settings/articleOptions.xml";

        if (file_exists($fileToLoad)){
            $xml = simplexml_load_file($fileToLoad);
            $xml_settings = (array)$xml->children();

            return $xml_settings;
        }
        else {
            return "FILE Doesn't exist.";
        }
    }

    public function getAllForMenuCreator($id){
        $array = $this->get_array(false,"id,title","WHERE categorie_id=".(int)$id,"title","asc");
        return $array;
    }

    public function getAllLanguages(){
        $langs = $this->engine->language->get_array(false);
        return $langs;
    }

    /**
     * @return bool|int
     */
    public function add($data)
    {


        $article = new articleObject();
        $article->fillMe($data);

        $article->setData(
            $data["options"]
        );

        $articleAdded = $this->addStandard($article);

    

        return $articleAdded;
    }

    protected function addStandard(articleObject $article){
        $query = "INSERT INTO " . $this->table . "
            (alias, title, categorie_id, text, active, description, meta, language, created, creator, author, `date`,
            `modified`,`modifier`, `options`)
            VALUES
            ('" . $article->getAlias() . "','" . $article->getTitle() . "', '" . $article->getCategorieID()
            . "','" . addslashes($article->getText()). "',
			 '" . $article->getActive() . "', '" . $article->getDescription() . "', '" . $article->getMeta() . "',
			 '" . $article->getLang() . "', NOW(), '" . $this->engine->users->active_user->get_id() .
            "', '" . $article->getAuthor() . "', '" . $article->getDate()->format("Y-m-d h:m:s") . "', NOW(),'" .
            $this->engine->users->active_user->get_id() . "', '".json_encode($article->getData())."');";

        // ID
        $id = $this->engine->dbase->insertQuery($query);
        // ID
        $article->setID($id);

        //$this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        return $article;
    }

    /**
     * @param str $alias
     * @param int $id
     */
    public function updateAlias($alias,$id){
        $query = "UPDATE ".$this->table." SET alias='".$alias."' WHERE id=".$id." LIMIT 1;";
        $this->engine->dbase->insertQuery($query);

    }

    /**
     * @param int $id
     * @return bool
     */
    public function activate($id)
    {
        $res = parent::activate($id);
       // $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
        return $res;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deactivate($id)
    {
        $res = parent::deactivate($id);
       // $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);
        return $res;
    }

    /**
     * @return array
     */
    public function getMapIcons(){

        $icons = array();

        $dir = opendir($this->mapIconFolder);

        while (false !== ($entry = readdir($dir))) {
            if ($entry != "." && $entry != "..") {
                $icons[] = "media/images/map_icons/".$entry;
            }
        }

        closedir($dir);

        return $icons;
    }
    
    public function _sort($articles){
        foreach($articles as $key=>$val){
            $query = "UPDATE articles SET `no`='".$key."' WHERE id='".(int)$val."' LIMIT 1;";
            $this->engine->dbase->insertQuery($query);
        }
    }

}