

<?php
session_start();

include('header.php');

$checkIn = isset($_POST["checkIn"]) ? $_POST["checkIn"] : (isset($_SESSION['checkIn']) ? $_SESSION['checkIn'] : '');
$checkOut = isset($_POST["checkOut"]) ? $_POST["checkOut"] : (isset($_SESSION['checkOut']) ? $_SESSION['checkOut'] : '');
$numGuests = isset($_POST["numGuests"]) ? $_POST["numGuests"] : (isset($_SESSION['numGuests']) ? $_SESSION['numGuests'] : '');

// set the session variables
$_SESSION["checkIn"] = $checkIn;
$_SESSION["checkOut"] = $checkOut;
$_SESSION["numGuests"] = $numGuests;

?>
<!-- title over image
     The header and paragraph are styled to be layered over the image on the right side--->
<div class='container'>
    <img src='assets/img/homeimg.png' alt='inn' style='width:100%;'>
    <div class='content'>
      <h1>WELCOME TO THE YORKSHIRE INN</h1>
      <p>The Yorkshire Inn is a family-owned
        Bed & Breakfast located in the heart of
        the New York Finger Lakes. Located in Phelps,
        New York, the Yorkshire Inn is steps away from
        several artisan vineyards, distilleries, and craft
        breweries. </p>
        <button class ='explorebtn' type='button'><a class="buttonLink" href="history.php">EXPLORE MORE</a></button>
    </div>
</div>

<!-- Check availability form to go create a booking in booking.php--->
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

<!-- Grid & content
     Organized in rows and columns-->
<div class='grid-wrapper'>
    <div class='row'>
      <div class='col'>
          <h2>Dining</h2>
          <p>A freshly cooked farm to table breakfast served every morning. We use locally sourced and organic produce, eggs and meat when they are available. If you let us know of any special dietary requests upfront we will be more than happy to accommodate.
            There is freshly brewed coffee and tea available first thing in the morning, and we have Keurig coffee machines available throughout the day.
            We are trying to be kind to the environment and compost with Impact Earth (www.impactearthroc.com)</p>
      </div>
      <div class='col'>
        <img src='assets/img/diningimg.png' alt='dining' style='width:100%;'>
      </div>
    </div>
    <div class='row'>
      <div class='col'>
        <img src='assets/img/amenitiesimg.png' alt='amenities' style='width:100%;'>
      </div>
      <div class='col'>
          <h2>Amenities</h2>
          <p>We offer a variety of amenities to make your stay as comfortable as possible. Each room has its own private bathroom and cable TV. There is free WiFi throughout the house as well as on the back deck.
            All guest rooms are on the first floor with no steps or stairs from the large parking lot to any of the rooms. There is central heating air conditioning in each room. We proudly provide low waste toiletries in all the rooms from Marilla's Mindful Supplies (https://marillas.com/) </p>
      </div>
    </div>
    <div class='row'>
      <div class='col'>
          <h2>Before You Book</h2>
          <p>We want you to be as comfortable as possible during your stay and in order to do that we ask you please let us know prior to booking if you have any questions or concerns. You can do that by calling or emailing the Inn directly.
            We do try to accommodate all dietary needs or restrictions but do need to know of them in advance of your stay.
            We also have pets, a cat and a dog, that live in the Inn as this is our home as well. If you have any allergies or concerns about the animals we highly recommend you call us prior to booking or consider other accommodations.</p>
      </div>
      <div class='col'>
        <img src='assets/img/petsimg.png' alt='pets' style='width:100%;'>
      </div>
    </div>
  </div>

<?php  
include('footer.php');
?>
