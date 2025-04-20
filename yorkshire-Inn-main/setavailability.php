

<?php
session_start();
//Login enforcement
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }
?>
<head>
    <!-- Page Title -->
    <title>Set Availability</title>
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
        <h1>Employee Actions - Set Availability</h1>
        <?php
         //error handling
         if (isset($_SESSION['error'])) { ?>
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
            <!-- Form that intakes date range and room availability information -->
            <form class="availabilityform" action="submitAvailabilityChange.php" method="post">
                <div>
                    <label for="Start Date">Start Date</label>
                    <input type="date" id="Date" name="StartDate">
                </div>
                <div>
                    <label for="End Date">End Date</label>
                    <input type="date" id="Date" name="EndDate">
                </div>
                <label for="dropdown1">Select Room 1 Availability</label>
                <select id="dropdown1" name="dropdown1">
                    <option value="1" selected>Available</option>
                    <option value="0">Not Available</option>
                </select>
                <label for="dropdown2">Select Room 2 Availability</label>
                <select id="dropdown2" name="dropdown2">
                    <option value="1" selected>Available</option>
                    <option value="0">Not Available</option>
                </select>
                <label for="dropdown3">Select Room 3 Availability</label>
                <select id="dropdown3" name="dropdown3">
                    <option value="1" selected>Available</option>
                    <option value="0">Not Available</option>
                </select>
                <label for="dropdown4">Select Room 4 Availability</label>
                <select id="dropdown4" name="dropdown4">
                    <option value="1" selected>Available</option>
                    <option value="0">Not Available</option>
                </select>

                <button type="submit">SUBMIT</button>
            </form>
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
