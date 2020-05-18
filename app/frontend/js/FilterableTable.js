function filterByNames() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function filterByTimeRange() {

}

function filterByActive() {
    // Declare variables
    var inf, input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("activeFilter");
    inf = input.options[input.selectedIndex].value;
    console.log(inf);
    if (inf.localeCompare("active") == 0) {
        filter = "TRUE";
    } else if (inf.localeCompare("inactive") == 0) {
        filter = "FALSE";
    } else {
        filter = "ALL";
    }
    console.log("In fiter by active");
    console.log(filter);
    //filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[5];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (filter.localeCompare("ALL") != 0) {
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            } else {
            tr[i].style.display = "";
            }
        }
    }
}