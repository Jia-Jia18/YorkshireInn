<?php
/*
YORKSHIRE INN REDESIGN

Purpose: To provide an interface between The Yorkshire Inn's systems and a fictitious 
third party travel company. This API mimics an intermediary data processing unit
in order to prep data for local storage. Postman should be utilized to test data.


*/
?>
<?php
require "dbconfig.php";
session_start();

if(isset($_COOKIE["manualBooking"])) {
    //get the cookie and decode its contents
    $cookieValue = $_COOKIE["manualBooking"];
    $data = json_decode($cookieValue, true);

    //set the cookie contents to variables
    $checkIn = $data['checkIn'];
    $checkOut = $data['checkOut'];
    $numGuests = $data['numGuests'];
    $SpecialReq = $data['SpecialReq'];
    $RoomID = $data['RoomID'];
    $FirstName = $data['FirstName'];
    $LastName = $data['LastName'];
    $phone = $data['phone'];
    $email = $data['email'];
    $CreditCardNum = $data['CreditCardNum'];
    $ccv = $data['ccv'];
    $zip = $data['zip'];
    $expire = $data['expire'];
    $TotalAmount = $data['TotalAmount'];
    $TotalDeposit = $data['TotalDeposit'];

    //see if room is blocked
    //select correct query based on room id
    switch($RoomID) {
        case 1:
            $checkQuery = "SELECT isBlockedBool1 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 2:
            $checkQuery = "SELECT isBlockedBool2 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 3: 
            $checkQuery = "SELECT isBlockedBool3 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 4: 
            $checkQuery = "SELECT isBlockedBool4 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
            break;       
    }

    //prepare query, get its results, and 
    $query = yorkshireQuery($checkQuery);
    $data = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    $jsonResult = json_encode($data, JSON_PRETTY_PRINT);
    
    //for each value - key pair in the result set, see if it contains 1/blocked variable
    //if it does, post an error message, submit an error to the error table, and end the process.
    //if it does not, continue until successful completion or failure state
    foreach($jsonResult as $pair) {
        $value = $pair['isBlockedBool' . $RoomID];
        if(strpos($value, '1') == true) {
            $error_msg = "[ERROR]: Double Booking Attempted. This room is blocked for this range of dates. Booking Unsuccessful.";
            $datetime = date("Y-m-d H:i:s");
            echo $datetime . " | " . $error_msg;
            yorkshireQuery("INSERT INTO error(timestamp, errorMessage) VALUES ('$datetime', '$error_msg')");
            http_response_code(400);
            die();
        } else {
            continue;
        }
    }

    //next, see if room is available

    //select the correct query depending on room choice
    $checkQuery = '';
    switch($RoomID) {
        case 1:
            $checkQuery = "SELECT isAvailableBool1 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 2:
            $checkQuery = "SELECT isAvailableBool2 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 3: 
            $checkQuery = "SELECT isAvailableBool3 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
            break;
        case 4: 
            $checkQuery = "SELECT isAvailableBool4 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
            break;       
    }

    //get the result set from the database and format it as a JSON variable for comparison
    $query = yorkshireQuery($checkQuery);
    $data = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    $jsonResult = json_encode($data, JSON_PRETTY_PRINT);


        //for each value - key pair in the result set, see if it contains 0/is not available variable
        //if it does, post an error message, submit an error to the error table, and end the process.
        //if it does not, continue until successful completion or failure state
    if(strpos($jsonResult, '0') !== false) {
        $error_msg = "[ERROR]: Double Booking Attempted. This room is not available for this range of dates. Booking Unsuccessful.";
        $datetime = date("Y-m-d H:i:s");
        echo $datetime . " | " . $error_msg;
        yorkshireQuery("INSERT INTO error(timestamp, errorMessage) VALUES ('$datetime', '$error_msg')");
        http_response_code(400);
        die();
    }     

    //now, insert the data
    $insertTime = date("Y-m-d H:i:s");
    yorkshireQuery("INSERT INTO traveler(FirstName, LastName, Phone, Email) VALUES ('$FirstName', '$LastName', '$phone', '$email')");
    yorkshireQuery("INSERT INTO payment(PaymentDate, CreditCardNo, CCV, ZIPCode, ExpireDate) VALUES ('$insertTime', '$CreditCardNum', '$ccv', '$zip', '$expire')");
    
    //get traveler id from table for reservation entry
    $query = yorkshireQuery("SELECT TravelerID FROM traveler WHERE FirstName LIKE '$FirstName' AND LastName LIKE '$LastName'");
    $travIDArray = yorkshire_fetch_assoc($query);
    $travID = $travIDArray['TravelerID'];

    //get payment id from table for reservation entry
    $query = yorkshireQuery("SELECT PaymentID FROM payment WHERE PaymentDate LIKE '$insertTime'");
    $payIDArray = yorkshire_fetch_assoc($query);
    $payID = $payIDArray['PaymentID'];

    //insert reservation data
    yorkshireQuery("INSERT INTO reservation(BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit, RoomID, TravelerID, PaymentID) 
                    VALUES ('$insertTime', '$checkIn', '$checkOut', '$numGuests', '$SpecialReq', '$TotalAmount', '$TotalDeposit', '$RoomID', '$travID', '$payID')");

    //set availability to 0/is not available based on room id
    switch($RoomID) {
        case 1:
            yorkshireQuery('UPDATE isAvailable SET isAvailableBool1 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
            break;
        case 2:
            yorkshireQuery('UPDATE isAvailable SET isAvailableBool2 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
            break;
        case 3:
            yorkshireQuery('UPDATE isAvailable SET isAvailableBool3 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
            break;
        case 4:
            yorkshireQuery('UPDATE isAvailable SET isAvailableBool4 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;    
            break;
    }                
    
    //get reservation ID for review
    $query = yorkshireQuery("SELECT ReservationsID FROM reservation WHERE PaymentID IN (SELECT PaymentID FROM payment WHERE PaymentDate LIKE '$insertTime')");
    $resIDAssoc = yorkshire_fetch_assoc($query);
    $resID = $resIDAssoc['ReservationIDs'];
} else {
    http_response_code(400);
}
?>
<head>
    <!-- Page Title -->
    <title>Manual Booking Confirmation</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!-- Page Header-->
    <div class=header>
        <div class="headercol">
            <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="grid-wrapper">
        <h1>Employee Actions - Booking Confirmation</h1>
        <!-- Output check in dates -->
        <?php if (isset($_SESSION['error'])) { ?>
        <p>
            <?php echo $_SESSION['error']; ?>
        </p>
        <?php unset($_SESSION['error']); ?>
        <?php } ?>
        Congratulations!
        <?php echo "$FirstName . $LastName"; ?> has successfully booked their stay at the Yorkshire Inn. Please inform
        the guest that their reservation ID is
        <?php 
                $query = yorkshireQuery("SELECT ReservationsID FROM reservation WHERE PaymentID IN (SELECT PaymentID FROM payment WHERE PaymentDate LIKE '$insertTime')");
                $resIDAssoc = yorkshire_fetch_assoc($query);
                $resID = $resIDAssoc['ReservationsID'];
                echo $resID;
            ?>
        <!-- Output "Invoice" -->
        <div class="bookingtable">
            <table style = "width:100%">
                <tr>
                    <th>Check In Date</th>
                    <th>
                        <?php echo $checkIn; ?>
                    </th>
                </tr>

                <tr>
                    <th>Check Out Date</th>
                    <th>
                        <?php echo $checkOut; ?>
                    </th>
                </tr>

                <tr>
                    <th>Number of Guests</th>
                    <th>
                        <?php echo $numGuests; ?>
                    </th>
                </tr>

                <tr>
                    <th>Special Requests</th>
                    <th>
                        <?php echo $SpecialReq; ?>
                    </th>
                </tr>

                <tr>
                    <th>Room ID</th>
                    <th>
                        <?php 
                            switch($RoomID) {
                                case 1: 
                                    echo "Blue Room";
                                    break;
                                case 2:
                                    echo "Bolero Room";
                                    break;
                                case 3:
                                    echo "Lodge Suite";
                                    break;
                                case 4:
                                    echo "Rose Suite";
                                    break;            
                            }
                        ?>
                    </th>
                </tr>

                <tr>
                    <th>First Name</th>
                    <th>
                        <?php echo $FirstName; ?>
                    </th>
                </tr>

                <tr>
                    <th>Last Name</th>
                    <th>
                        <?php echo $LastName; ?>
                    </th>
                </tr>

                <tr>
                    <th>Phone Number</th>
                    <th>
                        <?php echo $phone; ?>
                    </th>
                </tr>

                <tr>
                    <th>Email</th>
                    <th>
                        <?php echo $email; ?>
                    </th>
                </tr>

                <tr>
                    <th>Credit Card Number</th>
                    <th>
                        <?php echo $CreditCardNum; ?>
                    </th>
                </tr>

                <tr>
                    <th>CCV</th>
                    <th>
                        <?php echo $ccv; ?>
                    </th>
                </tr>

                <tr>
                    <th>ZIP Code</th>
                    <th>
                        <?php echo $zip; ?>
                    </th>
                </tr>

                <tr>
                    <th>Expiration Date</th>
                    <th>
                        <?php echo $expire; ?>
                    </th>
                </tr>

                <tr>
                    <th>Total Amount (with Tax and Service Fee)</th>
                    <th>
                        <?php echo $TotalAmount; ?>
                    </th>
                </tr>

                <tr>
                    <th>Total Deposit</th>
                    <th>
                        <?php echo $TotalDeposit; ?>
                    </th>
                </tr>
            </table>

            <!-- button to return to admin home -->                    
            <button class='submitPay'
                onclick="window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/adminmenu.php';">GO TO
                HOME PAGE</button>
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
