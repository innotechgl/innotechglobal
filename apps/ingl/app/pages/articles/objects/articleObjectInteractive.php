<?php

class articleObjectInteractive extends articleObject {

    /**
     * @var Array
     */
    protected $text;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param Array $vals
     */
    public function fillMe($vals)
    {
        parent::fillMe($vals);
        if (isset($vals["article_id"])){
            $this->setArticleID($vals["article_id"]);
        }
        $this->setCategorieID($vals["categorie_id"]);
        $this->setTitle($vals["title"]);
        $this->setAlias($vals["alias"]);

        if (is_string($vals["text"])) {
            if (trim($vals["text"])!==""){
                $this->setText(json_decode($vals["text"],true));
            }
        }
        else if (is_array($vals["text"])){
            $this->setText($vals["text"]);
        }

        $this->setMeta($vals["meta"]);
        if (isset($vals["no"])){
            $this->setNo($vals["no"]);
        }
        if (isset($vals["active"])){
            $this->setActive($vals["active"]);
        }
        if (isset($vals["home"])) {
            $this->setHome($vals["home"]);
        }
        $this->setLang($vals["language"]);
        $this->setDescription($vals["description"]);
        if (isset($vals["author"])){
            $this->setAuthor($vals["author"]);
        }
        $this->setDate(new DateTime($vals["date"]));

        if (is_string($vals["options"])) {
            if (trim($vals["options"])!==""){
                $this->setData(json_decode($vals["options"],true));
            }
        }
        else if (is_array($vals["options"])){
            $this->setData($vals["options"]);
        }

    }

    /**
     * @param array $texts
     */
    public function setText($texts){
        $this->text = $texts;
    }

}