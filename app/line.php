<?php

include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = timeSQLString($_GET['time']);
$error = '';

$sessionId = getActiveSessionId($db, $vehicle);
$url = $_SERVER['REQUEST_URI'];

$count = substr_count($url, '&');
$success = true;

for ($i = 1; $i <= $count; $i++) {
    $data = $_GET['l'.$i];
    $query = 'insert into linerecords value (UUID(),"' . $sessionId . '",' . $data . ',' . $timems . ')' ;
    $result = mysqli_query($db, $query);
    if ($result == false) {
        $success = false;
        break;
    }
}

if ($success) {
    echo getSuccessMessage();
} else {
    echo json_encode(array("ERROR" => "Failed to make line record"));
}