<?php

class modelArticlePhotos extends article_photos_class {

    protected $tmpDir = "temp";

    public function __construct(){
        parent::__construct();
//        //$this->dir = _APP_DIR_."media/images/article_photos/";
        $this->dir = "/var/www/scms/apps/granice/"."media/images/article_photos/";
    }

    protected function convertImageToBase64($url){
        $encoded_data = base64_encode(file_get_contents($url));
        return $encoded_data;
    }

    /**
     * @param DateTime $sinceDate
     * @return array
     */
    public function getAllPhotos(DateTime $sinceDate){
        $photos = array();
        // echo "WHERE created > '".$sinceDate->format("Y-m-d H:i:s")."'";
        $photosRaw  = $this->get_array(false, "*","WHERE created > '".$sinceDate->format("Y-m-d H:i:s")."'");

        foreach ($photosRaw as $key=>$val) {

            foreach($this->sizes as $k=>$v){
                $photos[$key]["names"][] = "media/images/article_photos/".$val["categorie_id"]."/".$v["prefix"].$val["name"];
            }

            $photos[$key]["dir"] = "media/images/article_photos/".$val["categorie_id"];
            $photos[$key]["path"] = "media/images/article_photos/".$val["categorie_id"]."/".$val["name"];
            $photos[$key]["name"] = $val["name"];
            $photos[$key]["id"] = $val["id"];
        }

        return $photos;
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