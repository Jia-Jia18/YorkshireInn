<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Logs out user by stripping session data from the browser.


*/
?>

<?php
//remove all session data to force a login again
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
