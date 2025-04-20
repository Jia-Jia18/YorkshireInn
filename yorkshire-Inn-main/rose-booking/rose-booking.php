<?php
session_start();
require 'dbconfig.php';
$checkIn = $_SESSION['checkIn'];
$checkOut = $_SESSION['checkOut'];
$numGuests = $_SESSION['numGuests'];
$RoomID = $_GET['RoomID'];
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
$RoomID = 4;
 
$insertTime = date("Y-m-d H:i:s");
$sql ="INSERT INTO traveler(FirstName, LastName, Phone, Email) VALUES ('$FirstName', '$LastName', '$phone', '$email')";
$result = mysqli_query($conn, $sql);

$sql = "INSERT INTO payment(PaymentDate, CreditCardNo, CCV, ZIPCode, ExpireDate) VALUES ('$insertTime', '$CreditCardNum', '$ccv', '$zip', '$expire')";
$result = mysqli_query($conn, $sql);

$sql = "SELECT PaymentID FROM payment WHERE PaymentDate = '$insertTime'";
$query = mysqli_query($conn, $sql);
$payIDArray = mysqli_fetch_assoc($query);
$payID = $payIDArray['PaymentID'];

$sql = "INSERT INTO reservation(BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit, RoomID, TravelerID, PaymentID) 
       VALUES ('$insertTime', '$checkIn', '$checkOut', '$numGuests', '$SpecialReq', '$TotalPayment', '$TotalDeposit', '$RoomID', '$travID', '$payID')";
$result = mysqli_query($conn, $sql);

$sql = "UPDATE isAvailable SET isAvailableBool1 = 0 WHERE isAvailableDate BETWEEN '" . $checkIn . "' AND '" . $checkOut . "'";
$result = mysqli_query($conn, $sql);

$query = "SELECT ReservationsID FROM reservation WHERE PaymentID = $payID";
$resIDAssoc = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($resIDAssoc);
$resID = $row['ReservationsID'];

$_SESSION['resID'] = $resID;
$_SESSION['SpecialReq'] = $SpecialReq;

mysqli_close($conn);
header("Location: confirmation.php?resID=$resID");
?>
