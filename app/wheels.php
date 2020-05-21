<?php

include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = $_GET['time'];
$error = '';

$leftval =  doubleval($_GET['left']);
$rightval = doubleval($_GET['right']);
$sessionId = getActiveSessionId($db, $vehicle);

$query = 'insert into wheelrecords value (UUID(),"' . $sessionId . '",' . $leftval . ',' . $rightval . ','. $timems . ',NOW())';
$result = mysqli_query($db, $query);
if ($result == false) {
    $error = json_encode(array("ERROR" => "wheelrecords insert failed"));
}

if ($error == '') {
    echo getSuccessMessage();
} else {
    echo $error;
}
