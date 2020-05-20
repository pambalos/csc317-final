
<?php
include('../lib/common.php');
include('../lib/show_queries.php');
include('../lib/error.php');

$query = 'select * from vehicle join sessionrecords s on vehicle.name = s.vName';
$result = mysqli_query($db, $query);

$sessions = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($sessions, $row);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="static/styles.css">
    <script src="./js/FilterableTable.js"></script>
    <title>All Sessions</title>
</head>
<body>
<br>
<br>
<a href="review.php">Home</a>
<br>
<div id="sessions-container">
    <?php
    if (!empty($sessions)) {
        echo '<h2>All Sessions</h2>';
    }
    ?>
    <input type="text" id="myInput" onkeyup="filterByNames()" placeholder="Search for names.."><br>
    Sessions:
    <select id="activeFilter" onchange="filterByActive()">
        <option value="all">All</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select><br><br>

    Started After: <input type="datetime-local" id="afterTime" name="afterTime"><br>
    Started Before: <input type="datetime-local" id="beforeTime" name="beforeTime">
    <input type="button" value="Filter" onclick="filterByTimeRange(3)">
    <br><br>
    <?php
    echo '<table id="myTable" style="border-radius: 5px; width: 80%; text-align: left;border-spacing: 0">';
    if (!empty($sessions)) {
        echo '<tr>';
        foreach (array_keys($sessions[0]) as $key) {
            if (strcasecmp($key, 'vName') != 0 && strcasecmp($key, 'time') != 0) {
                echo '<th>' . $key . '</th>';
            }
        }
        echo '</tr>';

        for ($i = 0; $i < sizeof($sessions); $i++) {
            echo '<tr class="session" id ='. strval($sessions[$i]['sessionId']) .'>';
            foreach (array_keys($sessions[$i]) as $key) {
                if (strcasecmp($key, 'vName') != 0 && strcasecmp($key, 'time') != 0) {
                    if (strcasecmp($key, 'active') == 0) {
                        $out = ($sessions[$i][$key]) ? 'true' : 'false';
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
        echo '<h2>No Sessions in Database</h2>';
    }

    echo '</table>';
    echo '';
    ?>
</div>
<script>
    //load default time values
    window.addEventListener("load", function() {
        let now = new Date();
        let utcString = now.toISOString().substring(0,19);
        let year = now.getFullYear();
        let month = now.getMonth() + 1;
        let day = now.getDate();
        let hour = now.getHours();
        let minute = now.getMinutes();
        let localDatetime = year + "-" +
            (month < 10 ? "0" + month.toString() : month) + "-" +
            (day < 10 ? "0" + day.toString() : day) + "T" +
            (hour < 10 ? "0" + hour.toString() : hour) + ":" +
            (minute < 10 ? "0" + minute.toString() : minute) +
            utcString.substring(16, 19);

        let beforeDatetimeField = document.getElementById("beforeTime");
        beforeDatetimeField.value = localDatetime;
        let afterDatetimeField = document.getElementById("afterTime");
        afterDatetimeField.value = localDatetime;
    });

    var sessions = document.getElementsByClassName("session");
    for (let session of sessions) {
        document.getElementById(session['id']).addEventListener("click", function () {
            //console.log("picked up click");
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