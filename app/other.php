<?php
include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = $_GET['time'];
$error = '';

$sessionId = getActiveSessionId($db, $vehicle);

$query = 'insert into otherrecords value (UUID(),"' . $sessionId . '","' . $_GET['ir'] . '",' . $timems. ',NOW())';
$result = mysqli_query($db, $query);
if ($result == false) {
    echo getErrorMessage("Failed to insert other records");
} else {
    echo getSuccessMessage();
}