<?php

include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

$vehicle = $_COOKIE['USER'];
$timems = timeSQLString($_GET['time']);
$error = '';

$name = $_GET['name'];
$width = doubleval($_GET['width']);

setcookie("USER", $name, time()+60*24*60*60);

$query = 'select * from vehicle where vehicle.name = "' . $name . '"';
$response = mysqli_query($db, $query);
$count = mysqli_num_rows($response);

if ($count < 1) {
    //no vehicle, create one
    $query = 'insert into vehicle value ("' . $name . '",' . $width . ', NOW() + MICROSECOND('. $timems . '))';
    $res = mysqli_query($db, $query);
    if ($res == false) {
        echo json_encode(array("ERROR" => "vehicle insert failed"));
    }
}

$query = 'select * from sessionrecords where vName = "' . $name . '"';
$res = mysqli_query($db, $query);
if ($res == false) {
    echo json_encode(array("ERROR" => "session select Failed"));
}

//if there are session belonging to this vehicle, check for active
if (($count = mysqli_num_rows($res)) > 0) {
    while (($row = mysqli_fetch_array($res))) {
        if ($row['active'] == true) {
            //found active session, set as deactive
            $query = 'update sessionrecords set active = false, ended = '. $timems . ' where vName = "' . $name .'" and active = true';
            $res2 = mysqli_query($db, $query);
            if ($res2 == false) {
                echo json_encode(array("ERROR" => "update session Failed"));
            }
        }
    }
}

$query = 'insert into sessionrecords value (UUID(),' . '"' . $name . '",'. $timems . ', null, true)';
$res = mysqli_query($db, $query);
if ($res == false) {
    echo json_encode(array("ERROR" => "create session Failed", "query" => $query));
}

$return = array("USER" => $name);
echo json_encode($return);

