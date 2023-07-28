<?php
/*
YORKSHIRE INN REDESIGN
ROCHESTER INSTITUTE OF TECHNOLOGY
ISTE 501 - Senior Design and Development
----------------------------------------
Author: Everett Simone
File: logout.php
Version: 1.0
Release Notes: Initial Release.
----------------------------------------
Purpose: Logs out user by stripping session data from the browser.

ALL RIGHTS RESERVED. 
*/
?>

<?php
//remove all session data to force a login again
session_start();
session_destroy();
header("Location: login.php");
exit();
?>