<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Provide the user with a way to contact the Yorkshire Inn


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
    <img src="assets/img/contactus.png" alt="contactus" style="width:100%;">
    <div class="right">
        <h1>Have a Question</h1>
        <p>Reach out to us! We are available to answer your questions weekdays 8am - 9pm 
            and weekends 8am - 7pm.</p>
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
// Misc. SESSION variable for functionallity
$_SESSION["checkIn"] = $checkIn;
$_SESSION["checkOut"] = $checkOut;
$_SESSION["numGuests"] = $numGuests;
?>

<div class="googlemap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2920.2868592196246!2d-77.0381250487053!3d42.951158979049296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89d0d1c750b4eefd%3A0xe3f4212639fd53b2!2sThe%20Yorkshire%20Inn!5e0!3m2!1sen!2sus!4v1675183911072!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>


<!-- Grid & content
     Divided content into rows and columns, one column has the contact information for the inn.
     The other has a form where a user can ask a question-->
<div class="grid-wrapper">
    <div class="row">
        <div class="col">
            <h2>Contact us</h2>
            <p><b>Address: </b> The Yorkshire Inn, 1135 New York Highway 96, Phelps, NY 14532</p>
            <p><b>Phone: </b> (315) 548-9675</p>
            <p><b>E-mail: </b> Innkeeper@theyorkshireinn.com</p>
            <p><b>Facebook: </b> https://www.facebook.com/theyorkshireinn/</p>
            <p><b>Instagram: </b> @TheYorkshireinn</p>
        </div>
        <div class="col">
            <h2>Ask A Question</h2>
            <form class="formstack" action="util-sendemail.php">
                <div>
                <label for="name" class="indent">Name:</label>
                <input type="text" id="name" name="name">
            </div>
            <div>
                <label for="email" class="indent">E-mail:</label>
                <input type="text" id="email" name="name">
            </div>
            <div>
                <label class="msg" for="message">Message:</label>
                <textarea id="message" name="message"> </textarea>
            </div>
                <button type="submit" >SUBMIT</button>
            </form>
        </div>
    </div>
</div>

<?php
// Used for formatting
include('footer.php');
?>
