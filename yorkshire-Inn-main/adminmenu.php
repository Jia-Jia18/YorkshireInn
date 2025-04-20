<?php
/*
YORKSHIRE INN REDESIGN
Purpose: Display all the menu actions an inn employee can take.
*/
?>

<?php
session_start();
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }
  ?>
<head>
    <title>Admin Main Menu</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!-- Modified header that just displays yorkshire inn logo.
         Also includes a logout button on the top right corner -->
    <div class=header>
            <div class="headercol">
                <a><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
            </div>
            <div class="headercol">
                <button class = "logout" type = "button" onclick="window.location.href='logout.php'">Logout</button>
            </div>
    </div>

    <!-- Body of the page,  grid-wrapper centers the text, adminbtns styles the buttons: centers them in the page, spaces them out, and adds padding.
         Each button takes the user to a new page-->
    <div class="grid-wrapper">
        <h1>Employee Actions</h1>
        <div class = "adminbtns">
            <button class = "menu" type = "button" onclick="window.location.href='manualbooking.php'">Create a Booking</button>
            <button class = "menu" type = "button" onclick="window.location.href='deletebooking.php'">Delete a Booking</button>
            <button class = "menu" type = "button" onclick="window.location.href='pricing.php'">Set Pricing</button>
            <button class = "menu" type = "button" onclick="window.location.href='setavailability.php'">Set Availability</button>
            <button class = "menu" type = "button" onClick="window.location.href='viewbooking.php'">View Bookings</button>
            <button class = "menu" type = "button" onClick="window.location.href='viewerrors.php'">View Booking Errors</button>
            <button class = "menu" type = "button" onClick="window.location.href='checkoutguest.php'">Check Out Guest</button>
            <button class = "menu" type = "button" onclick="window.location.href='password.php'">Reset Password</button>
            <button class = "menu" type = "button" onclick="window.location.href='newuser.php'">Create User</button>
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
