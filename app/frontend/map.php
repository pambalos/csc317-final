<?php
include('../lib/common.php');
include('../lib/show_queries.php');
include('../lib/error.php');

$sessionId = $_GET['sessionId'];
$map = array();
for ($i = 0; $i < 100; $i++) {
    $row = array();
    for ($j = 0; $j < 100; $j++) {
        array_push($row, 0);
    }
    array_push($map, $row);
}
$vehicle = $_COOKIE['USER'];
$query = 'select * from vehicle where name = "' . $vehicle . '"';
$result = mysqli_query($db, $query);
$vehicle = mysqli_fetch_array($result, MYSQLI_ASSOC);

$r = ($vehicle['width']);

?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="static/styles.css">
        <script src="./js/FilterableTable.js"></script>
        <title>Map</title>
    </head>
    <body>
        <br>
        <br>
        <a href="review.php">Home</a>
        <br>
        <h2>Map for Session: <?php echo $sessionId;?></h2>
        <div id="map-container">

        </div>
        <script></script>
    </body>
</html>
