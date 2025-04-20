<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Intermediary page between manualbooking and util-finalManualBooking.php.
         Allows innkeeper to preview data before submitting.

*/
?>

<?php
//login enforcement
session_start();
require "dbconfig.php";
        //read in a bunch of values from the form
        $checkIn = $_POST['checkIn'];
        $checkOut = $_POST['checkOut'];
        $numGuests = $_POST['numGuests'];
        $SpecialReq = $_POST['SpecialReq'];
        $RoomID =$_POST['RoomID'];
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $CreditCardNum = $_POST['CreditCardNum'];
        $ccv = $_POST['ccv'];
        $zip = $_POST['zip'];
        $expire = $_POST['expire'];
        

        
        $query = "";

        //get the sum of the room prices for a set of dates
        //switch based on room ID
        switch($RoomID) {
            case 1:
                $query = "SELECT SUM(room1Price) FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
                break;
            case 2:
                $query = "SELECT SUM(room2Price) FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
                break;  
            case 3:
                $query = "SELECT SUM(room3Price) FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
                break;
            case 4:
                $query = "SELECT SUM(room4Price) FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
                break;              
        }

        //get total payment and make it a variable
        $TotalPaymentResult = yorkshireQuery($query);
        $TotalPaymentAssoc = yorkshire_fetch_assoc($TotalPaymentResult);
        $TotalPayment = $TotalPaymentAssoc["SUM(room" . $RoomID . "Price)"];

        //calculate total amount (total price) and total deposit (money due up front) as a formatted number
        $TotalAmount = $TotalPayment + ($TotalPayment * 0.08) + ($TotalPayment * 0.13);
        $TotalDeposit = $TotalAmount * .25;
        $TotalAmountFormat = number_format($TotalAmount, 2);
        $TotalDepositFormat = number_format($TotalDeposit, 2);

        //save all data into a cookie for use in util-finalManualBooking.php
        $data = array(
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'numGuests' => $numGuests,
            'SpecialReq' => $SpecialReq,
            'RoomID' => $RoomID,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'phone' => $phone,
            'email' => $email,
            'CreditCardNum' => $CreditCardNum,
            'ccv' => $ccv,
            'zip' => $zip,
            'expire' => $expire,
            'TotalAmount' => $TotalAmountFormat,
            'TotalDeposit'  => $TotalDepositFormat
        );
        $cookieName = "manualBooking";
        $cookieData = json_encode($data);
        setcookie($cookieName, $cookieData, time() + (86400), "/");
?>
<head>
    <!-- Page Title -->
    <title>Manual Booking Confirmation</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!-- Page Header -->
    <div class=header>
        <div class="headercol">
            <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="grid-wrapper">
        <h1>Employee Actions - Confirm Manual Booking</h1>

        
        <?php /* Error Handing*/ if (isset($_SESSION['error'])) { ?>
        <p>
            <?php echo $_SESSION['error']; ?>
        </p>
        <?php unset($_SESSION['error']); ?>
        <?php } ?>
        <div class="bookingtable">
            <!-- Table of data for confirmation -->
            <table style="width:100%">
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
                        <?php echo $TotalAmountFormat; ?>
                    </th>
                </tr>

                <tr>
                    <th>Total Deposit</th>
                    <th>
                        <?php echo $TotalDepositFormat; ?>
                    </th>
                </tr>
            </table>

            <!-- Simple confirmation form -->
            <form class= "manualconfirm" method="post" action="util-finalManualBooking.php">
                <p>
                    By pressing the "Submit" button, you agree that you have verified the accuracy of the data listed in
                    this form. If all data is accurate, a booking
                    will be attempted. The booking is not confirmed until a confirmation code is generated.
                </p>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <!-- Page Footer -->
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
