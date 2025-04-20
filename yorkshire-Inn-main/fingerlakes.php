<?php
/*
YORKSHIRE INN REDESIGN

Purpose: To give information on Finger Lakes and the events that happen there


*/
?>

<?php
// Starts the session, needed for SESSION variables
session_start();


// Used for formatting
include('header.php');

// Used to check if there are session variables for filling check in, check out, and number of guests
$checkIn = isset($_POST["checkIn"]) ? $_POST["checkIn"] : (isset($_SESSION['checkIn']) ? $_SESSION['checkIn'] : '');
$checkOut = isset($_POST["checkOut"]) ? $_POST["checkOut"] : (isset($_SESSION['checkOut']) ? $_SESSION['checkOut'] : '');
$numGuests = isset($_POST["numGuests"]) ? $_POST["numGuests"] : (isset($_SESSION['numGuests']) ? $_SESSION['numGuests'] : '');

// set the session variables
$_SESSION["checkIn"] = $checkIn;
$_SESSION["checkOut"] = $checkOut;
$_SESSION["numGuests"] = $numGuests;

?>
<!-- title over image--->
<div class="container">
    <img src="assets/img/fingerlakes.png" alt="fingerlakes" style="width:100%;">
    <div class="right">
        <h1>Did you know?</h1>
        <p>The Finger Lakes District of New York State was named the Top Wine
            Region in the United States in a 2018 reader poll by USA Today.</p>
        <button class="explorebtn" type="button"><a class="buttonLink" href="https://www.newyorkupstate.com/drinks/2018/08/finger_lakes_named_americas_top_wine_region_in_usa_today_poll.html">FIND OUT MORE</a></button>
    </div>
</div>

<!-- Check availability form--->
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
               oninput="checkDate()">
        <label for="checkOut">Check Out:</label>
        <input type="date" id="checkOut" name="checkOut" 
               min="<?php echo isset($_SESSION['checkIn']) ? date('Y-m-d', strtotime($_SESSION['checkIn'] . ' + 2 days')) : date('Y-m-d'); ?>"  
               max="<?php echo isset($_SESSION['checkOut']) ? date('Y-m-d', strtotime($_SESSION['checkIn'] . ' + 14 days')) : date('Y-m-d', strtotime('+1 year')); ?>" 
               value="<?php echo isset($_SESSION['checkOut']) ? $_SESSION['checkOut'] : ''; ?>"
               <?php if(!isset($_SESSION['checkIn'])) { echo "disabled"; } ?>
               oninput="checkDate()" required> <!-- update event from onchange to oninput -->
        <input type="number" id="numGuests" name="numGuests" min="1" max="6" value="<?php echo isset($_SESSION['numGuests']) ? $_SESSION['numGuests'] : ''; ?>"placeholder="Guest Num" required>
        <button type="submit">Search</button>
        <script>
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
        </script>
    </form>
</div>
<?php    
    // set the session variables
    $_SESSION["checkIn"] = $checkIn;
    $_SESSION["checkOut"] = $checkOut;
    $_SESSION["numGuests"] = $numGuests;
?>

<!-- Grid & content
     Textcontainer centers the text in the middle of the page
     The following is then put into rows and columns on the page-->
<div class="grid-wrapper">
    <div class="textcontainer">
        <p>The Finger Lakes Region is known for many artisan vineyards, particularly its world-renowned
            Rieslings. The region also boasts an impressive list of craft breweries, distilleries, and
            hard-cider producers. New York, by the way, is one of the largest apple-growing states in
            the country, second only to the state of Washington.
            The area offers activities for all ages. From the Glenn H. Curtiss Aviation Museum to the
            George Eastman House and the Rochester Children\'s Museum, museums make the perfect day trip.
            If you prefer to spend your time outdoors, there are plenty of hiking and biking trails and
            places to go boating or fishing on the lakes. A local favorite is exploring the gorge trail
            in Watkins Glen State Park, which has no less than nineteen waterfalls!</p>
    </div>
    <div class="textcontainer">
        <h1>Events in the Finger Lakes</h1>
    </div>
    <div class="row">
        <div class="col">
            <h2>Craft Brewery Crawl</h2>
            <div class="row">
                <div class="innercol">
                    <p><b>Who:</b> Craft beer enthusiasts, adventure seekers</p>
                    <p><b>Where:</b> Several craft breweries around the Fingerlakes region</p>
                    <p><b>Meeting Place:</b> The Yorkshire Inn</p>
                    <p><b>Cost:</b> $35/person (ID required)</p>
                </div>
                <div class="innercol">
                    <img src="assets/img/craftbrewery.png" alt="craftbrewery" style="width:100%;">
                </div>
            </div>
        </div>
        <div class="col">
            <h2>Wine-Making 101</h2>
            <div class="row">
                <div class="innercol">
                    <p><b>Who:</b> Brewmasters, adventure seekers</p>
                    <p><b>Where:</b> Waterside Wine Bar</p>
                    <p><b>Meeting Place:</b> Waterside Wine Bar</p>
                    <p><b>Cost:</b> $50/person (ID required)</p>
                </div>
                <div class="innercol">
                    <img src="assets/img/winemaking.png" alt="winemaking" style="width:100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Used for formatting
include('footer.php');
?>
