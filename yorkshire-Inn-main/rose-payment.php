<?php
/*
YORKSHIRE INN REDESIGN
ROCHESTER INSTITUTE OF TECHNOLOGY
ISTE 501 - Senior Design and Development
----------------------------------------
Author: Chris Pegoli
File: rose-payment.php
Version: 1.0
Release Notes: Initial Release.
----------------------------------------
Purpose: Displays stay information and relevent costs using SQL queries and takes in customer information to be stored in the database. Various echos
and formatting in the form.

ALL RIGHTS RESERVED. 
*/
?>

<?php
// Starts the session, needed for SESSION variables
session_start();

// Used to format the page
include('header.php');

// Requires PHP page that handles database connection
require 'dbconfig.php';

// Save form data in session
$checkIn = isset($_COOKIE["checkIn"]) ? $_COOKIE["checkIn"] : '';
$checkOut = isset($_COOKIE["checkOut"]) ? $_COOKIE["checkOut"] : '';
$numGuests = isset($_COOKIE["numGuests"]) ? $_COOKIE["numGuests"] : '';

// Calculate number of nights
$checkinDate = new DateTime($checkIn);
$checkoutDate = new DateTime($checkOut);
$numNights = $checkinDate->diff($checkoutDate)->format('%a');
$_SESSION['numNights'] = $numNights;

// SQL query that gets the price of the room between check in and check out and adds them all together
$sql3 = "SELECT SUM(room3Price) AS price FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
$result3 = mysqli_query($conn, $sql3);
$row3 = $result3->fetch_assoc();
$price3 = $row3['price'];


// SQL query that gets the price of the room and sets it to a variable 
$roomPrice3 = "SELECT room3Price AS roomPrice FROM price WHERE priceDate = '$checkIn'";
$roomResult3 = mysqli_query($conn, $roomPrice3);
$roomPriceRow3 = $roomResult3->fetch_assoc();
$roomPrice3 = $roomPriceRow3['roomPrice'];

// Calculates the cost of the rooms for all nights with the guest fee and taxes
if ($numGuests > 2) {
    $totalGuestFee3 = ($numGuests - 2) * 30;
    $totalWGuestFee3 = $price3 + $totalGuestFee3;
    $salesTax3 = $totalWGuestFee3 * .08;
} else {
    $totalWGuestFee3 = $price3;
    $salesTax3 = $price3 * .08;
}

// Adds the sales tax to calculate to total cost with fees
$grandTotal3 = $totalWGuestFee3 + $salesTax3;

