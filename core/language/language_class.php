<?php

class language
{

    public $id = 0;
    public $name = '';
    public $short_name = '';
    public $active = 0;
    public $created = '';
    public $creator = 0;
    public $modified = '';
    public $modifier = 0;
    public $check_for_duplicates = true;
    public $table = 'language';
    public $page = 'language';
    protected $engine;

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;

        // INCLUDE LANGUAGE PACK
        include_once $this->engine->path . 'language/core/' . $this->page . '/' . $this->page . '_lat.php';
        $lang_class = $this->page . "_language";
        $this->lang = new $lang_class();
    }

    /**
     *
     * Sets main language
     *
     */
    public function set_language()
    {
        global $engine;
        if (isset($engine->sef->sef_params['lang'])) {
            $engine->set_lang($engine->sef->sef_params['lang']);
            $engine->add_cookie_property('lang', $engine->type, array("lang" => $engine->get_lang()));
        } else {
            $lang = $engine->read_cookie_property('lang', $engine->type);
            if (isset($lang['lang'])) {
                $engine->set_lang($lang['lang']);
            } else {
                $engine->set_lang($engine->settings->general->lang);
            }
        }
    }

    public function add()
    {
        global $engine;
        $requested = array("name", "short_name", "active");
        $vars = $engine->security->get_vals($requested);
        // Set variables
        $this->name = (string)$vars['name'];
        $this->short_name = (string)$vars['short_name'];
        $this->active = (int)$vars['active'];
        $this->creator = (int)$engine->users->get_id();
        if ($this->check_duplicated($this->name)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table . "
            (name, short_name, active, created, creator) VALUES
            ('" . $this->name . "', '" . $this->short_name . "','" . $this->active . "', NOW(), '" . $this->creator . "');";
        //
        $this->id = $engine->dbase->insertQuery($query);
        if ($this->id > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * INSERT NEW
     */

    /**
     *
     * @param string $name
     * @return boolean
     */
    private function check_duplicated($name = '')
    {
        if ($this->check_for_duplicates === false) {
            return false;
        }
        $array = $this->get_array(false, 'id', 'WHERE name LIKE "' . $name . '"');
        if (count($array) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $addopt
     * @param string $what_to_get
     * @param string $filter_params
     * @param string $order_by
     * @param string $order_direction
     * @return array
     */
    public function get_array($addopt = true, $what_to_get = '*', $filter_params = '', $order_by = 'id', $order_direction = 'ASC')
    {
        global $engine;
        $query = "SELECT " . $what_to_get . "
                  FROM " . $this->table . " " . $filter_params . "
                  ORDER BY " . $order_by . " " . $order_direction;
        $engine->dbase->query($query, $addopt, true);
        return $engine->dbase->rows;
    }

    /*
     * UPDATE
     */

    public function update()
    {
        global $engine;
        $requested = array("name", "short_name", "active");
        $vars = $engine->security->get_vals($requested);
        // Set variables
        $this->name = (string)$vars['name'];
        $this->short_name = (string)$vars['short_name'];
        $this->active = (int)$vars['active'];
        $this->modifier = (int)$engine->users->get_id();
        $this->id = $engine->sef->sef_params['id'];
        $query = "UPDATE " . $this->table . "
            SET name='" . $this->name . "',
                short_name='" . $this->short_name . "',
                active='" . $this->active . "',
                modified=NOW(),
                modifier='" . $this->modifier . "'
                WHERE id=" . $this->id . " LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * DELETE
     */
    public function delete($id = NULL)
    {
        global $engine;
        if ($id == NULL) {
            return false;
            exit();
        }
        if (is_array($id) && count($id) > 0) {
            $this->id = implode(",", $id);
        } else {
            $this->id = $id;
        }
        $query = "DELETE FROM $this->table WHERE
                id=" . $this->id . " LIMIT 1";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivate($id = NULL)
    {
        global $engine;
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=0 WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param mixed $id
     * @name Activate user account
     *
     */
    public function activate($id = NULL)
    {
        global $engine;
        // Set update limit
        $count = 0;
        // Check status of $id
        if ($id == NULL) {
            return false;
            exit();
        }
        // Check type of $id
        if (is_array($id)) {
            $this->id = implode(",", $id);
            $count = count($id);
        } else {
            $this->id = $id;
            $count = 1;
        }
        $query = "UPDATE " . $this->table . " SET active=1 WHERE id IN (" . $this->id . ") LIMIT " . $count . ";";
        if ($engine->dbase->insertQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert Cyr To lat
     */
    public function cirToLat($tekst = '', $force = false)
    {
        global $engine;
        if ($engine->get_lang() == 'lat' || $force == true) {
            $tekst = str_replace("љ", "lj", $tekst);
            $tekst = str_replace("њ", "nj", $tekst);
            $tekst = str_replace("џ", "dž", $tekst);
            $tekst = str_replace("а", "a", $tekst);
            $tekst = str_replace("б", "b", $tekst);
            $tekst = str_replace("в", "v", $tekst);
            $tekst = str_replace("г", "g", $tekst);
            $tekst = str_replace("д", "d", $tekst);
            $tekst = str_replace("ђ", "đ", $tekst);
            $tekst = str_replace("е", "e", $tekst);
            $tekst = str_replace("ж", "ž", $tekst);
            $tekst = str_replace("з", "z", $tekst);
            $tekst = str_replace("и", "i", $tekst);
            $tekst = str_replace("ј", "j", $tekst);
            $tekst = str_replace("к", "k", $tekst);
            $tekst = str_replace("л", "l", $tekst);
            $tekst = str_replace("м", "m", $tekst);
            $tekst = str_replace("н", "n", $tekst);
            $tekst = str_replace("о", "o", $tekst);
            $tekst = str_replace("п", "p", $tekst);
            $tekst = str_replace("р", "r", $tekst);
            $tekst = str_replace("с", "s", $tekst);
            $tekst = str_replace("т", "t", $tekst);
            $tekst = str_replace("ћ", "ć", $tekst);
            $tekst = str_replace("у", "u", $tekst);
            $tekst = str_replace("ф", "f", $tekst);
            $tekst = str_replace("х", "h", $tekst);
            $tekst = str_replace("ц", "c", $tekst);
            $tekst = str_replace("ч", "č", $tekst);
            $tekst = str_replace("ш", "š", $tekst);
            # VELIKA SLOVA
            $tekst = str_replace("Љ", "LJ", $tekst);
            $tekst = str_replace("Њ", "NJ", $tekst);
            $tekst = str_replace("Џ", "DŽ", $tekst);
            $tekst = str_replace("А", "A", $tekst);
            $tekst = str_replace("Б", "B", $tekst);
            $tekst = str_replace("В", "V", $tekst);
            $tekst = str_replace("Г", "G", $tekst);
            $tekst = str_replace("Д", "D", $tekst);
            $tekst = str_replace("Ђ", "Đ", $tekst);
            $tekst = str_replace("Е", "E", $tekst);
            $tekst = str_replace("Ж", "Ž", $tekst);
            $tekst = str_replace("З", "Z", $tekst);
            $tekst = str_replace("И", "I", $tekst);
            $tekst = str_replace("Ј", "J", $tekst);
            $tekst = str_replace("К", "K", $tekst);
            $tekst = str_replace("Л", "L", $tekst);
            $tekst = str_replace("М", "M", $tekst);
            $tekst = str_replace("Н", "N", $tekst);
            $tekst = str_replace("О", "O", $tekst);
            $tekst = str_replace("П", "P", $tekst);
            $tekst = str_replace("Р", "R", $tekst);
            $tekst = str_replace("С", "S", $tekst);
            $tekst = str_replace("Т", "T", $tekst);
            $tekst = str_replace("Ћ", "Ć", $tekst);
            $tekst = str_replace("У", "U", $tekst);
            $tekst = str_replace("Ф", "F", $tekst);
            $tekst = str_replace("Х", "H", $tekst);
            $tekst = str_replace("Ц", "C", $tekst);
            $tekst = str_replace("Ч", "Č", $tekst);
            $tekst = str_replace("Ш", "Š", $tekst);
        }
        return $tekst;
    }

    /**
     * Convert Cyr To lat
     */
    public function latToCir($tekst = '')
    {
        global $engine;
        $tekst = str_replace("lj", "љ", $tekst);
        $tekst = str_replace("nj", "њ", $tekst);
        $tekst = str_replace("dž", "џ", $tekst);
        $tekst = str_replace("a", "а", $tekst);
        $tekst = str_replace("b", "б", $tekst);
        $tekst = str_replace("v", "в", $tekst);
        $tekst = str_replace("g", "г", $tekst);
        $tekst = str_replace("d", "д", $tekst);
        $tekst = str_replace("đ", "ђ", $tekst);
        $tekst = str_replace("e", "е", $tekst);
        $tekst = str_replace("ž", "ж", $tekst);
        $tekst = str_replace("z", "з", $tekst);
        $tekst = str_replace("i", "и", $tekst);
        $tekst = str_replace("j", "ј", $tekst);
        $tekst = str_replace("k", "к", $tekst);
        $tekst = str_replace("l", "л", $tekst);
        $tekst = str_replace("m", "м", $tekst);
        $tekst = str_replace("n", "н", $tekst);
        $tekst = str_replace("o", "о", $tekst);
        $tekst = str_replace("p", "п", $tekst);
        $tekst = str_replace("r", "р", $tekst);
        $tekst = str_replace("s", "с", $tekst);
        $tekst = str_replace("t", "т", $tekst);
        $tekst = str_replace("ć", "ћ", $tekst);
        $tekst = str_replace("u", "у", $tekst);
        $tekst = str_replace("f", "ф", $tekst);
        $tekst = str_replace("h", "х", $tekst);
        $tekst = str_replace("c", "ц", $tekst);
        $tekst = str_replace("č", "ч", $tekst);
        $tekst = str_replace("š", "ш", $tekst);
        # VELIKA SLOVA
        $tekst = str_replace("LJ", "Љ", $tekst);
        $tekst = str_replace("NJ", "Њ", $tekst);
        $tekst = str_replace("DŽ", "Џ", $tekst);
        $tekst = str_replace("A", "А", $tekst);
        $tekst = str_replace("B", "Б", $tekst);
        $tekst = str_replace("V", "В", $tekst);
        $tekst = str_replace("G", "Г", $tekst);
        $tekst = str_replace("D", "Д", $tekst);
        $tekst = str_replace("Đ", "Ђ", $tekst);
        $tekst = str_replace("E", "Е", $tekst);
        $tekst = str_replace("Ž", "Ж", $tekst);
        $tekst = str_replace("Z", "З", $tekst);
        $tekst = str_replace("I", "И", $tekst);
        $tekst = str_replace("J", "Ј", $tekst);
        $tekst = str_replace("K", "К", $tekst);
        $tekst = str_replace("L", "Л", $tekst);
        $tekst = str_replace("M", "М", $tekst);
        $tekst = str_replace("N", "Н", $tekst);
        $tekst = str_replace("O", "О", $tekst);
        $tekst = str_replace("P", "П", $tekst);
        $tekst = str_replace("R", "Р", $tekst);
        $tekst = str_replace("S", "С", $tekst);
        $tekst = str_replace("T", "Т", $tekst);
        $tekst = str_replace("Ć", "Ћ", $tekst);
        $tekst = str_replace("U", "У", $tekst);
        $tekst = str_replace("F", "Ф", $tekst);
        $tekst = str_replace("H", "Х", $tekst);
        $tekst = str_replace("C", "Ц", $tekst);
        $tekst = str_replace("Č", "Ч", $tekst);
        $tekst = str_replace("Š", "Ш", $tekst);
        return $tekst;
    }
}

?>
