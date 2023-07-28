<?php
/*
YORKSHIRE INN REDESIGN
ROCHESTER INSTITUTE OF TECHNOLOGY
ISTE 501 - Senior Design and Development
----------------------------------------
Author: JiaJia Chen
File: viewbooking.php
Version: 1.0
Release Notes: Initial Release.
----------------------------------------
Purpose: The page will display the booking report and the admin able to search and view detail for each booking.

ALL RIGHTS RESERVED. 
*/
    session_start();
    require_once('dbconfig.php');
    //Login enforcement
    if (empty($_SESSION['user'])) {
        header("Location: login.php");
        die("Redirecting to login.php");
      }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Bookings</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
    <script>
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
    // Allows user to press enter without breaking search
    function handleFormSubmit(e) {
        e.preventDefault();
    }
</script>

</head>
<body>
<!-- Modified header that just displays yorkshire inn logo. When clicked takes user to admin menu
     Also includes a logout button on the top right corner -->
<div class=header>
    <div class="headercol">
        <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
    </div>
    <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
</div>
<!-- Body of the page,  grid-wrapper centers the text,
     row and formcol style the content into rows and columns.
     User can search for a booking-->
<div class="grid-wrapper">
    <h1>Employee Actions - View All Bookings</h1>

    <form class="bookingsearch">
        <div class="row">
            <div class="formcol">
            <label for="Search">Search:</label>
            <input type="text" id="usersearch" placeholder="Search" name="search" onkeyup='searchTable("usersearch","myTable")'>
            </div>
        </div>
    </form>
    <script>
            // Allows user to press enter without breaking search
            document.querySelector(".bookingsearch").addEventListener("submit", handleFormSubmit);
        </script>

    <hr style="width: 98%; color: black;">
    <div class="bookingtable">
        <form class="viewbooking">
            <?php
                //bigString is the template that creates the table
                $bigString = "<table style='width:100%' id='myTable'>
                <thead>
                <tr><th>Confirmation Num</th><th>Guest Last name</th><th>Guest First name</th><th>Check In</th><th>Check Out</th><th>View More</th></tr></thead>";
                $result = $conn->query('SELECT ReservationsID, LastName, FirstName, CheckIn, CheckOut FROM reservation INNER JOIN traveler on reservation.TravelerID = traveler.TravelerID ORDER BY CheckIn asc');
                if ($result->num_rows > 0) {
                   // OUTPUT DATA OF EACH ROW
                   while($row = $result->fetch_assoc()){
                    $id = $row['ReservationsID'];
                       $bigString.= "<tr> <td>" .
                           $row["ReservationsID"]. " </td><td> " .
                           $row["LastName"]. " </td><td> " .
                           $row["FirstName"]. " </td><td>  " . 
                           $row["CheckIn"]. " </td><td>  " . 
                           $row["CheckOut"]. "</td><td><button><a href='view.php?id=$id'>View</a></button>  </td></tr>" ;
                   }
                    $bigString.="</table>";
                    echo $bigString;
                } 
                else {
                   echo "0 results";
                }
             
                $conn->close();
            ?>
        </form>
    </div>
</div>

<!--Modified footer that just displays Yorkshire Inn logo and copyright-->
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
