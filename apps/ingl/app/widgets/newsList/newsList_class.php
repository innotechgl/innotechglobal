<?php

final class widget_newsList_class extends widget_class
{

    protected $articles;

    public function prepare_me()
    {
        global $engine;
        // Load required pages & utils
        $engine->pages->load_pages('articles', 'article_photos', 'article_categories', 'gallery');
        $engine->load_util('photos');

        // Set limit
        $engine->dbase->limit = $this->settings['limit'];
        $metaSearch = '';
        $homeOnly = '';
        if (trim($this->settings['metaFilter']) !== "") {
            $metaSearch = "AND meta LIKE '%" . $this->settings['metaFilter'] . "%'";
        }
        if (isset($this->settings['home_only'])) {
            if (trim($this->settings['home_only']) == "yes") {
                $homeOnly = "AND home=1";
            }
        }
        // Load articles
        $articles = $this->engine->articles->get_array(true, '`date`, title, id, categorie_id, description, text,
        alias,options', "WHERE categorie_id IN (" . implode(",",$this->settings['categorie_id']) . ") " . $homeOnly . " " . $metaSearch .
            " AND active=1 AND (language LIKE '" . $this->engine->get_lang()
            . "' OR language LIKE 'ALL')",
            $this->settings['orderBy'], $this->settings['orderDirection']);

        if (count($articles) > 0) {
            foreach ($articles as $key => $val) {
                $article = new articleObject();
                $article->setID($val['id']);
                $article->setTitle($val['title']);
                $article->setAlias($val['alias']);
                $article->setCategorieID($val['categorie_id']);
                $article->setDescription($val['description']);
                $article->setText($val['text']);
                $article->setDate(new DateTime($val['date']));
                $article->setData(json_decode($val["options"],true));
                // Set photo
                $photo = array();
                //$photo = $this->engine->util_photos->get_img_from_text($article->getText(), $this->settings['pic_prefix']);
                // Add to array

                // getPath
                $engine->article_categories->founded_categories = array();
                $engine->article_categories->get_path($article->getCategorieID());
                $als = array();
                $fcats = $engine->article_categories->get_founded_categories();
                foreach ($fcats as $valAls) {
                    if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                        $als[] = $valAls['alias'];
                    }
                }

                $link = implode("/", $als) . "/" . $article->getAlias() . '/';

                $preLink = $this->engine->settings->general->site;
                if ($this->engine->settings->general->multilingual){
                    $preLink .= $this->engine->get_lang();
                }

                $link =  $preLink ."/".$link;

                $photos = $this->getGalleryImagesForArticle($article->getID());
                $article->setDataElement("photos",$photos);
                $article->setDataElement("link",$link);

                $this->articles[$key]['article'] = $article;
                $this->articles[$key]['photo'] = $photo;

            }
            if (isset($this->settings['language'])) {
                if ($this->settings['language'] == $engine->get_lang()) {
                    $this->set_view($this->settings['viewType']);
                }
            } else {
                $this->set_view($this->settings['viewType']);
            }
        } else {
            $this->set_view(null);
        }
    }


    /**
     * @param Int $id
     * @return array
     */
    protected function getGalleryImagesForArticle($id){

        $photos = array();

        $this->engine->load_util("gallery_connector");
        $this->engine->load_page("gallery");

        $galleryCategories = $this->engine->util_gallery_connector->getGalIdsForConnection("articles", $id);

        if (count($galleryCategories)>0){

            $photos_raw = $this->engine->gallery->get_array(false, "name,category_id","WHERE category_id=".
                implode(",",$galleryCategories)." AND active=1");

            foreach($photos_raw as $key=>$val){
                $photos[] = $this->engine->settings->general->site."media/images/gallery/".$val["category_id"]."/".$val["name"];
            }
        }

        return $photos;

    }

}

?>