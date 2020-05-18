<?php
if (!isset($_SESSION)) {
    session_start();
}

// Allow back button without reposting data
header("Cache-Control: private, no-cache, no-store, proxy-revalidate");
//session_cache_limiter("private_no_expire");
date_default_timezone_set('America/Los_Angeles');

$error_msg = [];
$query_msg = [];
$showQueries = false; 
$showCounts = false; 
$dumpResults = false;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')           
    define("SEPARATOR", "\\");
else 
    define("SEPARATOR", "/");

//show cause of HTTP : 500 Internal Server Error
error_reporting(E_ALL);
ini_set('display_errors', 'off');
ini_set("log_errors", 'on');
ini_set("error_log", getcwd() . SEPARATOR ."error.log");

define('NEWLINE',  '<br>' );
define('REFRESH_TIME', 'Refresh: 1; ');

$encodedStr = basename($_SERVER['REQUEST_URI']); 
//convert '%40' to '@'  example: request_friend.php?friendemail=pam@dundermifflin.com
$current_filename = urldecode($encodedStr);
	
if($showQueries){
    array_push($query_msg, "<b>Current filename: ". $current_filename . "</b>"); 
}

define('DB_HOST', "localhost");
define('DB_PORT', "3308");
define('DB_USER', "web");
define('DB_PASS', "final");
define('DB_SCHEMA', "final");

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA, DB_PORT);

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error() . NEWLINE;
    echo "Running on: ". DB_HOST . ":". DB_PORT . '<br>' . "Username: " . DB_USER . '<br>' . "Password: " . DB_PASS . '<br>' ."Database: " . DB_SCHEMA;
    //phpinfo();   //unsafe, but verbose for learning.
    exit();
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function getActiveSessionId($db, $vehicle) {
    $query = 'select sessionId from sessionrecords where active = true and vName = "' . $vehicle . '"';
    $result = mysqli_query($db, $query);
    $session = mysqli_fetch_array($result);
    $sessionId = $session['sessionId'];
    return $sessionId;
}

function timeSQLString($timems) {
    return 'NOW() + INTERVAL '. $timems . ' microsecond';
}

function getSuccessMessage() {
    return json_encode(array(
        "SUCCESS" => true
    ));
}

function getErrorMessage($msg) {
    return json_encode(array(
        "ERROR" => $msg
    ));
}

header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

?>
