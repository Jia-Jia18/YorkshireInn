<?php
/*
YORKSHIRE INN REDESIGN

Purpose: The page will display the booking report and the admin able to search and delete the reservation.


*/
?>

<?php
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
    <title>Delete Bookings</title>
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
    <!-- Modified header that just displays yorkshire inn logo.
         Also includes a logout button on the top right corner -->
    <div class=header>
        <div class="headercol">
            <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <!-- Contains the booking search form, where a user can search for a booking using
         first name, last name, check in date, and checkout date
         User can then choose to delete a booking by checking a check box and pressing the submit button-->
    <div class="grid-wrapper">
        <h1>Employee Actions - Delete Booking</h1>

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
            <form class="deletebooking" action="delete.php" method="post">
            <?php
                //bigString is the template that creates the table
                $bigString = "<table style='width:100%' id='myTable'><thead>
                <tr><th>Confirmation Num</th><th>Guest Last Name</th><th>Guest First Name</th><th>Check In</th><th>Check Out</th><th>Check to Delete</th></tr></thead>";
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
                           $row["CheckOut"]. "</td><td><input type='checkbox' name='id[]' value='$id'/></td></tr>" ;
                   }
                    $bigString.="</table>";
                    echo $bigString;
                } 
                else {
                   echo "0 results";
                }
                $conn->close();
            ?>
            <input type="submit" value="Delete" name="submit" class="deletebutton">

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
