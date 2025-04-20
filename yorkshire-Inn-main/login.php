<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Handles the front end of the admin login to send to loginscript.php


*/
?>

<?php
session_start();
?>
<head>
    <title>Login</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <div class=header>
        <div class="headercol">
            <a><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
    </div>

    <div class="grid-wrapper">
        <h1>Employee Log-In</h1>
        <div class="emplog">
            <?php
            //error handling
            if (isset($_SESSION['error'])) { ?>
                <p><?php echo $_SESSION['error']; ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
            <!-- Login form -->
            <form class="formstack" action="loginscript.php" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <button type="submit">SUBMIT</button>
                <div>
                    <a href="./password.php">Change Password</a>
                </div>
            </form>
        </div>
    </div>
</form>
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
