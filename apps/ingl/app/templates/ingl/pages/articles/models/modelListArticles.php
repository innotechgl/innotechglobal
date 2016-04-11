<?php

class modelListArticles extends articles_class
{

    public function __construct()
    {
        parent::__construct();
        $this->engine->pages->load_page("article_categories");
    }

    /**
     * @param Int $categorieID
     * @param Int $pageNO
     * @return array
     */
    public function listArticles($categorieID, $pageNO)
    {
        $articles = array();

        $category = $this->getCategory($categorieID);

        // Get article_categorie_settings
        $orderBy = "title";
        $orderDirection = "ASC";
        $limit = 0;

        //
        $this->engine->dbase->setLimit($limit);
        $this->engine->dbase->set_page_no($pageNO);

        $articles_raw = $this->get_array(true, "*",
            "WHERE active=1 AND categorie_id=" . (int)$categorieID, $orderBy, $orderDirection);

        foreach ($articles_raw as $key => $val) {
            $article = new articleObject();
            $article->fillMe($val);

            $article->setDataElement("link",
                $this->getLink(
                    $article->getAlias(),
                    $article->getCategorieID(),
                    $article->getLang())
                );

            $article->setDataElement("photos",
                $this->getGalleryImagesForArticle($article->getID())
            );


            $article->setDataElement("category",$category);

            $articles[] = $article;
        }

        // set Og
        $catOptions = $category->get_options();
        $this->setOg($category->get_name(),$category->get_description(),$category->get_meta(),$catOptions["default_img_list"]);

        return $articles;
    }

    /**
     * @param Int $id
     * @return category_item
     */
    public function getCategory($id){
        $articleCategoryRaw = $this->engine->article_categories->get_array(false,"*","WHERE id=".(int)$id);
        $articleCategory = new category_item();
        $articleCategory->fillMe($articleCategoryRaw[0]);
        $categorySettings = json_decode($articleCategoryRaw[0]["options"],true);

        $articleCategory->set_options($categorySettings);

        return $articleCategory;
    }

    /**
     * @param String $alias
     * @param Int $categoryID
     * @param String $lang
     * @return String
     */
    protected function getLink($alias,$categoryID,$lang){

        // getPath
        $this->engine->article_categories->founded_categories = array();
        $this->engine->article_categories->get_path($categoryID);
        $als = array();

        $fcats = $this->engine->article_categories->get_founded_categories();

        foreach ($fcats as $valAls) {
            if ($valAls['name'] !== 'root' && $valAls['alias'] !== '') {
                $als[] = $valAls['alias'];
            }
        }

        $link = implode("/", $als) . "/" . $alias . '/';

        if ($this->engine->settings->general->multilingual) {
            $link = $this->engine->settings->general->site . $lang ."/". $link;
        } else {
            $link = $this->engine->settings->general->site . $link;
        }

        return $link;
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

            $photos_raw = $this->engine->gallery->get_array(false, "name,categorie_id","WHERE categorie_id=".
                implode(",",$galleryCategories)." AND active=1");

            foreach($photos_raw as $key=>$val){
                $photos[] = $this->engine->settings->general->site."media/images/gallery/".$val["categorie_id"]."/".$val["name"];
            }
        }

        return $photos;

    }

    /**
     * @param string $title
     * @param string $description
     * @param string $keywords
     */
    protected function setOg($title = '', $description='', $keywords = '', $image=''){
        $this->engine->parser->setOgSiteName($this->engine->settings->general->site);
        $this->engine->parser->setOgDescription(htmlspecialchars($description));
        $this->engine->parser->setOgTitle($title);
        $this->engine->parser->setOgUrl($this->engine->settings->general->site . $_SERVER['REQUEST_URI']);
        $this->engine->parser->setOgType('article');

        if (trim($image) !== "") {
            if (strpos($this->engine->settings->general->site,$image)){
                $this->engine->parser->setOgImage($image);
            }
            else {
                $this->engine->parser->setOgImage($this->engine->settings->general->site.$image);
            }

        } else {
            $this->engine->parser->setOgImage($this->engine->settings->general->site
                ."templates/".$this->engine->settings->general->template."/images/logo.png");
        }
    }
}