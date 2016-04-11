<?php

/**
 * Parser
 */
class parser
{

    // Standard variables
    public $title = '';
    public $description = '';
    public $keywords = '';
    public $robots = 'index.php,follow';
    public $content = '';
    public $icon = '';
    public $server = '';
    public $scripts = array();
    protected $styles = array();

    protected $lastModified = 0;

    // Open graph variables
    protected $ogTitle = '';
    protected $ogDescription = '';
    protected $ogType = '';
    protected $ogImage = '';
    protected $ogSiteName = '';
    protected $ogUrl = '';

    protected $engine;

    // Parse types
    const dataTypeHTML = "";
    const dataTypeXML = "xml";
    const dataTypeJSON = "json";

    public function __construct()
    {
        global $engine;
        $this->engine =& $engine;
        // Setup varialbes
        $this->title = $this->engine->settings->general->title;
        $this->description = $this->engine->settings->general->description;
        $this->keywords = $this->engine->settings->general->keywords;
        $this->robots = $this->engine->settings->general->robots;
        $this->icon = $this->engine->settings->general->icon;
        $this->server = $this->engine->settings->general->server;
        // Set Opeh graph
        $this->setOgSiteName($this->engine->settings->general->site);
        $this->setOgDescription($this->engine->settings->general->description);
        $this->setOgTitle($this->engine->settings->general->title);
        $this->setOgUrl($this->engine->settings->rss->imageLink . $_SERVER['REQUEST_URI']);
        $this->setOgType('website');
       //$this->setOgImage($this->engine->settings->rss->imageLink . $this->engine->settings->rss->imageURL);
        $this->lastModified = time();
    }

    public function setLastModified($timestamp)
    {
        $this->lastModified = $timestamp;
    }

    public function getLastModified()
    {
        return $this->lastModified;
    }

    public function addStyle($path, $media = "screen")
    {
        $i = count($this->styles);
        $this->styles[$i]['path'] = $path;
        $this->styles[$i]['media'] = $media;
    }

    public function setOgSiteName($siteName)
    {
        $this->ogSiteName = $siteName;
    }

    public function setOgTitle($title)
    {
        $this->ogTitle = $title;
    }

    public function getOgTitle()
    {
        return $this->ogTitle;
    }

    public function setOgDescription($description)
    {
        $this->ogDescription = $description;
    }

    public function getOgDescription()
    {
        return $this->ogDescription;
    }

    public function setOgImage($imageUrl)
    {
        $this->ogImage = $imageUrl;
    }

    public function getOgImage()
    {
        return $this->ogImage;
    }

    public function setOgType($type)
    {
        $this->ogType = $type;
    }

    public function getOgType()
    {
        return $this->ogType;
    }

    public function setOgUrl($url)
    {
        $this->ogUrl = $url;
    }

    public function getOgUrl()
    {
        return $this->ogUrl;
    }

    public function createHead()
    {
        global $engine;
        // include HEAD File
        include $engine->path . 'core/parser/html/head.php';
    }

    /**
     *
     * @param string $page
     */
    public function insertPage($page = '', $type = 'pages')
    {
        global $engine;
        if (trim($page) == '' || $page == 'index.php.html' || $page == 'index.php') {
            $page = 'home';
        } else {
            $page = $engine->sef->sef_params['page'];
        }
        switch ($type) {
            case "admin":
                $template = 'admintemplate';
                $dir = 'templates/' . $engine->settings->general->adminTemplate . '/pages/' . $page . '/index.php';
                break;
            case "mobile":
                $template = 'mobiletemplate';
                $dir = 'templates/' . $engine->settings->general->mobiletemplate . '/pages/' . $page . '/index.php';
                break;
            case "ajax":
                $template = '';
                $dir = 'pages/' . $page . '/index.php';
                break;
            case "rest":
                $template = '';
                $dir = 'pages/' . $page . '/index.php';
                break;
            default:
                $template = 'template';
                // check for existance of custom designed page
                $dir = _APP_DIR_ . 'templates/' . $engine->settings->general->template . '/pages/' . $page . '/index.php';
                break;
        }
        if (file_exists($dir)) {
            include $dir;
        } else {
            if (file_exists('pages/' . $page . '/index.php')) {
                include 'pages/' . $page . '/index.php';
            } else {
                echo $dir;
                //header ("location: /error/404/");
            }
        }
    }

    public function createFooter()
    {
    }

