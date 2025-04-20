

<?php
session_start();

require_once('dbconfig.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form input
    if (isset($_POST['username']) && isset($_POST['current']) && isset($_POST['new']) && isset($_POST['confirm'])){
        $username = $_POST['username'];
        $current_password = $_POST['current'];
        $new_password = $_POST['new'];
        $confirm_password = $_POST['confirm'];

        // Check if new password is empty
        if (empty($new_password)) {
            $_SESSION['error']= "New password cannot be empty";
            header('Location: password.php');
            exit();
        }
            // Check if the new password and confirmed password match
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'New password and confirmed password do not match';
            header('Location: password.php');
            exit();

        } else {
            // Check if the current password is correct
            $stmt = $conn->prepare('SELECT passwords FROM login WHERE UserName = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {
                $_SESSION['error'] = 'Incorrect current password';
                header('Location: password.php');
                exit();
            }

            $row = $result->fetch_assoc();
            $hashed_password = $row['passwords'];

            if (password_verify($current_password, $hashed_password)) {
                // Update the user's password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE login SET passwords = ? WHERE UserName = ?');
                $stmt->bind_param('ss', $hashed_new_password, $username);
                $stmt->execute();
                // Redirect to the success page
                header('Location: adminmenu.php');
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect current password';
                header('Location: password.php');
                exit();
            }
    
        }
    }
        
}
?>





