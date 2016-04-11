<?php

class search
{

    public $lang;
    public $page = 'search';
    public $found = Array();
    public $numOfResultsPerSearch = 10;
    private $engine;
    private $min_chars = 5;

    public function  __construct()
    {
        global $engine;
        $default_language = $engine->path . 'language/core/search/search_eng.php';
        $try_language = $engine->path . 'language/core/search/search_' . $engine->get_lang() . '.php';
        if (file_exists($try_language)) {
            include $try_language;
        } else {
            include $default_language;
        }
        $this->lang = new search_language();
    }

    /**
     * Sets min num of character for search
     * @param int $num
     *
     */
    public function set_min_chars($num)
    {
        $this->min_chars = (int)$num;
    }

    /**
     * Load all search pages and get results
     * @param array filter
     * @return bool
     */
    public function get_search($filter = null, $searchFor, $ordering)
    {
        global $engine;
        if (strlen($searchFor) >= $this->min_chars) {
            foreach ($filter as $value) {
                $engine->load_page($value);
                $engine->$value->search($searchFor, $ordering);
            }
            return true;
        } else {
            $engine->log->add_error_log('search',
                'Minimum entered characters should be ' .
                $this->min_chars . "+");
            return false;
        }
    }

    /**
     * Get pages with search options
     * @return array
     */
    public function get_pages()
    {
        global $engine;
        $search_pages = '';
        $loaded_pages = $engine->pages;
        foreach ($loaded_pages->pages as $value) {
            $filename = "pages/" . $value . "/search.php";
            if (file_exists($filename)) {
                $search_pages[] = $value;
            }
        }
        return $search_pages;
    }

    public function getNumOfFoundElements()
    {
        $num = 0;
        foreach ($this->found as $val) {
            // print_r($val);
            $num += $val['info']['num_of_rows'];
        }
        return $num;
    }
}
