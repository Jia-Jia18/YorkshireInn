<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Handless the session variables from the checkIn, checkOut, numGuest fields as well as 
all the posted field data from bolero-payment.php page. Calculates neccessary billing math, 
runs SQL queries to push and pull various information to/from the database.

*/
?>

<?php
// Starts the session, needed for SESSION variables
session_start();

// Requires PHP page that handles database connection
require 'dbconfig.php';

// SESSION variables
$checkIn = $_SESSION['checkIn'];
$checkOut = $_SESSION['checkOut'];
$numGuests = $_SESSION['numGuests'];
$grandTotal2 = $_SESSION['grandTotal2'];

// POST variables
$FirstName = $_POST['fname'];
$LastName = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$SpecialReq = $_POST['request'];
$CreditCardName = $_POST['cardName'];
$CreditCardNum = $_POST['cardNum'];
$ccv = $_POST['csc'];
$zip = $_POST['zip'];
$expire = $_POST['expDate'];

// Misc. variables
$RoomID = 2;
$deposit2 = $grandTotal2 * .25;
$insertTime = date("Y-m-d H:i:s");

// SQL query that inserts a customers first name, last name, phone number, and email, into the traveler table
$sql ="INSERT INTO traveler(FirstName, LastName, Phone, Email) VALUES ('$FirstName', '$LastName', '$phone', '$email')";
$result = mysqli_query($conn, $sql);

// SQL query that inserts a customers payment date, credit card number, CCV, zip code, and credit cards expire date, into the payment table
$sql = "INSERT INTO payment(PaymentDate, CreditCardNo, CCV, ZIPCode, ExpireDate) VALUES ('$insertTime', '$CreditCardNum', '$ccv', '$zip', '$expire')";
$result = mysqli_query($conn, $sql);

// SQL query that gets the payment ID based on the time the payment date was inserted into the database and sets it to a variable
$sql = "SELECT PaymentID FROM payment WHERE PaymentDate = '$insertTime'";
$query = mysqli_query($conn, $sql);
$payIDArray = mysqli_fetch_assoc($query);
$payID = $payIDArray['PaymentID'];

// SQL query that gets the traveler ID based on a matching customer email address and sets it to a variable
$travFinder = "SELECT TravelerID FROM traveler WHERE Email = '$email'";
$travQuery = mysqli_query($conn, $travFinder);
$travIDArray = mysqli_fetch_assoc($travQuery);
$travID = $travIDArray['TravelerID'];

// SQL query that inserts a customers booking date, check in, check out, number of guests, special requests, total payment
// total deposit amount, room id, traveler, and payment ID, into the reservation table
$sql = "INSERT INTO reservation(BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit, RoomID, TravelerID, PaymentID) 
        VALUES ('$insertTime', '$checkIn', '$checkOut', '$numGuests', '$SpecialReq', '$grandTotal2', '$deposit2', '$RoomID', '$travID', '$payID')";
$result = mysqli_query($conn, $sql);

// SQL query blocks the availability of the room between the check in and check out dates
$sql = "UPDATE isAvailable SET isAvailableBool2 = 0 WHERE isAvailableDate BETWEEN '" . $checkIn . "' AND '" . $checkOut . "'";
$result = mysqli_query($conn, $sql);


// SQL query that gets the reservation ID based on a matching payment ID and sets it to a variable
$query = "SELECT ReservationsID FROM reservation WHERE PaymentID = $payID";
$resIDAssoc = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($resIDAssoc);
$resID = $row['ReservationsID'];

// Misc. SESSION variables for functionality
$_SESSION['resID'] = $resID;
$_SESSION['SpecialReq'] = $SpecialReq;

// Closes the database connection
mysqli_close($conn);

// Redirects the customer to bolero-confirmation.php and passes the reservation ID to the page as well as puts it in the URL
header("Location: bolero-confirmation.php?resID=$resID");
?>
