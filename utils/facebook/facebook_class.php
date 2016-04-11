<?php
global $engine;
# Require FB Class
require_once $engine->path . 'includes/facebook/base_facebook.php';
require_once $engine->path . 'includes/facebook/facebook.php';

# FB Class
class facebook_class extends util_class
{

    public $fb;
    public $config = array();

    public function __construct()
    {
        global $engine;
        $this->config = array(
            'appId' => (string)$engine->settings->fb->AppID, // $engine->settings->fb->appId
            'secret' => (string)$engine->settings->fb->AppSecret, // $engine->settings->fb->secret
            'cookie' => false
        );
        $this->fb = new Facebook($this->config);
    }

    public function init()
    {
    }
}

?>