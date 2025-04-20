<?php
/*
YORKSHIRE INN REDESIGN

Purpose: To send an email utilizing PHP emailing methods.


*/
?>
<?php
ini_set('smtp_port', 587);
// Get the form data and sanitize it
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// Set the recipient email address
$to = 'djk5634@rit.edu';

// Set the email subject
$subject = 'New message from ' . $name;

// Set the email message
$body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

// Set the email headers
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// Send the email
if (mail($to, $subject, $body, $headers)) {
  // Redirect back to the form page with success message
  header('Location: contactus.php?status=success');
} else {
  // Redirect back to the form page with error message
  header('Location: contactus.php?status=error');
}
?>
