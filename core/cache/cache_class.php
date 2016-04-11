<?php

/**
 *
 * Caching data
 *
 */
class cache
{

    /**
     *
     * @var timestamp
     */
    protected $expires;

    /**
     *
     * @var timestamp
     */
    protected $created;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var mixed
     */
    protected $content;

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $extension = '.scache';

    /**
     *
     * @var array
     */
    protected $specParams = array();

    /**
     *
     * @var string
     */
    protected $cache_dir = 'cache/';

    public function __construct()
    {
        global $engine;
        $this->cache_dir = $engine->path . $this->cache_dir;
    }

    /**
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param string $name
     */
    public function setSpecParams($specParams)
    {
        $this->specParams = $specParams;
    }

    /**
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     *
     * @param string $type
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getCacheDir()
    {
        return $this->cache_dir;
    }

    public function setCacheDir($cacheDIR){
        $this->cache_dir = $cacheDIR;
    }

    /**
     * Add cache
     *
     */
    public function addCache()
    {
        if (!file_exists($this->cache_dir)) {
            mkdir($this->cache_dir, true, 0775);
        }
        $data = json_encode(array(
            "expires" => $this->expires,
            "created" => time(),
            "type" => $this->type,
            "content" => $this->content,
            "specParams" => $this->specParams
        ));
        $filename = $this->cache_dir . $this->calculateName() . $this->extension;
        if (file_exists($filename)) {
            chmod($filename, 0775);
        }
        // Filename
        $f = fopen($filename, "w");
        fwrite($f, $data);
        fclose($f);
        // Change chmod
        @chmod($filename, 0500);
    }

    /**
     *
     * @return string
     */
    public function calculateName()
    {
        return md5($this->id . $this->name);
    }

    /**
     *
     * @return string
     */
    public function getCache()
    {
        // Filename
        $filename = $this->cache_dir . $this->calculateName() . $this->extension;
        // Is cache dir
        if (is_dir($this->cache_dir)) {
            if (is_file($filename)) {
                $data = (array)json_decode(file_get_contents($filename),true);
                //var_dump($data);
                if ($this->_checkExpired($data)) {
                    return array(
                        "content" => $data['content'],
                        "specParams" => $data['specParams']
                    );
                } else {
                    $this->deleteCache($filename);
                }
            }
        }
    }

    /**
     *
     * @param array $data
     * @return boolean
     */
    protected function _checkExpired($data)
    {
        if ($data['expires'] > time()) {
            return true;
        } else {
            //$this->deleteCache();
            return false;
        }
    }

    /**
     *
     * @param string $cacheName
     */
    public function deleteCache($cacheName)
    {
        if (file_exists($cacheName)) {
            chmod($cacheName, 0775);
            unlink($cacheName);
        }
    }

    public function __destruct()
    {
    }
}

?>