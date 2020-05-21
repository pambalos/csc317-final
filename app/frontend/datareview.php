<?php
include('../lib/common.php');
include('../lib/show_queries.php');
include('../lib/error.php');

$sessionId = $_GET['sessionId'];
$query = 'select w.*, s.vName from sessionrecords s join wheelrecords w on s.sessionId = w.sessionId where s.sessionId = "'. $sessionId .'"';
$result = mysqli_query($db, $query);

$dataPoints = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($dataPoints, $row);
}

$query = 'select e.*, s.vName from sessionrecords s join echorecords e on s.sessionId = e.sessionId where s.sessionId = "'. $sessionId .'"';
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($dataPoints, $row);
}

$query = 'select l.*, s.vName from sessionrecords s join linerecords l on s.sessionId = l.sessionId where s.sessionId =  "'. $sessionId .'"';
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($dataPoints, $row);
}

$query = 'select o.*, s.vName from sessionrecords s join otherrecords o on s.sessionId = o.sessionId where s.sessionId = "' . $sessionId . '"';
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($dataPoints, $row);
}
/*
foreach ($dataPoints as $key => $row) {
    $mid[$key]  = $row['time'];
}

// Sort the data with mid descending
// Add $data as the last parameter, to sort by the common key
array_multisort($mid, SORT_DESC, $dataPoints);
*/
//console_log($dataPoints);

?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="static/styles.css">
        <script src="./js/FilterableTable.js"></script>
        <title>Data Review</title>
        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 40px;
                height: 20px;
                margin-left: 5px;
                margin-right: 5px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            td{
                padding: 10px;
            }

            table {
                border-radius: 5px;
                width: 90%;
                text-align: left;
                border-spacing: 0;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 15px;
                width: 15px;
                left: 4px;
                bottom: 3px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(18px);
                -ms-transform: translateX(18px);
                transform: translateX(18px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 20px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
        </style>
    </head>
    <body>
        <br>
        <br>
        <a href="review.php">Home</a>
        <br>
        <h2> Session <?php echo $sessionId; ?> </h2>
        <div id="sessions-container">
            <div style="display: inline-flex; margin-bottom: 8px">
                <div>
                    Wheels
                </div>
                <label class="switch">
                    <input type="checkbox" onchange="filterTable('wid')" id="wheelCheck" checked>
                    <span class="slider round"></span>
                </label>
                <div>
                    Echos
                </div>
                <label class="switch">
                    <input type="checkbox" onchange="filterTable('eid')" id="echoCheck" checked>
                    <span class="slider round"></span>
                </label>
                <div>
                    Lines
                </div>
                <label class="switch">
                    <input type="checkbox" onchange="filterTable('lid')" id="lineCheck" checked>
                    <span class="slider round"></span>
                </label>
                <div>
                    Other
                </div>
                <label class="switch">
                    <input type="checkbox" onchange="filterTable('oid')" id="otherCheck" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            <div id="fieldOptions" style="display: flex;"></div>
            <br>
            <input type="button" value="Sort on Time" onclick="sortTable(0)">&nbsp;&nbsp;&nbsp;<input type="button" value="Sort on Created" onclick="sortTable(1)"><br><br>
            <table id="myTable">
            </table>
        </div>
    <script>
        let data = <?php echo json_encode($dataPoints); ?>;
        let table = document.getElementById("myTable");
        let content = '';
        let fields =[];
        var sorted = true;
        let wheels = true, echos = true, lines = true, others = true;
        data.forEach(addToContent, content);

        function printTable() {
            document.getElementById("myTable").innerHTML = content;
        }

        printTable();
        sortTable(0);
        printFieldOptions();

        function printFieldOptions() {
            fields.push('time', 'vName', 'sessionId');
            let content ='';
            let div = document.getElementById("fieldOptions");

            for (let key of fields) {
                console.log(key);
                content += '<div>' + key + '</div>';
                content += '<input type="checkbox" onchange="filterTableColumn(\'' + key + '\')" checked>&nbsp;&nbsp;&nbsp;';
            }
            div.innerHTML = content;
        }

        function filterTableColumn(value) {
            let table = document.getElementById("myTable");
            let tr = table.getElementsByTagName("tr");
            for (let i = 0; i < tr.length; i++) {
                let row = tr[i];
                let found = false;
                let data = row.children;
                for (let j = 0; j < data.length; j++) {
                    if (data[j].innerHTML.includes(value)) {
                        found = true;
                        if (data[j].style.display === 'none') {
                            data[j].style.display = '';
                        } else {
                            data[j].style.display = 'none';
                        }
                    }
                }
            }
        }

        function filterTable(value) {
            let table = document.getElementById("myTable");
            let tr = table.getElementsByTagName("tr");
            for (let i = 0; i < tr.length; i++) {
                let row = tr[i];
                let found = false;
                let data = row.children;
                for (let j = 0; j < data.length; j++) {
                    if (data[j].innerHTML.includes(value)) {
                        found = true;
                    }
                }
                if (found) {
                    if (row.style.display === 'none') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        }
        
        function addToContent(value) {
            //console.log(value);
            //console.log("in print");
            //console.log(value['time']);
            content += '<tr onmouseover="lightUp(this)" onmouseout="lightDown(this)">';
            content += '<td>'+ 'time: <br>' + value['time'] + '</td>';
            content += '<td>'+ 'created: <br>' + value['created'] + '</td>';
            content += '<td>'+ 'vName: <br>' + value['vName'] + '</td>';
            content += '<td>'+ 'sessionId: <br>' + value['sessionId'] + '</td>';

            for (let data in value) {
                //console.log(value[data]);
                if (data !== 'time' &&  data !== 'vName' && data !== 'sessionId' && data !== 'created') {
                    if (!fields.includes(data.toString())) fields.push(data.toString());
                    content += '<td>';
                    content += data + ': <br>' + value[data];
                    content += '</td>';
                }
            }
            content += '</tr>';
        }

        function sortTable(index) {
            sorted = !sorted;
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;

                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 0; i < rows.length-1; i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                    one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[index];
                    y = rows[i + 1].getElementsByTagName("TD")[index];
                    // Check if the two rows should switch place:
                    if (sorted) {
                        if (x.innerHTML > y.innerHTML) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (x.innerHTML < y.innerHTML) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }

                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        function lightUp(doc) {
            doc.style.backgroundColor = 'lightgreen';
        }

        function lightDown(doc) {
            doc.style.backgroundColor = 'white';
        }

        //console.log(content);

    </script>
    </body>
</html>
