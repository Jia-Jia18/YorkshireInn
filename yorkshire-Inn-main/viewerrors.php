<?php
/*
YORKSHIRE INN REDESIGN

Purpose: To provide a table to view double booking and room blocking related errors.

*/

    //starts session for SSO authentication and requires DB config file
    session_start();
    require_once('dbconfig.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Errors</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>
<body>

<div class=header>
    <div class="headercol">
        <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
    </div>
    <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
</div>
<div class="grid-wrapper">
    <h1>Employee Actions - View Errors</h1>

    <form class="bookingsearch">
        <div class="row">
            <div class="formcol">
            <label for="Search">Search:</label>
            <input type="text" id="usersearch" placeholder="Search" name="search" onkeyup='searchTable("usersearch","myTable")'>
            </div>
        </div>
    </form>

    <hr style="width: 98%; color: black;">
    <div class="bookingtable">
        <form class="viewbooking">
            <?php
                //create head of table
                $bigString = "<table style='width:100%' id='myTable'>
                <thead>
                <tr>
                <th>Error ID</th>
                <th>Timestamp</th>
                <th>Error Message</th>
                </tr>
                </thead>";
                //select errors from error table
                //if a result exists, insert it as a row into the table
                //if a result does not exist, say that 0 results exist
                $result = $conn->query('SELECT errorID, timestamp, errorMessage FROM error ORDER BY errorID asc');
                if ($result->num_rows > 0) {
                   // OUTPUT DATA OF EACH ROW
                   while($row = $result->fetch_assoc()){
                       $bigString.= "<tr> <td>" .
                           $row["errorID"]. " </td><td> " .
                           $row["timestamp"]. " </td><td> " .
                           $row["errorMessage"]. " </td><td>  ";
                   }
                    $bigString.="</table>";
                    echo $bigString;
                } 
                else {
                   echo "0 results";
                }
                //terminate the DB connection
                $conn->close();
            ?>
        </form>
    </div>
</div>

<div class="footer">
        <div class='row'>
            <div class='footercol'>
                <img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;">
            </div>
            <div class='row'>
                <div class='footercol'>
                    <p>COPYRIGHT © 2022 THE YORKSHIRE INN - ALL RIGHTS RESERVED.</p>
                </div>
            </div>
        </div>
</body>

</html>

<?php
	
	echo '  <script>

    function searchTable(inputId,tableId) {
        const searchInput = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll("tbody tr");
    
        searchInput.addEventListener("input", function() {
        const query = searchInput.value.trim().toLowerCase();
    
        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            let hasMatch = false;
    
            cells.forEach(cell => {
            if (cell.textContent.trim().toLowerCase().includes(query)) {
                hasMatch = true;
            }
            });
    
            if (hasMatch) {
            row.style.display = "";
            } else {
            row.style.display = "none";
            }
        });
        });
      }
</script>'
?>
