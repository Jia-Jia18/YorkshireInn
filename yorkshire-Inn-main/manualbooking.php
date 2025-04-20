

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
    <title>Manual Booking</title>
    <link href="assets/css/homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!--Page Header with Logout/home page button-->
    <div class=header>
        <div class="headercol">
            <a href=./adminmenu.php><img src='assets/img/yorkshire.png' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>

    <div class="grid-wrapper">
        <h1>Employee Actions - Manual Booking</h1>
        <?php
         //error handling
         if (isset($_SESSION['error'])) { ?>
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
        <div class="bookingtable">
            <!-- Booking form that intakes all required data -->
            <form class="bookingform" action="util-confirmManualBooking.php" method="post">
                <script>
                    // Get the check-in and check-out inputs
                    const checkinInput = document.getElementById('checkIn');
                    const checkoutInput = document.getElementById('checkOut');

                    // Set the minimum value of the check-out input to the check-in input value
                    checkinInput.addEventListener('change', function () {
                        checkoutInput.min = checkinInput.value;
                    });

                    // Set the maximum value of the check-in input to the check-out input value
                    checkoutInput.addEventListener('change', function () {
                        checkinInput.max = checkoutInput.value;
                    });
                </script>
                <div class="row">
                    <div class="formspacing">
                        <label for="checkin">Check-in date:</label>
                        <input type="date" id="checkin" name="checkIn">

                    </div>
                    <div class="formspacing">
                        <label for="checkout">Check-out date:</label>
                        <input type="date" id="checkout" name="checkOut">
                    </div>
                    <div class="formspacing">
                        <label for="quantity">Guests:</label>
                        <input type="number" id="quantity" name="numGuests" value="1" min="1" max="10" step="1">
                    </div>


                    <select id="dropdown1" name="RoomID">
                        <option value="1" selected>Blue Room</option>
                        <option value="2">Bolero Room</option>
                        <option value="3">Rose Suite</option>
                        <option value="4">Lodge Suite</option>
                    </select>
                </div>

                <div class="row">
                    <div class="formspacing">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="FirstName">
                    </div>

                    <div class="formspacing">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="LastName">
                    </div>
                </div>

                <div class="row">
                    <div class="formspacing">
                        <label for="em">Email:</label>
                        <input type="text" id="em" name="email">
                    </div>
                    <div class="formspacing">
                        <label for="ph">Phone Number:</label>
                        <input type="text" id="ph" name="phone">
                    </div>
                </div>

                <div class="row">
                    <div class="formspacing">
                        <label for="SpecialReq">Special Requests</label>
                        <textarea id="SpecialReq" name="SpecialReq"
                            placeholder="Please use this field to communicate any special requests"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="formspacing">
                        <label for="CreditCardNum">Card Number:</label>
                        <input type="text" id="CreditCardNum" name="CreditCardNum">
                    </div>

                    <div class="formspacing">
                        <label for="expire">Expiration</label>
                        <input type="text" id="expire" name="expire">
                    </div>

                    <div class="formspacing">
                        <label for="ccv">CCV:</label>
                        <input type="text" id="ccv" name="ccv">
                    </div>
                    <div class="formspacing">
                        <label for="zip">ZIP Code:</label>
                        <input type="text" id="zip" name="zip">
                    </div>
                </div>
                <div class="row">
                    <button type="submit">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    <!--Page Footer-->
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
