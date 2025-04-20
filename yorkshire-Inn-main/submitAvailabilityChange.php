

<?php
session_start();
require 'dbconfig.php';

//get the form data and set it equal to some variables
// r1 = room1 availablity data
// r2 = room2 availablity data
// r3 = room3 availablity data
// r4 = room4 availablity data
$startDate = $_POST['StartDate'];
$endDate = $_POST['EndDate'];
$r1 = $_POST['dropdown1'];
$r2 = $_POST['dropdown2'];
$r3 = $_POST['dropdown3'];
$r4 = $_POST['dropdown4'];

//declare blocked variables for isBlocked table
$r1Blocked = 0;
$r2Blocked = 0;
$r3Blocked = 0;
$r4Blocked = 0;

//logic to set value of isBlocked variable depending on isAvailable variable
if ($r1 == 0) {
    $r1Blocked = 1;
}

if ($r2 == 0) {
    $r2Blocked = 1;
}

if ($r3 == 0) {
    $r3Blocked = 1;
}

if ($r4 == 0) {
    $r4Blocked = 1;
}

//update all the tables
//update each room's entry in the isBlocked table to reflect form choice
$res1 = yorkshireQuery("UPDATE isBlocked SET isBlockedBool1 = '$r1Blocked' WHERE isBlockedDate BETWEEN '$startDate' AND '$endDate'");
$res2 = yorkshireQuery("UPDATE isBlocked SET isBlockedBool2 = '$r2Blocked' WHERE isBlockedDate BETWEEN '$startDate' AND '$endDate'");
$res3 = yorkshireQuery("UPDATE isBlocked SET isBlockedBool3 = '$r3Blocked' WHERE isBlockedDate BETWEEN '$startDate' AND '$endDate'");
$res4 = yorkshireQuery("UPDATE isBlocked SET isBlockedBool4 = '$r4Blocked' WHERE isBlockedDate BETWEEN '$startDate' AND '$endDate'");

//update each room's entry in the isAvailable table to reflect form choice
$av1 = yorkshireQuery("UPDATE isAvailable SET isAvailableBool1 = '$r1' WHERE isAvailableDate BETWEEN '$startDate' AND '$endDate'");
$av2 = yorkshireQuery("UPDATE isAvailable SET isAvailableBool2 = '$r2' WHERE isAvailableDate BETWEEN '$startDate' AND '$endDate'");
$av3 = yorkshireQuery("UPDATE isAvailable SET isAvailableBool3 = '$r3' WHERE isAvailableDate BETWEEN '$startDate' AND '$endDate'");
$av4 = yorkshireQuery("UPDATE isAvailable SET isAvailableBool4 = '$r4' WHERE isAvailableDate BETWEEN '$startDate' AND '$endDate'");

//post success message
header('Location: setavailability.php?status=success');
?>
