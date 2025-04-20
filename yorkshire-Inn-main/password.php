

<?php
session_start();
?>
<head>
    <title>Change Password</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!-- Modified header that just displays yorkshire inn logo.
         Also includes a logout button on the top right corner -->
    <div class=header>
        <div class="headercol">
            <a href='./adminmenu.php'><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <!-- Body of page, grid-wrapper centers the text
         The form is used to input the correct username and current password to create a new password-->
    <div class="grid-wrapper">
        <h1>Employee Actions - Change Password</h1>
        <?php
         //error handling
         if (isset($_SESSION['error'])) { ?>
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
            <form class="passwordform" action="passwordreset.php" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                </div>
                <div>
                    <label for="current">Current Password:</label>
                    <input type="text" id="current" name="current">
                </div>
                <div>
                    <label for="new">New Password:</label>
                    <input type="text" id="new" name="new">
                </div>
                <div>
                    <label for="confirm">Confirm Password:</label>
                    <input type="text" id="confirm" name="confirm">
                </div>
                <button type="submit">SUBMIT</button>
            </form>
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
