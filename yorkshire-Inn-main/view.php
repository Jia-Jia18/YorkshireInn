

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Bookings Information</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'
        integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
    <link href='assets/css/homecss.css' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='assets/css/style.css' type='text/css'>
    <meta charset="utf-8" />
</head>

<body>
    <div class=header>
        <div class="headercol">
            <a><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
    </div>

    <div class="grid-wrapper viewbooking">
        <h1>View Bookings Information</h1>
        
        <div class='bookingtable'>
        <?php

        session_start();
        require_once('dbconfig.php');
        //Login enforcement
        if (empty($_SESSION['user'])) {
            header("Location: login.php");
            die("Redirecting to login.php");
        }
        $id="";
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        //variable init
        $roomID = "";
        $FirstName = "";
        $LastName = "";
        $Phone = "";
        $Email = "";
        $BookingDate = "";
        $CheckIn = "";
        $CheckOut = "";
        $NumGuests = "";
        $SpecialReq = "";
        $TotalPayment = "";
        $TotalDeposit = "";
        $stmt = $conn->prepare('SELECT roomID, FirstName, LastName, Phone, Email, BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit FROM reservation 
        inner join traveler on reservation.TravelerID = traveler.TravelerID 
        where reservation.ReservationsID = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
                       // OUTPUT DATA OF EACH ROW
            while($row = $result->fetch_assoc()){
                $roomID = $row["roomID"];
                $FirstName = $row["FirstName"];
                $LastName = $row["LastName"];
                $Phone =  $row["Phone"];
                $Email =  $row["Email"];
                $BookingDate = $row["BookingDate"];
                $CheckIn = $row["CheckIn"];
                $CheckOut = $row["CheckOut"];
                $NumGuests = $row["NumGuests"];
                $SpecialReq = $row["SpecialReq"];
                $TotalPayment = $row["TotalPayment"];
                $TotalDeposit = $row["TotalDeposit"];
            }
         }
            // Based on the room id, set it to the proper room name.
         if($roomID == 1){
            $roomID = "Blue Room";
         } 
         else if($roomID == 2){
            $roomID = "Bolero Room";
         }
         else if($roomID == 3){
            $roomID = "Lodge Suit";
         }
         else{
            $roomID = "Rose Suite";
         }
         //output room info
        echo "
            <label for='confirmation'>Confirmation Number:</label>
            <p>$id</p>
</div>

        <hr style='width: 98%; color: black;'>
        <div class='bookingtable'>
                <table style='width:100%''>
                    <tr>
                        <th>Guest Last name</th>
                        <th>Guest First name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Booking Date</th>
                    </tr>
                    <tr>
                        <td>$LastName</td>
                        <td>$FirstName</td>
                        <td>$Email</td>
                        <td>$Phone</td>
                        <td>$BookingDate</td>
                    </tr>
                </table>
        </div>

        <div class='bookingtable'>
            <table>
                <tr>
                    <th>Room</th>
                    <th>Num. of guests</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Payment</th>
                    <th>Deposit</th>
                </tr>
                <tr>
                    <td>$roomID</td>
                    <td>$NumGuests</td>
                    <td>$CheckIn</td>
                    <td>$CheckOut</td>
                    <td>"; echo number_format($TotalPayment, 2); echo "</td>
                    <td>"; echo number_format($TotalDeposit, 2); echo "</td>
                </tr>
                
            </table>
        </div>
        <div class='bookingtable'>
            <p>Special Requirement</p>
            <p>$SpecialReq</p>
        </div>
        "
        ?>
        <button class="view"><a href='viewbooking.php'>Back</a></button>

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
