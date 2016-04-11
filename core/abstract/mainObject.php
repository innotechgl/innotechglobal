<?php

abstract class mainObject
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $creator;
    /**
     * @var DateTime
     */
    protected $created;
    /**
     * @var int
     */
    protected $modifier;
    /**
     * @var DateTime
     */
    protected $modified;

    /**
     * @var Str
     */
    protected $lang;

    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;

        $this->created = new DateTime();
        $this->modified = new DateTime();
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setID($id)
    {
        $this->id = (int)$id;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = (int)$creator;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }

    public function getModifier()
    {
        return $this->modifier;
    }

    public function setModifier($modifier)
    {
        $this->modifier = (int)$modifier;
    }

    /**
     * @return DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param DateTime $modified
     */
    public function setModified(DateTime $modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return Str
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param Str $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return array
     */
    public function __getArray()
    {
        $array = array();
        $array["id"] = $this->id;
        $array["creator"] = $this->creator;
        $array["created"] = $this->created->format("Y-m-d h:i:s");
        $array["modifier"] = $this->modifier;
        $array["modified"] = $this->modified->format("Y-m-d h:i:s");
        $array["lang"] = $this->lang;
        return $array;
    }

    /**
     * @param Array $vals
     */
    public function fillMe($vals)
    {
        if (isset($vals["id"])) {
            $this->setID($vals["id"]);
        }
        if (isset($vals["creator"])) {
            $this->setCreator($vals["creator"]);
        }
        if (isset($vals["modified"])) {
            $this->setModified(new DateTime($vals["modified"]));
        }
        if (isset($vals["created"])) {
            $this->setCreated(new DateTime($vals["created"]));
        }
        if (isset($vals["lang"])) {
            $this->setLang($vals["lang"]);
        }
        if (isset($vals["modifier"])) {
            $this->setModifier($vals["modifier"]);
        }
    }
}