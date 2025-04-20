<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Based on room availability and number of guests, customers can choose to book one of the four rooms.


*/
?>

<?php
// Starts the session, needed for SESSION variables
session_start();

// Requires PHP page that handles database connection
require_once('dbconfig.php');

// Used for formatting
include('header.php');

// Save form data in session
$checkIn = isset($_POST["checkIn"]) ? $_POST["checkIn"] : (isset($_SESSION['checkIn']) ? $_SESSION['checkIn'] : '');
$checkOut = isset($_POST["checkOut"]) ? $_POST["checkOut"] : (isset($_SESSION['checkOut']) ? $_SESSION['checkOut'] : '');
$numGuests = isset($_POST["numGuests"]) ? $_POST["numGuests"] : (isset($_SESSION['numGuests']) ? $_SESSION['numGuests'] : '');

// Misc. variables
$checkinDate = new DateTime($checkIn);
$checkoutDate = new DateTime($checkOut);

// Used to determine number of of nights
$numNights = $checkinDate->diff($checkoutDate)->format('%a');
$numNightsCalc = $numNights++;

// set the session variables
$_SESSION["checkIn"] = $checkIn;
$_SESSION["checkOut"] = $checkOut;
$_SESSION["numGuests"] = $numGuests;

$numGuests = $_POST['numGuests'];

// SQL query that gets the price of the Blue Room from the database and stores it as a variable
$roomPrice1 = "SELECT room1Price AS roomPrice1 FROM price WHERE priceDate = '$checkIn'";
$roomResult1 = mysqli_query($conn, $roomPrice1);
$roomPriceRow1 = $roomResult1->fetch_assoc();
$roomPrice1 = $roomPriceRow1['roomPrice1'];

// SQL query that gets the price of the Bolero Room from the database and stores it as a variable
$roomPrice2 = "SELECT room2Price AS roomPrice2 FROM price WHERE priceDate = '$checkIn'";
$roomResult2 = mysqli_query($conn, $roomPrice2);
$roomPriceRow2 = $roomResult2->fetch_assoc();
$roomPrice2 = $roomPriceRow2['roomPrice2'];

// SQL query that gets the price of the Rose Suite from the database and stores it as a variable
$roomPrice3 = "SELECT room3Price AS roomPrice3 FROM price WHERE priceDate = '$checkIn'";
$roomResult3 = mysqli_query($conn, $roomPrice3);
$roomPriceRow3 = $roomResult3->fetch_assoc();
$roomPrice3 = $roomPriceRow3['roomPrice3'];

// SQL query that gets the price of the BLodge Suite from the database and stores it as a variable
$roomPrice4 = "SELECT room4Price AS roomPrice4 FROM price WHERE priceDate = '$checkIn'";
$roomResult4 = mysqli_query($conn, $roomPrice4);
$roomPriceRow4 = $roomResult4->fetch_assoc();
$roomPrice4 = $roomPriceRow4['roomPrice4'];

// SQL query that gets the availability of the Blue room from the database and stores it as a variable
$isUnavailable1 = mysqli_query($conn, "SELECT COUNT(*) FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut' AND isAvailableBool1 = 1");
$count1 = intval(mysqli_fetch_array($isUnavailable1)[0]);
$isUnavailable2 = mysqli_query($conn, "SELECT COUNT(*) FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut' AND isAvailableBool2 = 1");
$count2 = intval(mysqli_fetch_array($isUnavailable2)[0]);
$isUnavailable3 = mysqli_query($conn, "SELECT COUNT(*) FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut' AND isAvailableBool3 = 1");
$count3 = intval(mysqli_fetch_array($isUnavailable3)[0]);
$isUnavailable4 = mysqli_query($conn, "SELECT COUNT(*) FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut' AND isAvailableBool4 = 1");
$count4 = intval(mysqli_fetch_array($isUnavailable4)[0]);

