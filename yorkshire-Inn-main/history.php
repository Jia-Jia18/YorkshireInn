

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
    <img src="assets/img/history.png" alt="History" style="width:100%;">
    <div class="right">
        <h1>THE HISTORY OF The Yorkshire Inn</h1>
        <p>The Yorkshire Inn was first settled in during 1824.
            Over the past 200 years, the Inn has seen many phases of history in New York.
            Check out the wealth of history that the Yorkshire Inn has to offer.</p>
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
    // set the session variable
    $_SESSION["checkIn"] = $checkIn;
    $_SESSION["checkOut"] = $checkOut;
    $_SESSION["numGuests"] = $numGuests;
?>

<!-- Grid & content
     Styled into rows and columns-->
<div class="grid-wrapper">
    <div class="row">
        <div class="col">
            <h2>Early Settling in New York</h2>
            <p>In 1789, not long after the U. S. Constitution was ratified,
                the first major wave of settlers began pouring into the wide-open
                spaces of western New York in search of farmland. In 1796, one of
                those settlers staked his claim to a large tract of land north of
                Seneca Lake and began building the two-story brick farmhouse that
                would one day be known as the Yorkshire Inn. For a bit of context,
                George Washington was still president, and Napoleon was on the March in Europe.</p>
        </div>
        <div class="col">
            <h2>The Swift Estate</h2>
            <p>The original owner, to this day unknown, sold the house and a large portion of his
                property to a man named Moses Swift in 1824. Legend has it that when his wife saw
                the home she complained that it was too small, so they built a second home just
                down the road from the original, and later added another for their children..
                The two brick homes to the east of the Inn are the original Swift residences.</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>Phelps, NY in the 1800s</h2>
            <p>With the advent of regular mail delivery in the early 1800s, stagecoach runs became
                a common occurrence. This meant, of course, that the coach would need fresh horses,
                and passengers would require food and lodging. So, a third floor was added to the original
                farmhouse to provide all the accommodations of a stagecoach stop, and the
                Yorkshire Inn was born. Evidence of these alterations can still be seen throughout
                the house today.
                With a total of six guest suites on the upper floors and a large gathering room on the first,
                the Inn must have been quite a bustling place. By 1840 there were two mills, two inns, two stores,
                three shops, and a one-room schoolhouse. Eventually, there would be a small railway station and a
                post office.
                The new village was named Unionville.</p>
        </div>
        <div class="col">
            <img src="assets/img/mainstreet.png" alt="mainstreet" style="width:100%;">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <img src="assets/img/canal.png" alt="canal" style="width:100%;">
        </div>
        <div class="col">
            <h2>The Growth of Phelps, NY</h2>
            <p>The Erie Canal would pass by seven miles to the north, the Seneca Turnpike seven miles south.
                Though the towns closest to these new thoroughfares benefitted the most, local farmers were
                able to bring their goods to bigger markets, assuring greater prosperity for the entire region.
                Eventually, a railroad line running from Rochester to Auburn was built, putting the nearby
                town of Phelps, then known as Vienna, on the map. It was during this period that its
                distinctive cobblestone and cut limestone buildings were built, many still in use today,
                including the town hall.</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>The Inn in the Mid 1900’s</h2>
            <p>By the 1940s, the Yorkshire Inn was home to a well-known restaurant, a spot so popular a new
                addition was built in 1954. It’s safe to say that anyone born before 1980 in this region had
                at least one meal there. The York Inn restaurant was run successfully until 1976 by James and
                Vivian Malone, a family still highly regarded in the area to this day.</p>
        </div>
        <div class="col">
            <img src="assets/img/1900s.png" alt="1900s" style="width:100%;">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <img src="assets/img/latch.png" alt="latch" style="width:100%;">
        </div>
        <div class="col">
            <h2>The Latch Family Era</h2>
            <p>The Latch family purchased the house in 2000 and began converting it into a Bed and Breakfast,
                opening with a single guest room in 2003, and has been operated as a successful B&B ever since.
                The next remodel resulted in the addition of The Blue Room and, in 2013, the old restaurant
                space was converted into the Lodge Suite, the Bolero room, the gathering room, and an
                additional laundry facility. This is the current state of the Inn today.</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>The Current Owners of the Inn</h2>
            <p>The Inn was purchased once again by Micah Sherman and Darcy DiPane in 2021 after relocating
                from Chicago to the Rochester area to be closer to family. Micah brings years of experience
                in the hospitality industry, having managed one of the top wedding venues in Windy City for
                over a decade. Darcy is a certified dietician with years of experience in the healthcare industry.
                Together, they are proud to become a part of the Inn\'s history and are dedicated to continuing
                the legacy of their predecessors.</p>
        </div>
        <div class="col">
            <img src="assets/img/owners.png" alt="owners" style="width:100%;">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <img src="assets/img/petsimg.png" alt="pets" style="width:100%;">
        </div>
        <div class="col">
            <h2>The Yorkshire Inn’s Mission</h2>
            <p>Your hosts will provide delicious, home-cooked breakfasts using locally-grown, farm-fresh
                ingredients,
                all prepared with a gourmet flair. Each of the four suites has its own distinctive decor
                and includes a private bath, flat-screen cable TV, and luxurious beds appointed with fine linens.
                Come join them as they write the next chapter in the ongoing saga of the Yorkshire Inn.
                They are committed to ensuring your visit will be a memorable one.</p>
        </div>
    </div>
</div>

<?php
// Used for formatting
include('footer.php');
?>
