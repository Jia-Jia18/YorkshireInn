<?php
/*
YORKSHIRE INN REDESIGN

Purpose: This webpage is currently under construction for a future release. In the future,
         this webpage will allow an innkeeper to check out a guest similar to how they initially booked
         their stay.

*/
?>

<?php
session_start();
//Login enforcement
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }
?>
<head>
    <!--Page Title-->
    <!--declare styling-->
    <title>Check Out Guest</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!--Page Header and Home/Logout Buttons-->
    <div class=header>
        <div class="headercol">
            <a href='./adminmenu.php'><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
    </div>

    <!--Page Content-->
    <div class="grid-wrapper">
        <h1>Employee Actions - Check Out Guest</h1>
        <hr style="width: 98%; color: black;">
        <div class="bookingtable">
            <h2> UNDER CONSTRUCTION</h2>
        </div>
    </div>

    <!--Page Footer with logo-->
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
