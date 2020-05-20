
<?php
include('../lib/common.php');
include('../lib/show_queries.php');
include('../lib/error.php');

$query = 'select * from vehicle join sessionrecords s on vehicle.name = s.vName where s.active = true';
$result = mysqli_query($db, $query);

$sessions = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($sessions, $row);
}
console_log($sessions);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="static/styles.css">
    <script src="./js/FilterableTable.js"></script>
    <title>Active</title>
</head>
<body>
    <br>
    <br>
    <br>
    <div id="sessions-container">
        <?php
        if (!empty($sessions)) {
            echo '<h2>Current Active Sessions</h2>';
        }
        ?>
        <input type="text" id="myInput" onkeyup="filterByNames()" placeholder="Search for names..">
        <?php

        echo '<table id="myTable" style="border-radius: 5px; width: 80%; text-align: left;border-spacing: 0">';

        if (!empty($sessions)) {

            echo '<tr class="header">';
            foreach (array_keys($sessions[0]) as $key) {
                if (strcasecmp($key, 'ended') != 0 && strcasecmp($key, 'time') != 0 && strcasecmp($key, 'vName') != 0) {
                    echo '<th>' . $key . '</th>';
                }
            }
            echo '</tr>';

            for ($i = 0; $i < sizeof($sessions); $i++) {
                echo '<tr class="session" id ='. strval($sessions[$i]['sessionId']) .'>';
                foreach (array_keys($sessions[$i]) as $key) {
                    if (strcasecmp($key, 'ended') != 0 && strcasecmp($key, 'time') != 0 && strcasecmp($key, 'vName') != 0) {
                        if (strcasecmp($key, 'active') == 0) {
                            $out = $sessions[$i][$key] ? 'true' : 'false';
                            echo '<td>' . $out . '</td>';
                        } else {
                            echo '<td>' . $sessions[$i][$key] . '</td>';
                        }
                    }
                }
                //echo '<td class="button-td">';
                echo '<td><a href="map.php?sessionId='. strval($sessions[$i]['sessionId']) .'">Map</a></td>';
                echo '</tr>';
            }

        } else {
            echo '<h2>No Active Sessions</h2>';
        }

        echo '</table>';
        echo '';
        ?>
    </div>
<script>
    var sessions = document.getElementsByClassName("session");
    for (let session of sessions) {
        document.getElementById(session['id']).addEventListener("click", function () {
            console.log("picked up click");
            window.location.href = "datareview.php?sessionId=" + session['id'];
        });
        document.getElementById(session['id']).addEventListener("mouseover", function () {
            this.style.backgroundColor = "lightgreen";
        });
        document.getElementById(session['id']).addEventListener("mouseout", function () {
            this.style.backgroundColor = "white";
        });
    }
</script>
</body>
</html>