    public function generatePages($num_of_pages = 0, $default = true, $mixed_link = '', $view_type = 'pages')
    {
        global $engine;
        if ($num_of_pages > 1) {
            include $engine->path . 'core/parser/html/' . $view_type . '.php';
        }
    }

    /**
     *
     * Create RSS XML File
     * @param array $elements
     * @param string $filename
     *
     */
    public function createRSS($logo = '', $title = '', $lang = '', $filename = '', $elements = array())
    {
        global $engine;
        // set current date
        $date = $engine->calendar->datum_vreme_usa_DATE_RFC822(date("Y-m-d H:m:s"));
        # Generisemo RSS
        $rss = "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n
               <channel>\n
               <atom:link href=\"http://" . $engine->settings->general->site .
            "/rss.php\" rel=\"self\" type=\"application/rss+xml\" />\n
		<title>" . $title . "</title>\n
		<description></description>\n
		<link>http://" . $engine->settings->general->site . "</link>\n
		<image><title>" . $title . "</title>\n
        <url>" . $logo . "</url>\n
        <link>http://" . $engine->settings->general->site . "</link></image>\n
		<language>" . $lang . "</language>\n
    	<pubDate>" . $date . "</pubDate>\n
    	<lastBuildDate>" . $date . "</lastBuildDate>\n
    	<generator>sCMS ver 2.0</generator>\n
    	<managingEditor>" . $engine->settings->mail->fromMail .
            " (webmaster)</managingEditor>\n
    	<webMaster>" . $engine->settings->mail->fromMail . " (webmaster)</webMaster>\n";
        // Go through elements
        foreach ($elements as $key_e => $val_e) {
            $i++;
            $rss .= "<item>\n";
            $rss .= "<title>\n<![CDATA[ ";
            $rss .= $val_e['title'] . "\n";
            $rss .= "]]></title>\n";
            $rss .= "<description>\n<![CDATA[ ";
            $rss .= $val_e['description'] . "\n";
            $rss .= "]]></description>\n";
            $rss .= "<link>" . $val_e['link'] . "</link>\n";
            $rss .= "<guid>" . $val_e['link'] . "</guid>\n";
            $rss .= "<pubDate>" . $engine->calendar->datum_vreme_usa_DATE_RFC822($val_e['date']) . "</pubDate>\n";
            $rss .= "</item>\n";
        }
        $rss .= "</channel>\n";
        $rss .= "</rss>\n";
        // write rss file
        $myFile = "xml/rss/" . $filename;
        $fh = fopen($myFile, 'w') or die("can't open file");
        $stringData = $rss;
        fwrite($fh, $stringData);
        fclose($fh);
    }

    public function createSiteMap($elements)
    {
    }

    /**
     * Form parse
     * @name Form parser
     * @param string $type
     * @param string $selected
     * @param string $ajax_call
     * @param string $id
     * @param string $name
     * @param string $class
     * */
    public function parse_form_element($type, $value, $ajax_call, $id, $name, $classname, $selected = '', $advanced = array())
    {
        global $engine;
        $advanced_params = array();
        foreach ($advanced as $key => $val) {
            $advanced_params[] = $key . '="' . $val . '"';
        }
        $advanced_params = implode(" ", $advanced_params);
        include $engine->path . 'core/parser/html/form_parser/' . $type . '.php';
    }

    /*
      Parse
     */
    public function parseOptions($settings)
    {
        global $engine;
        include $engine->path . 'core/parser/html/options_edit.php';
    }

    /**
     *
     * @param array $options
     * @param array $loadedValues
     * @return string
     */
    public function parseInfo($options, $loadedValues = array())
    {
        ob_start();
        foreach ($options->option as $option) {
            switch ($option->fieldType) {
                case "select":
                    $this->_parseInfoSelectElement($option, $loadedValues);
                    break;
                case "text":
                    $this->_parseInfoTextElement($option, $loadedValues);
                    break;
                case "textarea":
                    $this->_parseInfoTextareaElement($option, $loadedValues);
                    break;
                case "checkbox":
                    $this->_parseInfoCheckBoxElement($option, $loadedValues);
                    break;
            }
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    protected function _parseInfoSelectElement($option, $loadedValues = array())
    {
        include $this->engine->path . 'core/parser/html/infoParse/select.php';
    }

    protected function _parseInfoTextElement($option)
    {
    }

    protected function _parseInfoTextareaElement($option)
    {
    }

    protected function _parseInfoCheckBoxElement($option)
    {
    }

    protected function _parseInfoRadioElement()
    {
    }
}

?>