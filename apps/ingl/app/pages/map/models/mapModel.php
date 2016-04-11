<?php
class mapModel extends googleMaps_class
{

    /**
     * @var Array
     */
    protected $articles;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param DateTime $sinceDate
     * @return array
     */
    public function getAll(DateTime $sinceDate){

        $query = "SELECT
googlemaps.id,
article_categories.id AS category_id,
article_categories.`name` AS category_name,
googlemaps.lat,
googlemaps.lng,
googlemaps.title,
googlemaps.icon,
googlemaps.rel_id,
googlemaps.rel_page,
googlemaps.description,
googlemaps.lang
FROM
`".$this->table."`
LEFT JOIN articles ON articles.id = googlemaps.rel_id
LEFT JOIN article_categories ON article_categories.id = articles.categorie_id
WHERE
googlemaps.modified > '".$sinceDate->format("Y-m-d h:i:s")."'";

        $this->engine->dbase->query($query);

        return $this->engine->dbase->rows;

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