// If/else statements that runs a scripts on page load to check and block off buttons if the count of the number 
// of available nights doesn't equal the number of nights the customer wants to stay. It also takes into account
// the number of guests is great than two for the rooms that don't allow for more than two guests to stay in them.
if ($count1 != $numNightsCalc || $numGuests > 2) {
?>
<script>
     window.onload = function() {
          var bookNowBtn1 = document.getElementById("room1");
          if(<?php echo $count1 ?> != <?php echo $numNights ?> || <?php echo $numGuests ?> > 2) {
               bookNowBtn1.disabled = true;
               bookNowBtn1.style.opacity = 0.5;
               bookNowBtn1.textContent = "UNAVAILABLE";
          }

          var bookNowBtn2 = document.getElementById("room2");
          if(<?php echo $count2 ?> != <?php echo $numNights ?> || <?php echo $numGuests ?> > 2) {
               bookNowBtn2.disabled = true;
               bookNowBtn2.style.opacity = 0.5;
               bookNowBtn2.textContent = "UNAVAILABLE";
          }

          var bookNowBtn3 = document.getElementById("room3");
          if(<?php echo $count3 ?> != <?php echo $numNights ?>) {
               bookNowBtn3.disabled = true;
               bookNowBtn3.style.opacity = 0.5;
               bookNowBtn3.textContent = "UNAVAILABLE";
          }

          var bookNowBtn4 = document.getElementById("room4");
          if(<?php echo $count4 ?> != <?php echo $numNights ?>) {
               bookNowBtn4.disabled = true;
               bookNowBtn4.style.opacity = 0.5;
               bookNowBtn4.textContent = "UNAVAILABLE";
          }
     };
</script>

<?php
     
}

?>
<div class='bookingSelect'>
    <h2 style='text-align: center;'>Select Your Room</h2>
    <p style='text-align: center;'>
        The Yorkshire Inn offers four rooms for overnight stays.
        Travelers will enjoy plush<br> mattresses, lavish decorations,
        and plenty of space to stretch out after a long drive. See<br>
        what The Yorkshire Inn has to offer you!
    </p>
</div>
<!-- 
    CheckIn:    The first available day is the next day to avoid same day bookings. Sets the max check in date two weeks before a full year out so that the last 
                possible day to be in the room is a year out. Fills in with SESSION data if it exists, if not, field is empty. This field must be entered to continue.
                When entered, runs checkDate script.
    CheckOut:   Doesn't allow booking two days after check in. Doesn't allow check out more than two weeks after check in. If the session variable exists, it fills
                in the check out, if not, it's empty. If check in date is changed while check out is filled in, the field is cleared. This field must be entered
                to continue. When entered, runs checkDate script.
    numGuests:  Cannot have less than one guest or more than 6 in a stay. If a session variables exists, it fills in the num guests field, if not, it's empty.
