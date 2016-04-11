<?php
//die('ok.');
require_once 'init.cfg.php';
//die('ok.');
require _ROOT_ . 'includes/error_handle.php';

// Start session
session_start();
// SET ERROR REPORTING
date_default_timezone_set('Europe/Belgrade');
error_reporting(E_ALL);
ini_set('display_errors', '1');

//ini_set("log_errors", 1);
//ini_set("error_log", _ROOT_."/tmp/php-error.log");


// Define sCMS
define("_scms_", true);
// Define APP
define("_app_ingl_", true);
/**
 * @var sCMS_engine $engine
 */
global $engine;
// Start engine
include _ROOT_ . 'core/engine/engine_class.php';

require_once _APP_CFG_;

$engine = new sCMS_engine('', 'standard', $cfg);
$engine->init();                 // Initialize engine
// Remove sessions
$engine->users->remove_old_sessions();
// Load active user
$engine->users->loadActiveUser();
$engine->sef->_readRoute();
// LOAD PAGE
ob_start();
@$engine->parser->insertPage($engine->sef->sef_params['page']);
$html_page = ob_get_contents();
ob_end_clean();
// Full view of page
$type = "standard";
if (isset($engine->sef->sef_params['type'])) {
    $type = $engine->sef->sef_params['type'];
}
if ($type !== 'clean') {
    // Start OB
    ob_start();
    $engine->events->dispatchEvent("main", "startedObStart");
    require_once(_APP_DIR_ . "templates/" . $engine->settings->general->template . "/index.php");
    $engine->html = ob_get_contents();
    ob_end_clean();
    $engine->events->dispatchEvent("main", "BeforeEvent", "");
    // Setup page
    $engine->html = preg_replace("/<scms:page>/", $html_page, $engine->html);
    $engine->html = $engine->widgets->place_widgets($engine->html);
    // Print HTML FILE
    print $engine->language->cirToLat($engine->html);
} // Clean view of page
else {
    // Place widgets
    $engine->html = $html_page;
    // Setup page
    $engine->html = preg_replace("/<scms:page>/", $html_page, $engine->html);
    // Place widgets
    $engine->html = $engine->widgets->place_widgets($engine->html);
    // Print HTML FILE
    print $engine->language->cirToLat($engine->html);
}
// Close DB
$engine->dbase->db_close();

