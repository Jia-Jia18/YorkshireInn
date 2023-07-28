<?php
include('header.php');
echo '<!-- title over image--->
<div class="container">
    <img src="assets/img/contactus.png" alt="contactus" style="width:100%;">
    <div class="right">
        <h1>Have a Question</h1>
        <p>Reach out to us! We are available to answer your questions weekdays 8am - 9pm 
            and weekends 8am - 7pm.</p>
    </div>
</div>

<!-- Check availability form--->
<div class="center">
    <form class="form-inline" action="/action_page.php">
        <label for="checkin">Check In:</label>
        <input type="date" id="checkin" name="checkin">
        <label for="checkout">Check Out:</label>
        <input type="date" id="checkout" name="checkout">
        <input type="number" id="guestnum" name="guestnum" min="1" placeholder="Guest Num">
        <button type="submit">Search</button>
    </form>
</div>

<div class="googlemap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2920.2868592196246!2d-77.0381250487053!3d42.951158979049296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89d0d1c750b4eefd%3A0xe3f4212639fd53b2!2sThe%20Yorkshire%20Inn!5e0!3m2!1sen!2sus!4v1675183911072!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>


<!-- Grid & content-->
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
            <form class="formstack" action="/action_page.php">
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
                <button type="submit">SUBMIT</button>
            </form>
        </div>
    </div>
</div>';
include('footer.php');
?>