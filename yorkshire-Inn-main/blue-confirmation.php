<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Uses session variable and SQL queries to display relevent booking information to the 
customer at the end of the booking process.


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

// SQL query that gets the deposit amount from the database and stores it as a variable
$resID = $_GET['resID'];
$deposit = "SELECT TotalDeposit AS deposit FROM reservation WHERE ReservationsID = '$resID'";
$depositResult = mysqli_query($conn, $deposit);
$depositResultRow = $depositResult->fetch_assoc();
$deposit = $depositResultRow['deposit'];

// SQL query that gets the price of the room between check in and check out and adds them all together
$sql = "SELECT SUM(room1Price) AS price FROM price WHERE priceDate >= '$checkIn' AND priceDate < '$checkOut'";
$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();
$price = $row['price'];

// SQL query that gets the price of the room and sets it to a variable 
$roomPrice = "SELECT room1Price AS roomPrice FROM price WHERE priceDate = '$checkIn'";
$roomResult = mysqli_query($conn, $roomPrice);
$roomPriceRow = $roomResult->fetch_assoc();
$roomPrice = $roomPriceRow['roomPrice'];

// If/else statement that calculates guest fee if necessary
if ($numGuests > 2) {
    $totalGuestFee = ($numGuests - 2) * 30;
    $totalWGuestFee = $price + $totalGuestFee;
    $salesTax = $totalWGuestFee * .08;
} else {
    $totalWGuestFee = $price;
    $salesTax = $price * .08;
}

// calculates grand total
$grandTotal = $totalWGuestFee + $salesTax;

// Misc. SESSION variables for functionality
$SpecialReq = $_SESSION['SpecialReq'];
$resID = $_SESSION['resID'];
?>
        <h3 class='resDetails'>Confirmation of your reservation of the Blue Room between:</h3>
        <h3 class='resDetails'><?php echo ($checkIn); ?> - <?php echo ($checkOut); ?></h3>
        <div>
            <p class='confirmText'>
                Your reservation is all set! The Innkeeper has received your 
                reservation and will begin<br> preparing for you arrival. Until then, 
                you should receive an email containing important<br> information, 
                such as a summary of your reservations and directions to the Inn.
                Pet fee<br> will be assessed upon check in.
            </p>
            <ul class='concerns'>
                <li>Your deposit fee is: $<?php echo number_format($deposit, 2); ?></li>    
                <li>Special Requests: <?php echo $SpecialReq ?></li>
            </ul>
            <br>
            <p class='numText'>Your confirmation number is: <?php echo $resID ?></p><p class='confirmationNum'></p>
            <p class="innMessage">
                Please print out or write down this confirmation number. 
                This number will be helpful in confirming your reservation 
                at the Yorkshire Inn.

                Welcome home!- Yorkshire Inn Innkeeper
            </p>
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
                <td>$<?php echo number_format($roomPrice, 2); ?></td>
                <td>$<?php echo number_format($price, 2); ?></td>
            </tr>
            <tr>
                <td>Guest fee ($30 per guest)</td>
                <td><?php echo $numGuests; ?></td>
                <td>$<?php echo number_format($totalGuestFee, 2); ?></td>
                <td>$<?php echo number_format($totalWGuestFee, 2); ?></td>
            </tr>
            <tr>
                <td>NYS Sales Tax (8%)</td>
                <td>$<?php echo number_format($totalWGuestFee, 2); ?> x .08</td>
                <td>$<?php echo number_format($salesTax, 2); ?></td>
                <td>$<?php echo number_format($grandTotal, 2); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>TOTAL DUE*</td>
                <td>$<?php echo number_format($grandTotal, 2); ?></td>
            </tr>
        </table>
    </div>
            <button class='submitPay' onclick="window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/home.php';">GO TO HOME PAGE</button>
            <br>
            <br>
         </div>
<?php
// Used to format the page
include('footer.php');

// Attempts to reset cookie data
setcookie('checkIn','',time()-3600);
setcookie('checkOut','',time()-3600);
setcookie('numGuests','',time()-3600);

// Ends the session
session_destroy();
?>