-->
<div class="center">
    <form class="form-inline" action="./booking.php" method="POST">
        <label for="checkIn">Check In:</label>
        <input type="date" id="checkIn" name="checkIn"
               min="<?php echo date('Y-m-d'); ?>" 
               max="<?php echo date('Y-m-d', strtotime('+351 days')); ?>"
               value="<?php echo isset($_SESSION['checkIn']) ? $_SESSION['checkIn'] : ''; ?>" required
               oninput="checkDate(); submitForm(); resetCheckOut()">
        <label for="checkOut">Check Out:</label>
        <input type="date" id="checkOut" name="checkOut" 
               min="<?php echo isset($_SESSION['checkIn']) ? date('Y-m-d', strtotime($_SESSION['checkIn'] . ' + 2 days')) : date('Y-m-d'); ?>"  
               max="<?php echo isset($_SESSION['checkOut']) ? date('Y-m-d', strtotime($_SESSION['checkIn'] . ' + 14 days')) : date('Y-m-d', strtotime('+1 year')); ?>" 
               value="<?php echo isset($_SESSION['checkOut']) ? $_SESSION['checkOut'] : ''; ?>"
               <?php if(!isset($_SESSION['checkIn'])) { echo "disabled"; } ?>
               oninput="checkDate(); submitForm()" required> <!-- update event from onchange to oninput -->
        <input type="number" id="numGuests" name="numGuests" min="1" max="6" value="<?php echo isset($_SESSION['numGuests']) ? $_SESSION['numGuests'] : ''; ?>"
               placeholder="Guest Num" onchange="submitForm()" required>
        <button type="submit">Search</button> 
        <script>
          const bookingForm = document.getElementById('booking-form');

          const checkInInput = document.getElementById('checkIn');
          const checkOutInput = document.getElementById('checkOut');
          const numGuestsInput = document.getElementById('numGuests');

          checkInInput.addEventListener('change', submitForm);
          checkOutInput.addEventListener('change', submitForm);
          numGuestsInput.addEventListener('change', submitForm);

          // Function that acts as a button press to force the page to reload, checking the availability of the rooms for the new dates 
          // input into the calendar by the customer
          function submitForm() {
               bookingForm.submit();
          }
          // CheckDate:   Checks to see if the checkOut date is earlier than checkIn date. If it is, it sets off an alert that lets the user know
          //              tells the customer and resets the field, resetting and blocking off the correct mininmum/max check in and check out dates.
          function checkDate() {
                const checkIn = new Date(document.getElementById('checkIn').value);
                const checkOutElement = document.getElementById('checkOut');
                const checkOut = new Date(checkOutElement.value);

                if (checkOut < checkIn) {
                    alert('Check out date cannot be earlier than check in date and must be two days after check in.');
                    checkOutElement.value = '';
                    
                    const checkOutMinDate = new Date(checkIn);
                    checkOutMinDate.setDate(checkIn.getDate() + 2);
                    checkOutElement.min = checkOutMinDate.toISOString().split('T')[0];
                    const checkOutMaxDate = new Date(checkIn);
                    checkOutMaxDate.setDate(checkIn.getDate() + 14);
                    checkOutElement.max = checkOutMaxDate.toISOString().split('T')[0];
                } else {
                    const checkOutMinDate = new Date(checkIn);
                    checkOutMinDate.setDate(checkIn.getDate() + 2);
                    checkOutElement.min = checkOutMinDate.toISOString().split('T')[0];
                    const checkOutMaxDate = new Date(checkIn);
                    checkOutMaxDate.setDate(checkIn.getDate() + 14);
                    checkOutElement.max = checkOutMaxDate.toISOString().split('T')[0];
                }
          }
          
          // Function that gets the checkIn and checkOut dates from the fields. It then calculates the number of nights
          // between the dates and converts it to days. If the number of nights it greater than 14 days, it alerts the
          // customer and tells them to contact the inn keeper to talk about setting up an extended stay. It also resets
          // the calendar min/max checkIn and checkOut rules to the new checkIn date on page load.
          function setMaxStay() {
               const checkIn = new Date(document.getElementById('checkIn').value);
               const checkOutElement = document.getElementById('checkOut');
               const checkOut = new Date(checkOutElement.value);
               const maxStay = 14; // maximum stay in days

               const numNights = Math.floor((checkOut - checkIn) / (1000 * 60 * 60 * 24));
               if (numNights > maxStay) {
                    alert('The maximum stay when booking online is 14 days. Please contact the owner for a longer stay.');
                    checkOutElement.value = '';
                    
                    const checkOutMaxDate = new Date(checkIn);
                    checkOutMaxDate.setDate(checkIn.getDate() + maxStay);
                    checkOutElement.value = checkOutMaxDate.toISOString().split('T')[0];
                    checkOutElement.value = '';// checkOutElement.max = checkOutMaxDate.toISOString().split('T')[0];
               } else {
                    const checkOutMinDate = new Date(checkIn);
                    checkOutMinDate.setDate(checkIn.getDate() + 2);
                    checkOutElement.min = checkOutMinDate.toISOString().split('T')[0];
                    const checkOutMaxDate = new Date(checkIn);
                    checkOutMaxDate.setDate(checkIn.getDate() + maxStay);
                    checkOutElement.max = checkOutMaxDate.toISOString().split('T')[0];
               }
          }
          
          setMaxStay();
        </script>
    </form>