// Misc. SESSION variables for functionality
$_SESSION['grandTotal3'] = $grandTotal3;
?>
<div class="grid-wrapper">
    <div class='resDetails'>
        <h2>Your Reservation Details</h2>
        <div class='room'>
            <div class='row'>
                <div class='col' id='paymentWindow'>
                    <h3>Rose Suite</h3>
                    <ul class='amenPayment'>
                        <li>Sleeps 6</li>
                        <li>Bathrobes</li>
                        <li>Hair Dryer</li>
                        <li>Private Bathroom</li>
                        <li>Shower</li>
                        <li>Air Conditioning</li>
                    </ul>
                </div>
                <div class='col' id='paymentWindow'>
                    <ul class='amenPayment'>
                        <li>TV</li>
                        <li>Free WiFi</li>
                        <li>Daily Housekeeping</li>
                        <li>Desk</li>
                        <li>Deck</li>
                    </ul>
                </div>
                <div class='col' id='paymentWindow'>
                    <img src='assets/img/rose.png' alt='Rose Suite' class='payImg'>
                </div>
            </div>
            <div class='row'>
                <div class='roomInfo'>
                    <div class='row' id='infoRow'>
                        <div class='col' id='infoCol'>
                            <h3><strong>CHECK IN</strong></h3>
                            <p><?php echo $checkIn ?></p>
                        </div>
                        <div class='col' id='infoCol'>
                            <h3><strong>CHECK OUT</strong></h3>
                            <p><?php echo $checkOut ?></p>
                        </div>
                        <div class='col' id='infoCol'>
                            <h3><strong>NO. OF GUESTS</strong></h3>
                            <p><?php echo $numGuests ?></p>
                        </div>
                        <div class='col' id='infoCol'>
                            <h3><strong>ROOM FARE</strong></h3>
                            <p>$<?php echo number_format($roomPrice3, 2); ?>/Night</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class='roomTotal'>
                    <h3><strong>TOTAL</strong></h3>
                    <p>$<?php echo number_format($grandTotal3, 2); ?></p>
                </div>
            </div>

        </div>
    </div>

    <div class='contactUs'>
        <h2><strong>Have a question about booking with us?</strong></h2>
        <button class='bookingButton'>Contact Us</button>
    </div>

    <div class='summary'>
        <table>
            <tr>
                <th>Charge Description</th>
                <th></th>
                <th>Cost</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Number of Nights</td>
                <td><?php echo $numNights; ?></td>
                <td>$<?php echo number_format($roomPrice3, 2); ?></td>
                <td>$<?php echo number_format($price3, 2); ?></td>
            </tr>
            <tr>
                <td>Guest fee (if over 2, $30 per guest aditional)</td>
                <td><?php echo $numGuests; ?></td> <!-- for testing purposes, this is only needed on rose/lodge -->
                <td>$<?php echo number_format($totalGuestFee3, 2); ?></td>
                <td>$<?php echo number_format($totalWGuestFee3, 2); ?></td>
            </tr>
       <!-- <tr>
                <td>Guest 1</td>
                <td>6</td>
                <td>$0.00</td>
                <td>$0.00</td>
            </tr>
            <tr>
                <td>Guest 2</td>
                <td>6</td>
                <td>$0.00</td>
                <td>$0.00</td>
            </tr> -->
            <tr>
                <td>NYS Sales Tax (8%)</td>
                <td>$<?php echo number_format($totalWGuestFee3, 2); ?> x .08</td>
                <td>$<?php echo number_format($salesTax3, 2); ?></td>
                <td>$<?php echo number_format($grandTotal3, 2); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>TOTAL DUE*</td>
                <td>$<?php echo number_format($grandTotal3, 2); ?></td>
            </tr>
        </table>
    </div>
    <h3 class='resDetails'>Guest Information</h3>
    <div class='guestInfo'>
        <form method="POST" action="rose-booking.php">
            <div class="row">
                <div class="formcol1">
                    <label for='title'>Title</label>
                    <input type='text' id='title' name='title' value='Mr.'>
                </div>
                <div class="formcol">
                    <label for='fname'>First Name*</label>
                    <input type='text' id='fname' name='fname' required>
                </div>

                <div class="formcol">
                    <label for='lname'>Last Name*</label>
                    <input type='text' id='lname' name='lname' required><br>
                </div>
            </div>

            <div class="row">
                <div class="formcol">
                    <label for='email'>E-mail Address*</label>
                    <input type='email' id='email' name='email' required>
                </div>

                <div class="formcol">
                    <label for='pnum'>Phone Number*</label>
                    <input type="tel" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone" maxlength="12" placeholder="123-456-7890" oninput="formatPhoneNumber(this)" required><br>
                    <script>
                        function formatPhoneNumber(input) {
                            var phoneNumber = input.value.replace(/\D/g, '');
                            phoneNumber = phoneNumber.substring(0,3) + '-' + phoneNumber.substring(3,6) + '-' + phoneNumber.substring(6,10);
                            input.value = phoneNumber;
                        }
                    </script>
                </div>
            </div>
            <div class="row">
                <div class="formcol">
                    <label for='request'>Special Requests/Notes</label>
                    <textarea id="request" name="request"
                              placeholder="Please use this field to communicate any special requests or notes for the Innkeeper (ex. dietary restrictions, pet guests)."></textarea>
                </div>
            </div>
            <br>
            <h3>Payment Information</h3>
            <div class="row">
                <div class="formcol">
                    <label for='cardName'>Name on Card*</label>
                    <input type='text' id='cardName' name='cardName' required> <!-- required -->
                </div>
                <div class="formcol">
                    <div class="cardrow">
                        <p>We accept these cards:</p>
                        <div>
                            <img src='assets/img/visa 1.png' class='cardIcon'>
                            <img src='assets/img/american-express 1.png' class='cardIcon'>
                            <img src='assets/img/discover 1.png' class='cardIcon'>
                            <img src='assets/img/master-card 1.png' class='cardIcon'>
                        </div>
                    </div><br>
                </div>
            </div>

            <div class="row">
                <div class="formcol">
                    <label for='cardNum'>Card Number</label>
                    <input type='text' pattern="\d{16}" id='cardNum' name='cardNum' minlength='16' maxlength='16' required> <!-- required -->
                </div>
                <div class="formcol">
                    <label for='expDate'>Exp Date</label>
                    <input type='text' id='expDate' name='expDate' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" maxlength="10" placeholder="1234-56-78" oninput="formatDate(this)" required>
                    <script>
                        function formatDate(input) {
                            var date = input.value.replace(/\D/g, '');
                            date = date.substring(0,4) + '-' + date.substring(4,6) + '-' + date.substring(6,8);
                            input.value = date;
                        }
                    </script>
                </div>
                <div class="formcol">
                    <label for='csc'>CSC</label>
                    <input type='text' pattern="\d{3}" id='csc' name='csc' maxlength='3' required> <!-- required -->
                </div>
                <div class="formcol">
                    <label for='zip'>Zip Code</label>
                    <input type='text' pattern="\d{5}" id='zip' name='zip' maxlength='5' max='99999' required> <!-- required -->
                </div>
            </div>
    <div class='terms'>
        <h3>Terms and Conditions</h3>
        <p>
            By pressing the “Submit” button, you acknowledge that you have
            read The Yorkshire<br> Inn’s policies regarding guests,
            cancellations, and “no shows”. Our policies can be<br> viewed
            here. All cancellations must be handled by calling our
            Innkeeper at <br>(315) 548-9765.<br><br>

            Upon pressing the “Submit” button, a charge will be placed
            with the credit card on file.<br> A 25% deposit will be charged
            to secure the room for your stay. This deposit will be<br>
            credited upon checking out.
        </p>
            <button type="submit" name="submitPay" class="submitPay">Submit</button>
        </form>

        <!-- <button class='submitPay'>Submit</button> -->
    </div>
</div>
<?php
// Used to format the page
include('footer.php');

// Misc. SESSION variables for functionality
$checkIn = $_SESSION['checkIn'];
$checkOut = $_SESSION['checkOut'];
$numGuests = $_SESSION['numGuests'];
?>
