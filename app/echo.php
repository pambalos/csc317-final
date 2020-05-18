<?php

include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = timeSQLString($_GET['time']);
$error = '';

$dist = doubleval($_GET['dist']);

$sessionId = getActiveSessionId($db, $vehicle);

$query = 'insert into echorecords value (UUID(),"' . $sessionId . '",' . $dist . ',' . timeSQLString($timems) . ')';
$result = mysqli_query($db, $query);
if ($result == false) {
    echo json_encode(array("ERROR" => "Failed inserting Echo record", "query" => "" . $query . ""));
} else {
    echo getSuccessMessage();
}