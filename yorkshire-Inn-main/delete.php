<?php
/*
YORKSHIRE INN REDESIGN

Purpose: The page will process delete reservation and update isAvailable in the database.


*/
?>

<?php
    session_start();
	require_once('dbconfig.php');
    if(isset($_POST['submit'])){
        if(!empty($_POST['id'])) {    
            $query = "DELETE FROM reservation WHERE ReservationsID =";
                
            for ($i=0; $i<count($_POST['id']); $i++){
                $reservationsID = $_POST['id'][$i];

                //Receive info
                $fetchQuery = "SELECT RoomID, CheckIn, CheckOut FROM reservation WHERE ReservationsID = ?";
                $stmt = $conn->prepare($fetchQuery);
                $stmt->bind_param("i", $reservationsID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $roomID = $row['RoomID'];
                $checkIn = $row['CheckIn'];
                $checkOut = $row['CheckOut'];
                
                //Delete the reservation
                $deleteQuery = "DELETE FROM reservation WHERE ReservationsID = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $reservationsID);
                $stmt->execute();
                
                //Update isAvailable
                $currentDate = new DateTime($checkIn);
                $checkOutDate = new DateTime($checkOut);

                while ($currentDate <= $checkOutDate) {
                    $currentDateString = $currentDate->format("Y-m-d");

                    $updateQuery = "UPDATE isAvailable SET isAvailableBool" . $roomID . " = 1 WHERE isAvailableDate = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("s", $currentDateString);
                    $stmt->execute();

                    $currentDate->modify("+1 day");
                }
                   
                }
                
            header('Location: deletebooking.php');      
        }else{
            header('Location: deletebooking.php'); 
        }
    }
    $conn->close();

    
                
?>
