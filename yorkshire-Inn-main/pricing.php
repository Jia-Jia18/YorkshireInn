<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Admin page that updates the prices of each room at the inn


*/
?>

<?php
session_start();
require "dbconfig.php"; //Requires PHP page that handles database connection
//Login enforcement
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }
// php code to Update data from mysql workbench database

//POST variables
   $blue = $_POST['blue'];
   $bolero = $_POST['bolero'];
   $lodge = $_POST['lodge'];
   $rose = $_POST['rose'];
   $startPriceDate = $_POST['startPriceDate'];    
   $endPriceDate = $_POST['endPriceDate'];     
           
   // mysql queries to Update the price for the rooms using the "BETWEEN & AND" operators which selects values within a giving start date and end date.
   yorkshireQuery("UPDATE price SET room1Price='$blue' WHERE priceDate BETWEEN '$startPriceDate' AND '$endPriceDate'");
   yorkshireQuery("UPDATE price SET room2Price='$bolero' WHERE priceDate BETWEEN '$startPriceDate' AND '$endPriceDate'");
   yorkshireQuery("UPDATE price SET room3Price='$lodge' WHERE priceDate BETWEEN '$startPriceDate' AND '$endPriceDate'");
   yorkshireQuery("UPDATE price SET room4Price='$rose' WHERE priceDate BETWEEN '$startPriceDate' AND '$endPriceDate'");


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Pricing</title>
    <link href="homecss.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8" />
</head>

<body>
    <!-- Modified header that just displays yorkshire inn logo. When clicked, takes user to admin menu
         Also includes a logout button on the top right corner -->
    <div class=header>
        <div class="headercol">
            <a href='./adminmenu.php'><img src='assets/img/yorkshire.jpg' alt='Yorkshire Inn Logo' style="width:300px;"></a>
        </div>
        <div class="headercol">
            <button class="logout" type="button">Logout</button>
        </div>
    </div>

    <!-- Body of the page,  grid-wrapper centers the text, pricingform holds the form and styles it- centering it and padding the contents.
         Each input changes the respective price for the room-->
    <div class="grid-wrapper">
        <h1>Employee Actions - Change Pricing</h1>

        <form class="pricingform" action="pricing.php" method=POST>
            <div>
                <label for="blue">Blue Room:</label>
                <input type="text" id="blue" name="blue" required>
            </div>
            <div>
                <label for="bolero">Bolero Room:</label>
                <input type="text" id="bolero" name="bolero" required>
            </div>
            <div>
                <label for="lodge">Lodge Suite:</label>
                <input type="text" id="lodge" name="lodge" required>
            </div>
            <div>
                <label for="rose">Rose Suite:</label>
                <input type="text" id="rose" name="rose" required>
            </div>
            <div>
                <label for="priceDate">Start Date:</label>
                <input type="date" id="priceDatepriceDate" name="startPriceDate" min="2023-03-27" required>
            </div>
            <div>
                <label for="priceDate">End Date:</label>
                <input type="date" id="priceDatepriceDate" name="endPriceDate" min="2023-03-27" required>
            </div>
            <div>
                <button type="submit">Update</button>
            </div>
        </form>

    </div>

    <!--Modified footer that just displays Yorkshire Inn logo and copyright-->
    <div class="footer">
        <div class='row'>
            <div class='footercol'>
                <img src='assets/img/yorkshire.jpg' alt='Yorkshire Inn Logo' style="width:300px;">
            </div>
            <div class='row'>
                <div class='footercol'>
                    <p>COPYRIGHT © 2022 THE YORKSHIRE INN - ALL RIGHTS RESERVED.</p>
                </div>
            </div>
        </div>
</body>

</html>
