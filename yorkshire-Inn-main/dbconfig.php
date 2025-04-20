<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Universal file to declare connection constants, data input/output methods

*/
?>

<?php
//constants for connection to database
// servername = database URL (from AWS)
// dbuser = AWS RDC database user
// dbpass = AWS RDC database password
// dbname = schema to be used (must be yorkshireInn)

$servername = "yorkshireinn-db1.cf8hdk0a7aiu.us-east-1.rds.amazonaws.com:3306";
$dbuser = "admin";
$dbpass = "yiBgQMeYUAuF37";
$dbname = "yorkshireInn";

//connect to the DB, handle errors
$conn = mysqli_connect($servername, $dbuser, $dbpass, $dbname);
if (!$conn)
{
    switch (mysqli_errno())
    {
        case 1040: die("We cannot process request at this time, please wait and try again in a few minutes"); //Too Many Connections
        case 2002: die("Sorry we are having problems with the database at this time, please try back later"); //Misc could be permission problems
        default: die("There was a problem during the MySQL connection, please try again and report this error should it persist");
    }
}

//function to send query to DB and return its result
function yorkshireQuery($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    return $result;
}

//function to mimic mysqli_fetch_assoc
function yorkshire_fetch_assoc($result) {
    $row = $result->fetch_assoc();
    return $row;
}
?>
