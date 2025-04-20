

<head>
    <title>Login</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <div class=header>
        <div class="headercol">
            <a href="./adminmenu.php"><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
    </div>
    <div class="grid-wrapper">
        <div class="emplog">
            <!-- user creation form -->
            <form action="createuser.php" method="POST">
                    <label for="usernamecreate">New Username:</label>
                    <input type="text" id="usernamecreate" name="usernamecreate"><br>

                    <label for="passwordcreate">New Password:</label>
                    <input type="password" id="passwordcreate" name="passwordcreate"><br>
                    <input type="submit" value="Create User">
            </form>
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