</div>
<div class='row' id='bookingRow'>
    <div class='room'>
        <div class='row'>
            <div class='col' id='bookingCol'>
                <h4><strong>Blue Room</strong></h4>
                <p>
                    The Blue Room sleeps 2 in a queen bed and features a
                    wall of south-facing windows overlooking the deck and
                    back yard. Private bath with an oversized standup
                    shower.
                </p>
                <button class='bookingButton' id="room1" onClick="bookNow1()">Rate: $<?php echo number_format($roomPrice1, 2); ?> night <br> BOOK NOW</button>
                    <script>
                    function bookNow1() 
                    {
                        // get form data
                        const checkIn = document.getElementById("checkIn").value;
                        const checkOut = document.getElementById("checkOut").value;
                        const numGuests = document.getElementById("numGuests").value;

                        // set cookie with form data
                        document.cookie = `checkIn=${checkIn}`;
                        document.cookie = `checkOut=${checkOut}`;
                        document.cookie = `numGuests=${numGuests}`;

                        // redirect to payment page
                        window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/blue-payment.php';
                    }
                </script>
            </div>
            <div class='col' id='bookingCol'>
                <h4><strong>Amenities</strong></h4>
                <ul class='bookingList'>
                    <li>Sleeps 2</li>
                    <li>Bathrobes</li>
                    <li>Hair dryer</li>
                    <li>Private bathroom</li>
                    <li>Shower</li>
                    <li>Air conditioning</li>
                    <li>TV</li>
                    <li>Free WiFi</li>
                    <li>Daily housekeeping</li>
                    <li>Desk</li>
                    <li>Balcony</li>
                </ul>
            </div>
            <!-- Slideshow container
            <div class="slideshow-container fade">
                <div class="Containers">
                <div class="MessageInfo">1 / 3</div>
                <img src="blue1.png" style="width:100%">
            </div>
            <div class="Containers">
                <div class="MessageInfo">2 / 3</div>
                <img src="blue2.png" style="width:100%">
            </div>
            <div class="Containers">
                <div class="MessageInfo">3 / 3</div>
                <img src="blue3.png" style="width:100%">
            </div>
                <a class="Back" onclick="plusSlides(-1)">&#10094;</a>
                <a class="forward" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <br>
            <div style="text-align:center">
                <span class="dots" onclick="currentSlide(1)"></span>
                <span class="dots" onclick="currentSlide(2)"></span>
                <span class="dots" onclick="currentSlide(3)"></span>
            </div> -->
            <div class='col' id='bookingCol'>
                <img src='assets/img/blue.jpg' alt='Blue Room Photo'>
            </div>
        </div>
    </div>

    <div class='room'>
        <div class='row'>
            <div class='col' id='bookingCol'>
                <h4><strong>Bolero Room</strong></h4>
                <p>
                    The Bolero Room is our premium, private, romantic suite.
                    It sleeps two in style and comfort in a king bed with
                    an oversized bath featuring a two person jacuzzi tub.
                </p>
                <button class='bookingButton' id="room2" onClick="bookNow2()">Rate: $<?php echo number_format($roomPrice2, 2); ?> night <br> BOOK NOW</button>
            <script>
                function bookNow2() 
                {
                    // get form data
                    const checkIn = document.getElementById("checkIn").value;
                    const checkOut = document.getElementById("checkOut").value;
                    const numGuests = document.getElementById("numGuests").value;
                     // set cookie with form data
                    document.cookie = `checkIn=${checkIn}`;
                    document.cookie = `checkOut=${checkOut}`;
                    document.cookie = `numGuests=${numGuests}`;

                    // redirect to payment page
                    window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/bolero-payment.php';
                }
            </script>
            </div>
            <div class='col' id='bookingCol'>
                <h4><strong>Amenities</strong></h4>
                <ul class='bookingList'>
                    <li>Sleeps 2</li>
                    <li>Bathrobes</li>
                    <li>Hair dryer</li>
                    <li>Private bathroom</li>
                    <li>Shower</li>
                    <li>Jacuzzi Tub</li>
                    <li>Air conditioning</li>
                    <li>TV</li>
                    <li>Free WiFi</li>
                    <li>Daily housekeeping</li>
                    <li>Fireplace</li>
                </ul>
            </div>
            <div class='col' id='bookingCol'>
                <img src='assets/img/bolero.jpg' alt='Bolero Room Photo'>
            </div>
        </div>
    </div>

    <div class='room'>
        <div class='row'>
            <div class='col' id='bookingCol'>
                <h4><strong>Rose Suite</strong></h4>
                <p>
                    The Rose Suite is a two-bedroom suite that sleeps up
                    to 6 individuals. Includes 2 queen beds and a daybed
                    with trundle. The private bath features an over sized
                    stand-up shower.
                </p>
                <button class='bookingButton' id="room3" onClick="bookNow3()">Rate: $<?php echo number_format($roomPrice3, 2); ?> night <br> BOOK NOW</button>
                <script>
                        function bookNow3() 
                    {
                    // get form data
                    const checkIn = document.getElementById("checkIn").value;
                    const checkOut = document.getElementById("checkOut").value;
                    const numGuests = document.getElementById("numGuests").value;
                    const RoomID = 3; // set the RoomID here

                    // set cookie with form data
                    document.cookie = `checkIn=${checkIn}`;
                    document.cookie = `checkOut=${checkOut}`;
                    document.cookie = `numGuests=${numGuests}`;

                    // redirect to payment page
                    window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/rose-payment.php';
                    }
                </script>
            </div>
            <div class='col' id='bookingCol'>
                <h4><strong>Amenities</strong></h4>
                <ul class='bookingList'>
                    <li>Sleeps 6</li>
                    <li>Bathrobes</li>
                    <li>Hair dryer</li>
                    <li>Private bathroom</li>
                    <li>Shower</li>
                    <li>Air conditioning</li>
                    <li>TV</li>
                    <li>Free WiFi</li>
                    <li>Daily housekeeping</li>
                    <li>Desk</li>
                    <li>Deck</li>
                </ul>
            </div>
            <div class='col' id='bookingCol'>
                <img src='assets/img/rose.jpg' alt='Rose Suite Photo'>
            </div>
        </div>
    </div>

    <div class='room'>
        <div class='row'>
            <div class='col' id='bookingCol'>
                <h4><strong>Lodge Suite</strong></h4>
                <p>
                    The Lodge Suite is a two-bedroom suite that sleeps up
                    to 6 individuals. It features two bedrooms with a queen,
                    full, and two twin mattresses.
                </p>
                <button class='bookingButton' id="room4" onClick="bookNow4()">Rate: $<?php echo number_format($roomPrice4, 2); ?> night <br> BOOK NOW</button>
            </div>
            <script>
                function bookNow4() 
                {
                    // get form data
                    const checkIn = document.getElementById("checkIn").value;
                    const checkOut = document.getElementById("checkOut").value;
                    const numGuests = document.getElementById("numGuests").value;

                    // set cookie with form data
                    document.cookie = `checkIn=${checkIn}`;
                    document.cookie = `checkOut=${checkOut}`;
                    document.cookie = `numGuests=${numGuests}`;

                    // redirect to payment page
                    window.location.href = 'https://ec2-54-80-23-92.compute-1.amazonaws.com/lodge-payment.php';
                }
            </script>
            <div class='col' id='bookingCol'>
                <h4><strong>Amenities</strong></h4>
                <ul class='bookingList'>
                    <li>Sleeps 6</li>
                    <li>Bathrobes</li>
                    <li>Hair dryer</li>
                    <li>Private bathroom</li>
                    <li>Tub/Shower</li>
                    <li>Air conditioning</li>
                    <li>TV</li>
                    <li>Free WiFi</li>
                    <li>Daily housekeeping</li>
                    <li>4 Beds</li>
                    <li>Dresser</li>
                </ul>
            </div>
            <div class='col' id='bookingCol'>
                <img src='assets/img/lodge.jpg' alt='Lodge Suite Photo'>
            </div>
        </div>
    </div>
    <div class='contactUs'>
        <h2><strong>Have a question about booking with us?</strong></h2>
        <button class='bookingButton'>Contact Us</button>
    </div>
</div>
<script>
    function submitForm() 
    {
        document.querySelector("form").submit();
    }
</script>

<?php
// Used to format the page
include('footer.php');
?>
