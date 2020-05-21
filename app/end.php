<?php

include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = $_GET['time'];
$error = '';

$sessionId = getActiveSessionId($db, $vehicle);

$query = 'update sessionrecords set active = false, ended = '. $timems .' where sessionId = "' . $sessionId .'"';
$result = mysqli_query($db, $query);
if ($result == false) {
    echo $query;
    //echo getErrorMessage('Failed to update session to inactive');
} else {
    echo getSuccessMessage();
}