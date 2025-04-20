

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
<div class="title">
    <h1>Rooms at The Yorkshire Inn</h1>
    <p>The Yorkshire Inn offers four rooms for overnight stays. Travelers will enjoy 
        plush mattresses, lavish decorations, and plenty of space to stretch out 
        after a long drive. See what The Yorkshire Inn has to offer you!</p>
</div>
<!-- Check availability form--->
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
$checkIn = $_POST["checkIn"];
$checkOut = $_POST["checkOut"];
$numGuests = $_POST["numGuests"];
?>

<!-- Grid & content
     grid-wrapper centers the text, row creates rows and col adds a column to the row.
     Image styled to fit the entire of col-->
<div class="grid-wrapper">
    <div class="row">
      <div class="col">
          <h2>Blue Room</h2>
          <p>The Blue Room sleeps 2 in a queen bed and features a wall of south-facing windows 
            overlooking the deck and back yard. Private bath with an oversized standup shower.</p>
      </div>
      <div class="col">
        <img src="assets/img/blue.png" alt="blue room" style="width:100%;">
      </div>
    </div>
    <div class="row">
      <div class="col">
        <img src="assets/img/bolero.png" alt="bolero room" style="width:100%;">
      </div>
      <div class="col">
          <h2>Bolero Room</h2>
          <p>The Bolero Room is our premium, private, romantic suite. It sleeps two in style and 
            comfort in a king bed with an oversized bath featuring a two person jacuzzi tub and individual shower. </p>
      </div>
    </div>
    <div class="row">
      <div class="col">
          <h2>Lodge Suite</h2>
          <p>The Lodge Suite is a two-bedroom suite that sleeps up to 6 individuals. IT has a queen bed in the 
            main room with a full and two twins in the second bedroom. It is trimmed in rough sawn cedar and features 
            a bath with a tub and shower combination.</p>
      </div>
      <div class="col">
        <img src="assets/img/lodge.png" alt="lodge suite" style="width:100%;">
      </div>
    </div>
    <div class="row">
        <div class="col">
          <img src="assets/img/rose.png" alt="rose suite" style="width:100%;">
        </div>
        <div class="col">
            <h2>Rose Suite</h2>
            <p>The Rose Suite is a two-bedroom suite that sleeps up to 6 individuals. Includes 2 queen beds and 
                a daybed with trundle. It has large windows overlooking the deck and backyard. It has a second door 
                that opens directly onto the deck. The private bath features an oversized standup shower.</p>
        </div>
      </div>
  </div>

<?php  
// Used for formatting
include('footer.php');
?>
