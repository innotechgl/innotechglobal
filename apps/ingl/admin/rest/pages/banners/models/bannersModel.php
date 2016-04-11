<?php

class bannersModel extends banners_class {

    protected $tmpDir = "temp";

    public function __construct(){
        parent::__construct();
        $this->dir = _APP_DIR_."media/images/banners/";
    }

    /**
     * @param bannerObject $photoItem
     * @return bool
     */
    protected function _add(bannerObject $photoItem){

        $query = "INSERT INTO ".$this->table." (`active`,`name`,`title`,`description`,`categorie_id`,`created`,`creator`,`modified`,`modifier`)
        VALUES ('".$photoItem->getActive()."','".$photoItem->getName()."', '".$photoItem->getTitle()."', '".$photoItem->getDescription()
            ."','".$photoItem->getCategorieId()."',NOW(),'".$this->engine->users->active_user->get_id()
            ."', NOW(), '".$this->engine->users->active_user->get_id()."')";

        $res = $this->engine->dbase->insertQuery($query);

        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        return $res;
    }

    /**
     * @param Int $id
     */
    public function _delete($id){

        $photos = $this->get_array(false,"id, name, categorie_id","WHERE id=".$id);

        foreach($photos as $key=>$val){

            foreach($this->sizes as $size){

                $file = _APP_DIR_."media/images/banners/".$val["categorie_id"]
                    ."/".$size["prefix"].$val["name"];
                $filesToRemove[] = "media/images/banners/".$val["categorie_id"]
                    ."/".$size["prefix"].$val["name"];

                if (is_file($file)){
                    unlink($file);
                }

            }
        }

        $query = "DELETE FROM ".$this->table." WHERE id=".(int)$id;

        // Write update info
        $this->engine->updater->addDeletedItem($this->page, $id);

        $this->engine->dbase->insertQuery($query);

    }

    /**
     * @param $photos
     * @param $categoryID
     * @param $title
     * @param $description
     * @param int $active
     * @return array
     */
    public function uploadPhotos($photos, $categoryID, $title, $description,$active=0){

        $results = array();
        $photosUploaded = array();

        // Create temp dir
        $this->createTempDir();

        foreach($photos as $photo){
            $results[] = $this->uploadPhoto($photo,$categoryID);
        }

        foreach($results as $result){

            $photo = new bannerObject();

            $photo->setName($result["name"]);
            $photo->setCategorieId($categoryID);
            $photo->setTitle($title);
            $photo->setDescription($description);
            $photo->setActive($active);

            $id = $this->_add($photo);
            $photo->setId($id);
            $photosUploaded[] = $photo->__getArray();
        }

        // Remove temp dir
        $this->deleteTempDir();

        $res = array("results"=>$results,"photos"=>$photosUploaded);

        return $res;
    }

    /**
     * @param Array $photo
     * @param Int $categoryID
     * @return array
     */
    protected function uploadPhoto(Array $photo, $categoryID){

        $photoResult = array("name"=>"","path"=>"","sizes"=>array());
        $dir = _APP_DIR_."media/images/banners/".$categoryID;

        if (!is_dir($dir)){
            mkdir($dir);
        }

        // Names
        if ($this->generateNames){
            $exp = explode(".",$photo["name"]);
            $name = rand(0,1200).time().rand(0,1250).".".$exp[count($exp)-1];
        }
        else {
            $name = $this->engine->sef->filename_sef($photo["name"]);
        }

        $photoResult["name"]  = $name;
        $photoResult["path"]  = $this->engine->settings->general->site."media/images/banners/".$categoryID."/";

        // Move file
        move_uploaded_file($photo['tmp_name'], $this->tmpDir . "/" . $name);
        // echo $photo['tmp_name'], $this->tmpDir . "/" . $name;

        foreach($this->sizes as $size){
            $result = $this->engine->util_photos->resize_photo(
                $name, $this->tmpDir."/", $size["size"], $size["prefix"],
                $dir."/", null, $size["cmp"]
            );

            $photoResult["sizes"][] = $size["prefix"];
        }

        return $photoResult;
    }

    protected function createTempDir(){

        // Set temp dir
        $this->tmpDir = _ROOT_.$this->tmpDir."/".rand(0,1250).time().rand(0,1250);

        if (!is_dir($this->tmpDir)) {
            mkdir($this->tmpDir, 0755, true);
        }

    }

    protected function deleteTempDir(){
        $files = array_diff(scandir($this->tmpDir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$this->tmpDir/$file")) ? delTree("$this->tmpDir/$file") : unlink("$this->tmpDir/$file");
        }
        return rmdir($this->tmpDir);
    }

    public function edit($data){

        $banner = new bannerObject();
        $banner->fillMe($data);

        $query = "UPDATE ".$this->table."
         SET title='".$banner->getTitle()."', `description`='".
            $banner->getDescription()
            ."', `active`='".$banner->getActive()."', modified=NOW(), `modifier`='".
            $this->engine->users->active_user->get_id()."' WHERE id='".$banner->getID()."';";

        $res = $this->engine->dbase->insertQuery($query);

        $this->engine->updater->writeUpdateStatusInFile(updater_class::UPDATED, $this->page);

        return $res;
    }

    public function getAllCategories(){
        $query = "SELECT * FROM banner_categories";
        $this->engine->dbase->query($query);
        return $this->engine->dbase->rows;
    }

    public function getAllBanners(){
        $photos = $this->get_array(false, "*");
        return $photos;
    }
    
    /**
     * 
     * @param array $banners
     */
    public function _sort($banners){
        foreach($banners as $key=>$val){
            $query = "UPDATE ".$this->table." SET `no`='".$key."' WHERE id='".(int)$val."' LIMIT 1;";
            $this->engine->dbase->insertQuery($query);
        }
    }


}