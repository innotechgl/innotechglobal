
<?php

// Set error reporting
error_reporting(E_ALL);
ini_set('error_reporting', 1);

// Load init config
require_once "init.cfg.php";

// Error handle
require _ROOT_.'includes/error_handle.php';

// Start session
session_start();

// Set JSON header
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json; charset=utf-8');

define("_scms_rest_", true);
define("_app_ingl_", true);

if ($_SERVER['REQUEST_METHOD']=="OPTIONS"){
    die('OK.');
}



/**
 * @var sCMS_engine $engine
 */
global $engine;

require _ROOT_."core/rest/rest_class.php";
require _ROOT_."core/engine/engine_class.php";

require _APP_CFG_;

$engine = new sCMS_engine("", 'rest', $cfg);
$engine->init(); // Initialize engine

// UPDATE activity
$engine->users->remove_old_sessions();

// Load active user
$engine->users->loadActiveUser();

$engine->load_page('updater');

// Load user if token exists
if (isset($_GET['token'])) {
    $token = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING);
    $uid = $engine->users->getUserIDByToken($token);
    if ($uid !== false) {
        $engine->users->active_user->load_user($uid);
    }
    else if ($_GET["page"]=="users" && $_GET["task"]=="checkToken"){
        die(json_encode(array("data"=>array("BAD TOKEN!"))));
    }
    else {
        die("User doesn't exists.");
    }
}

// Read init route
$engine->sef->readRoute();

// Set language
$engine->language->set_language();

// Load Events for utils
$engine->events->loadEvents('utils', $engine->utils_list);

// Load Events for pages
$engine->events->loadEvents('pages', $engine->pages->pages);

// Start event
$engine->events->dispatchEvent("main", "startedObStart");

/**
 * @todo Insert check for secured
 */
$engine->parser->insertPage($engine->sef->sef_params['page'], 'rest');

// Close DB
$engine->dbase->db_close();