<?php

class sCMS_engine
{

    /**
     *
     * @var sCMS_db
     */
    public $dbase;      // DB Class

    /**
     *
     * @var sCMS_security
     */
    public $security;   // Security

    /**
     * @var users
     */
    public $users;

    /**
     * @var user_groups
     */
    public $user_groups;

    /**
     * @var events
     */
    public $events;

    public $type = 'standard';
    public $types = array("admin", "ajax", "standard", "rest");
    public $utils_list = array();
    public $core = array();
    //public $pages    = array();
    public $html = '';
public $settings;
        public $path = ''; // Settings variable
    protected $lang = '';
    protected $system_lang = '';
    private $data = array();
    private $table = 'core';
    private $table_pages = 'pages';

    /**
     * @param string $path
     * @param string $type
     * @param stdClass $cfg
     */
    public function __construct($path = '', $type = 'standard', stdClass $cfg)
    {
        $this->path = _ROOT_ . $path;
        $this->type = $type;
        $this->system_lang = 'lat';
        $this->settings = $cfg;
        $this->lang = $this->settings->general->lang;
    }

    // Set runtime variables

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }

    // Get runtime variables

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function get_lang()
    {
        return $this->lang;
    }

    public function set_lang($lang = 'lat')
    {
        $this->lang = $this->security->get_val($lang, sCMS_security::VAL_STRING);
    }

    public function get_system_lang()
    {
        return $this->system_lang;
    }

    public function set_system_lang($lang = 'lat')
    {
        $this->system_lang = $this->security->get_val($lang, sCMS_security::VAL_STRING);
    }

    /**
     * Get page settings from file
     * @param string $page
     * @param string $type
     */
    public function get_page_settings($page = '', $type = '')
    {
        $path = '';
        $template = $this->settings->general->template;
        switch ($type) {
            case "ajax":
                $path = _APP_DIR_ . 'ajax/';
                break;
            case "standard":
                $path = _ROOT_;
                break;
            case "admin":
                $path = _APP_ADMIN_DIR_;
                break;
            case "xml":
                $path = _APP_DIR_ . 'xml';
                break;
            case "rest":
                $path = $this->path . 'rest';
                break;
            case "mobile":
                $template = $this->settings->general->mobileTemplate;
                $path = _APP_DIR_ . 'mobile';
                break;
        }
        $filename = $path . 'pages/' . $page . '/settings/settings.xml';
        $template_filename = $path . 'templates/' . $template . '/pages/' . $page . '/settings/settings.xml';
        # check for template custom settings for selected page
        if (!file_exists($template_filename)) {
            if (file_exists($filename)) {
                $xml = simplexml_load_file($filename);
                return $xml->children();
            } else {
                return false;
            }
        } else {
            $xml = simplexml_load_file($template_filename);
            return $xml->children();
        }
    }

    /**
     * Get page settings from file
     * @param string $page
     * @param string $type
     */
    public function get_forced_page_settings($page = '', $type = '')
    {
        $path = '';
        switch ($type) {
            case "standard":
                $path = '';
                break;
            case "admin":
            case "xml":
            case "ajax":
                $path = '../';
                break;
        }
        $filename = $path . 'pages/' . $page . '/settings/forced_settings.xml';
        $template_filename = $path . 'templates/' . $this->settings->general->template . '/pages/' . $page . '/settings/forced_settings.xml';
        # check for template custom settings for selected page
        if (!file_exists($template_filename)) {
            if (file_exists($filename)) {
                $xml = simplexml_load_file($filename);
                return $xml->children();
            } else {
                return false;
            }
        } else {
            $xml = simplexml_load_file($template_filename);
            return $xml->children();
        }
    }

    /**
     * @param string $name
     */
    public function load_util($name)
    {
        // Load abstract
        require_once $this->path . 'core/abstract/util_class.php';
        // E-Mail
        if (in_array($name, $this->utils_list)) {
            if (!file_exists($this->path . 'utils/' . $name . '/' . $name . '_class.php')) {
                $this->log->write("0", "engine", "Tried to load util: " . $name . ", File doesn't exist.", 1);
                exit();
            }
            // Load Requested util
            include_once $this->path . 'utils/' . $name . '/' . $name . '_class.php';
            $name_s = 'util_' . $name;
            $name_c = $name . '_class';
            // Load class
            $this->$name_s = new $name_c();
            $this->$name_s->set_real_name($name);
            $this->log->write("0", "engine", "Loaded util: " . $name . ", util's registered.", 1);
        } else {
            $this->log->write("0", "engine", "Tried to load util: " . $name . ", util's not registered.", 1);
        }
    }

    /**
     * Calls page load function from page class
     * @param string $name
     *
     */
    public function load_page($name)
    {
        // Call load page from pages
        $this->pages->load_page($name);
    }

    /**
     *
     * @param array $string
     * @throws string
     *
     */
    public function import(array $array = array())
    {
        $allowed = array("pages", "utils");
        foreach ($array as $val) {
            $str = explode(".", $val);
            if (in_array($str[0], $allowed)) {
                $filename = $this->path . implode("/", $str) . "_class.php";
                if (file_exists($filename)) {
                    include_once $filename;
                } else {
                    echo "UNKNOWN: " . $filename;
                }
            } else {
                /**
                 * @todo write to log
                 */
            }
        }
    }

    public function __destruct()
    {
        // Close database
        //@$this->dbase->db_close();
    }

    public function init()
    {
        $this->load_core_important();
        $this->load_core_classes();
    }

    private function load_core_important()
    {
        // Include traits
        $this->load_traits();
        // Include interfaces
        $this->load_interfaces();
        // Abstract
        $this->load_abstract();
        // Include core files
        include_once $this->path . 'core/log/log_class.php';
        include_once $this->path . 'core/security/security_class.php';
        include_once $this->path . 'core/database/database_class.php';
        $this->core[] = "security";
        // Create log
        $this->log = new log();
        // Security
        $this->security = new sCMS_security();
        // set default lang
        $this->lang = $this->settings->general->lang;
        // Check website status
        if ($this->settings->general->locked == 1) {
            die('zakljucano');
        }
        // Start Database
        $this->startDB();
        // Unset settings
        //unset($this->settings);
    }

    /**
     *
     * Load all traits
     */
    public function load_traits()
    {
        $dir = $this->path . 'core/traits';
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filename = $dir . "/" . $file;
                    if (file_exists($filename) && !is_dir($filename)) {
                        include_once $filename;
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     *
     * Load all interfaces
     */
    public function load_interfaces()
    {
        $dir = $this->path . 'core/interfaces';
        $handle = opendir($dir);
        if ($handle) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filename = $dir . "/" . $file;
                    if (file_exists($filename) && !is_dir($filename)) {
                        include_once $filename;
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     *
     * Load all abstract
     */
    public function load_abstract()
    {
        $classes = array("page_class.php", "util_class.php");
        $dir = $this->path . 'core/abstract';
        foreach ($classes as $key => $val) {
            include_once $dir . "/" . $val;
        }
        $handle = opendir($dir);
        if ($handle) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filename = $dir . "/" . $file;
                    if (file_exists($filename) && !is_dir($filename)) {
                        include_once $filename;
                    }
                }
            }
            closedir($handle);
        }
    }

    protected function startDB()
    {
        // Create new database instance
        $this->dbase = new sCMS_db(
            (string)$this->settings->db->database, (string)$this->settings->db->server, (string)$this->settings->db->port, (string)$this->settings->db->user, (string)$this->settings->db->pass
        );
        // Connect to database
        $this->dbase->db_open();
    }

    private function load_core_classes()
    {
        $query = "SELECT * FROM utils WHERE active=1;";
        $this->dbase->query($query);
        foreach ($this->dbase->rows as $key => $val) {
            $this->utils_list[] = $val['name'];
        }
        $query = "SELECT * FROM " . $this->table . " WHERE active=1;";
        $this->dbase->query($query);
        // Load files
        foreach ($this->dbase->rows as $key => $val) {
            // Include core file
            include_once $this->path . 'core/' . $val['name'] . '/' . $val['name'] . '_class.php';
            $this->core[] = $val['name'];
        }
        // Create variables
        foreach ($this->dbase->rows as $key => $val) {
            $name = $val['name'];
            $this->$name = new $name();
        }
    }

    /**
     * @name ADD COOKIE PROPERTY FOR PAGE
     * @param string $page
     * @param array $property
     * @param string $type
     */
    public function add_cookie_property($page = '', $type = '', $property = array())
    {
        $string_array = array();
        $string_property = json_encode($property);
        @setcookie($page . $type, $string_property, time() + 3600, "/");
    }

    /**
     * @name READ COOKIE PROPERTY FOR PAGE
     * @param string $page
     * @param array $property
     *
     */
    public function read_cookie_property($page = '', $type = '')
    {
        if (isset($_COOKIE[$page . $type])) {
            $properties = $_COOKIE[$page . $type];
            $array = (array)json_decode(stripslashes($properties));
            return $array;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $page
     * @param string $name
     * @return boolean
     */
    public function loadController($page, $name)
    {
        global $engine;
        // Files
        $coreFile = $this->path . 'pages/' . $page . '/controller/' . $name . '.php';
        $templateFile = $this->path . 'templates/' . $this->settings->general->template . '/pages/' . $page . '/controller/' . $name . '.php';
        // Check file existance
        if (file_exists($templateFile)) {
            include_once $templateFile;
            return true;
        } elseif (file_exists($coreFile)) {
            include_once $coreFile;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get template from settings
     * @return string
     */
    public function getTemplate()
    {
        return $this->settings->general->template;
    }
}

?>