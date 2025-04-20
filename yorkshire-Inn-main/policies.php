

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
// set the session variable
$_SESSION["checkIn"] = $checkIn;
$_SESSION["checkOut"] = $checkOut;
$_SESSION["numGuests"] = $numGuests;
?>

<!-- Texcontainer creates a box to hold the content of the page,
     The rest has minimal styling, just tags to define paragraphs and lists-->
<div class="textcontainer">
    <h1>Policies at the Inn</h1>
    <p class = "policytitle">CANCELLATION GUIDELINES:</p>
    <ul>
        <li>A $15.00 cancellation fee for all cancellations results in a refund.</li>
        <li>Cancellations made more than 14 days before the scheduled check-in date and time will qualify for a
            100%
            refund of the initial 1-night room deposit less the $15 cancellation fee.</li>
        <li>Cancellations made between 14 and 7 days before the scheduled check-in date and time will qualify
            for a
            50% refund of the initial 1-night room deposit, less the $15 cancellation fee.</li>
        <li>If your reservation is canceled within 7 days of the scheduled check-in date, there is no refund of
            the
            initial 1-night room deposit, and no additional cancellation fee is charged.</li>
        <li>In the event of a "no-show," guests will be charged the full balance of the entire reservation
            duration.
        </li>
        <li>There is no refund for early departures.</li>
        <li>Guests are responsible for paying the full remaining balance upon departure.</li>
        <li>Check-in time is 3:00pm – 6:00pm</li>
        <li>Check-out time is 11 am</li>
    </ul><br>

    <p class ="policytitle">ADDITIONAL GUEST FEE:</p>
    <ul>
        <li>Additional guests can be accommodated in the Rose Room and the Lodge.</li>
        <li>An additional guest fee of $30.00 per guest per night will be added to your balance if booking one
            of
            the identified rooms with more than 2 people, including children.</li>
        <li>Any guest inviting an additional guest to stay in any room without notification to The Yorkshire Inn
            staff will be charged a fee of $150/night for each night the guest stays.</li>
    </ul><br>

    <p class="policytitle">SMOKING:</p>
    <ul>
        <li>There is no smoking within the Inn.</li>
        <li>Smoking is allowed outside, and guests are responsible for cleaning up the ashes.</li>
        <li>There is a $250 fine for smoking inside the Inn.</li>
    </ul>
</div>
</div>

<?php
// Used for formatting
include('footer.php');
?>
