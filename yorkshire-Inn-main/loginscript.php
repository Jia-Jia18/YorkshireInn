<?php
/*
YORKSHIRE INN REDESIGN

Purpose: Handles the single sign on validation between the database and the admin portal


*/
?>

<?php
session_start();
require_once('dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        //prepare and gather results from SQL statement
        $stmt = $conn->prepare('SELECT passwords FROM login WHERE UserName = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        //if login info exists and matches password, allow login. otherwise return user to login page to try again
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['passwords'])) {
                // Successful login
                $_SESSION['user'] = $username;
                header('Location: adminmenu.php');
                exit();
            } else {
                // Failed login
                $_SESSION['error'] = "Invalid password";
                header('Location: login.php');
                exit();
            }
        } else {
            // Failed login
            $_SESSION['error'] = "Invalid username";
            header('Location: login.php');
            exit();
        }
    }
}
?>
