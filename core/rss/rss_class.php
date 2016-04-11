<?php

class rss
{

    // db
    public $id;
    public $title;
    public $name;
    public $options;
    public $refresh_time;

    // Header elements
    public $header_title = '';
    public $header_link = '';
    public $header_description = '';
    public $header_language = '';
    public $header_copyright = '';
    public $header_pubDate = ''; // Creation time and date
    public $header_ttl = 10;
    public $header_image_title = '';
    public $header_image_link = '';
    public $header_image_url = '';
    public $header_image_width = '';
    public $header_image_height = '';
    public $header_image_description = '';

    // Elements
    public $element_title = '';
    public $element_guid = '';
    public $element_link = '';
    public $element_description = '';
    public $element_pubDate = '';

    // xml document
    private $doc;
    private $header;
    private $channel;
    private $item;

    private $table = 'rss';

    public function __construct()
    {
        global $engine;
        $this->header_title = $engine->settings->general->title;
        $this->header_link = "http://" . $engine->settings->general->site;
        $this->header_description = $engine->settings->general->description;
        $this->header_language = $engine->settings->general->language;
        $this->header_copyright = $engine->settings->general->copyright;
        $this->header_pubDate = date('D, d M Y H:i:s O');
    }

    public function init()
    {
        $this->doc = new DomDocument('1.0', 'utf-8');
        // set rss definition
        $rss = $this->doc->createElement("rss");
        $rss->setAttribute("xmlns:media", "http://search.yahoo.com/mrss/");
        $rss->setAttribute("version", "2.0");
        $this->doc->appendChild($rss);
        $this->channel = $this->doc->createElement('channel');
    }

    public function setHeader()
    {
        /* title */
        $hel_title = $this->doc->createElement('title', $this->header_title);
        $hel_link = $this->doc->createElement('link', $this->header_link);
        $hel_description = $this->doc->createElement('description', $this->header_description);
        $hel_language = $this->doc->createElement('language', $this->header_language);
        $hel_copyright = $this->doc->createElement('copyright', $this->header_copyright);
        $hel_pubDate = $this->doc->createElement('pubDate', $this->header_pubDate);
        $hel_ttl = $this->doc->createElement('ttl', $this->header_ttl);
        // Append children
        $this->channel->appendChild($hel_title);
        $this->channel->appendChild($hel_link);
        $this->channel->appendChild($hel_description);
        $this->channel->appendChild($hel_language);
        $this->channel->appendChild($hel_copyright);
        $this->channel->appendChild($hel_pubDate);
        $this->channel->appendChild($hel_ttl);
        // Set image
        $image = $this->doc->createElement('image');
        $iel_title = $this->doc->createElement('title', $this->header_image_title);
        $iel_link = $this->doc->createElement('link', $this->header_image_link);
        $iel_url = $this->doc->createElement('url', $this->header_image_url);
        $iel_width = $this->doc->createElement('width', $this->header_image_width);
        $iel_height = $this->doc->createElement('height', $this->header_image_height);
        $iel_description = $this->doc->createElement('description', $this->header_image_description);
        // Append elements to image
        $image->appendChild($iel_title);
        $image->appendChild($iel_link);
        $image->appendChild($iel_url);
        $image->appendChild($iel_width);
        $image->appendChild($iel_height);
        $image->appendChild($iel_description);
        $this->doc->appendChild($image);
    }

    public function add_item()
    {
        // Create item
        $this->item = $this->doc->createElement("item");
        // Fill item
        $this->fill_item();
        // Append item
        $this->channel->appendChild($this->item);
    }

    public function fill_item()
    {
        $el_title = $this->doc->createElement('title', $this->element_title);
        $el_guid = $this->doc->createElement('guid', $this->element_guid);
        $el_link = $this->doc->createElement('link', $this->element_link);
        $el_description = $this->doc->createElement('description', $this->element_description);
        $el_pubDate = $this->doc->createElement('pubDate', $this->element_pubDate);
        // Insert elements
        $this->item->appendChild($el_title);
        $this->item->appendChild($el_guid);
        $this->item->appendChild($el_link);
        $this->item->appendChild($el_description);
        $this->item->appendChild($el_pubDate);
    }

    public function generate()
    {
        $string = $this->doc->saveXML();
        print($string);
    }

    public function check_last_publish()
    {
    }
}

?>