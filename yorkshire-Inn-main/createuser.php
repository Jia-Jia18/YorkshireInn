<?php
/*
YORKSHIRE INN REDESIGN
ROCHESTER INSTITUTE OF TECHNOLOGY
ISTE 501 - Senior Design and Development
----------------------------------------
Author: Everett Simone 
File: createuser.php
Version: 1.0
Release Notes: Initial Release.
----------------------------------------
Purpose: Backend creation of admin user for the login table in the database

ALL RIGHTS RESERVED. 
*/
?>

<?php
session_start();
require_once('dbconfig.php');
//Login enforcement
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//User creation, takes in form data and creates a user in the login table.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST["usernamecreate"];
    $password = $_POST["passwordcreate"];

    
    if (empty($username) || empty($password)) {
        echo "Username and password are required";
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO login (UserName, passwords) VALUES ('$username', '$hashed_password')";

    
    if (mysqli_query($conn, $sql)) {
        echo "<p>User created successfully</p>";
        echo "<a href='./adminmenu.php'>Click here to go back to the menu</a>";
    } else {
        echo "Error creating user: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
?>