<?php

class modelViewArticle extends articles_class
{

    public function __construct()
    {
        parent::__construct();
        $this->engine->pages->load_pages("article_categorie","gallery");
    }

    /**
     * @param Int $id
     * @return articleObject
     */
    public function getArticle($id)
    {
        $articleRaw = $this->get_array(false, "*", "WHERE id=" . $id . " AND active=1");
        $article = new articleObject();
        $article->fillMe($articleRaw[0]);
        return $article;
    }

    /**
     * @param String $alias
     * @return articleObject
     */
    public function getArticleByAlias($alias)
    {

        $this->engine->load_util('gallery_connector');


        $alias = filter_var($alias, FILTER_SANITIZE_STRING);
        $articleRaw = $this->get_array(false, "*", "WHERE alias='" . $alias . "' AND active=1 AND language='".$this->engine->get_lang()."'");

        $article = new articleObject();
        $article->fillMe($articleRaw[0]);

        // Get Categorie with settings
        $articleCategoryRaw = $this->engine->article_categories->get_array(false,"*","WHERE id=".$article->getCategorieID());
        $articleCategory = new category_item();
        $articleCategory->fillMe($articleCategoryRaw[0]);
        $categorySettings = json_decode($articleCategoryRaw[0]["options"],true);

        $articleCategory->set_options($categorySettings);
        $article->setDataElement("category",$articleCategory);
        $article->setDataElement("photos",$this->getGalleryImagesForArticle($article->getID()));

        return $article;
    }

    /**
     * @param Int $id
     * @return array
     */
    protected function getGalleryImagesForArticle($id){

        $photos = array();

        $galleryCategories = $this->engine->util_gallery_connector->getGalIdsForConnection("articles", $id);

        if (count($galleryCategories)>0){

            $photos_raw = $this->engine->gallery->get_array(false, "name,categorie_id","WHERE categorie_id=".
                implode(",",$galleryCategories)." AND active=1");

            foreach($photos_raw as $key=>$val){
                $photos[] = $this->engine->settings->general->site."media/images/gallery/".$val["categorie_id"]."/".$val["name"];
            }
        }

        return $photos;

    }

    public function getArticleByID($id){

        $this->engine->load_util('gallery_connector');

        $articleRaw = $this->get_array(false, "*", "WHERE id=".(int)$id." AND active=1");
        $article = new articleObject();
        $article->fillMe($articleRaw[0]);

        // Get Categorie with settings
        $articleCategoryRaw = $this->engine->article_categories->get_array(false,"*","WHERE id=".$article->getCategorieID());
        $articleCategory = new category_item();
        $articleCategory->fillMe($articleCategoryRaw[0]);
        $categorySettings = json_decode($articleCategoryRaw[0]["options"],true);

        $articleCategory->set_options($categorySettings);
        $article->setDataElement("category",$articleCategory);
        $article->setDataElement("photos",$this->getGalleryImagesForArticle($article->getID()));

        return $article;
    }

    /**
     * @param array $IDStoDisable
     * @param Int $categoryID
     * @param Int $limit
     * @return Array
     */
    public function getRecentArticles(Array $IDStoDisable, $categoryID, $limit){

        $articles = array();

        $this->engine->dbase->setLimit($limit);
        $articlesRAW = $this->get_array(true,"*", "WHERE id NOT IN (".implode(",",$IDStoDisable)
            .") AND active=1 AND categorie_id=".$categoryID, "created", "DESC");

        foreach($articlesRAW as $key=>$val){
            $article = new articleObject();
            $article->fillMe($val);
            $articles[] = $article;
        }

        return $articles;
    }
}