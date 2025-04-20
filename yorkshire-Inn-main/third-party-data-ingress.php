<?php
/*
YORKSHIRE INN REDESIGN

Purpose: To provide an interface between The Yorkshire Inn's systems and a fictitious 
third party travel company. This API mimics an intermediary data processing unit
in order to prep data for local storage. Postman should be utilized to test data.


*/
require 'dbconfig.php';
$optCode = $_POST['option'];
if ($optCode == null) {
    // Send an error response
    http_response_code(400); // Bad Request
} else {
    /*
    Get the JSON Option Code
    1 = Booking
    2 = Cancellation
    3 = Request for Availability
    */
    if($optCode == 1) {
        
        //read in a bunch of data
        $checkIn = $_POST['checkIn'];
        $checkOut = $_POST['checkOut'];
        $numGuests = $_POST['numGuests'];
        $SpecialReq = $_POST['SpecialReq'];
        $TotalPayment = $_POST['TotalPayment'];
        $TotalDeposit = $_POST['TotalDeposit'];
        $RoomID =$_POST['RoomID'];
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $CreditCardNum = $_POST['CreditCardNum'];
        $ccv = $_POST['ccv'];
        $zip = $_POST['zip'];
        $expire = $_POST['expire'];

        $error_flag = 0;
        
        //first, see if room is administratively blocked

        //select the correct query depending on room choice
        switch($RoomID) {
            case 1:
                $checkQuery = "SELECT isBlockedBool1 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 2:
                $checkQuery = "SELECT isBlockedBool2 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 3: 
                $checkQuery = "SELECT isBlockedBool3 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 4: 
                $checkQuery = "SELECT isBlockedBool4 FROM isBlocked WHERE isBlockedDate BETWEEN '$checkIn' AND '$checkOut'";
                break;       
        }

        //get the result set from the database and format it as a JSON variable for comparison
        $query = yorkshireQuery($checkQuery);
        $data = array();
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        $jsonResult = json_encode($data, JSON_PRETTY_PRINT);

        //for each value - key pair in the result set, see if it contains 1/blocked variable
        //if it does, post an error message, submit an error to the error table, and end the process.
        //if it does not, continue until successful completion or failure state
        foreach($jsonResult as $pair) {
            $value = $pair['isBlockedBool' . $RoomID];
            if(strpos($value, '1') == true) {
                $error_msg = "[ERROR]: Double Booking Attempted. This room is blocked for this range of dates. Booking Unsuccessful.";
                $datetime = date("Y-m-d H:i:s");
                echo $datetime . " | " . $error_msg;
                yorkshireQuery("INSERT INTO error(timestamp, errorMessage) VALUES ('$datetime', '$error_msg')");
                http_response_code(400);
                die();
            } else {
                continue;
            }
        }

        //next, see if room is available

        //select the correct query depending on room choice
        $checkQuery = '';
        switch($RoomID) {
            case 1:
                $checkQuery = "SELECT isAvailableBool1 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 2:
                $checkQuery = "SELECT isAvailableBool2 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 3: 
                $checkQuery = "SELECT isAvailableBool3 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
                break;
            case 4: 
                $checkQuery = "SELECT isAvailableBool4 FROM isAvailable WHERE isAvailableDate BETWEEN '$checkIn' AND '$checkOut'";
                break;       
        }

        //get the result set from the database and format it as a JSON variable for comparison
        $query = yorkshireQuery($checkQuery);
        $data = array();
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        $jsonResult = json_encode($data, JSON_PRETTY_PRINT);

        //for each value - key pair in the result set, see if it contains 0/is not available variable
        //if it does, post an error message, submit an error to the error table, and end the process.
        //if it does not, continue until successful completion or failure state
        if(str_contains($jsonResult, '0')) {
            $error_msg = "[ERROR]: Double Booking Attempted. This room is not available for this range of dates. Booking Unsuccessful.";
            $datetime = date("Y-m-d H:i:s");
            echo $datetime . " | " . $error_msg;
            yorkshireQuery("INSERT INTO error(timestamp, errorMessage) VALUES ('$datetime', '$error_msg')");
            $error_flag = 1;
            http_response_code(400);
            die();
        } 
        
        //generate an insert time for the payment table
        $insertTime = date("Y-m-d H:i:s");

        //now, insert the data into their tables
        yorkshireQuery("INSERT INTO traveler(FirstName, LastName, Phone, Email) VALUES ('$FirstName', '$LastName', '$phone', '$email')");
        yorkshireQuery("INSERT INTO payment(PaymentDate, CreditCardNo, CCV, ZIPCode, ExpireDate) VALUES ('$insertTime', '$CreditCardNum', '$ccv', '$zip', '$expire')");
        
        //get traveler ID for reservation entry
        $query = yorkshireQuery("SELECT TravelerID FROM traveler WHERE FirstName LIKE '$FirstName'");
        $travIDArray = yorkshire_fetch_assoc($query);
        $travID = $travIDArray['TravelerID'];

        //get payment ID for reservation entry
        $query = yorkshireQuery("SELECT PaymentID FROM payment WHERE PaymentDate LIKE '$insertTime'");
        $payIDArray = yorkshire_fetch_assoc($query);
        $payID = $payIDArray['PaymentID'];

        //create reservation table entry
        yorkshireQuery("INSERT INTO reservation(BookingDate, CheckIn, CheckOut, NumGuests, SpecialReq, TotalPayment, TotalDeposit, RoomID, TravelerID, PaymentID) 
                        VALUES ('$insertTime', '$checkIn', '$checkOut', '$numGuests', '$SpecialReq', '$TotalPayment', '$TotalDeposit', '$RoomID', '$travID', '$payID')");

        //finally, block off the room

        //execute query to block off room in isAvailable table
        //determine based on room id
        switch($RoomID) {
            case 1:
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool1 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 2:
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool2 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 3:
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool3 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 4:
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool4 = 0 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;    
                break;
        }

        //get the reservation ID and send it back
        $resIDResult = yorkshireQuery("SELECT ReservationsID FROM reservation WHERE BookingDate LIKE '$insertTime'");
        $resIDAssoc = yorkshire_fetch_assoc($resIDResult);
        $resID = $resIDAssoc['ReservationsID'];
        echo($insertTime . ' | SUCCESS: Reservation successfully created. Reservation ID: ' . $resID);
        //send a good code
        http_response_code(200);

    } elseif ($optCode == 2) {
        //get the ID
        $resID = $_POST['resID'];

        //get reservation check in/out dates before deletion
        $checkInResult = yorkshireQuery("SELECT CheckIn FROM reservation WHERE ReservationsID = $resID");
        $checkOutResult = yorkshireQuery("SELECT CheckOut FROM reservation WHERE ReservationsID = $resID");
        $checkInAssoc = yorkshire_fetch_assoc($checkInResult);
        $checkOutAssoc = yorkshire_fetch_assoc($checkOutResult);
        $checkIn = $checkInAssoc['CheckIn'];
        $checkOut = $checkOutAssoc['CheckOut'];
        $roomIDResult = yorkshireQuery("SELECT RoomID FROM reservation WHERE ReservationsID = $resID");
        $roomIDAssoc = yorkshire_fetch_assoc($roomIDResult);
        $roomID = $roomIDAssoc['RoomID'];
        //delete the ID
        $result = yorkshireQuery("DELETE FROM reservation WHERE ReservationsID LIKE '$resID'");
        
        //free up the reservation table
        switch($roomID) {
            case 1:
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool1 = 1 WHERE isAvailableDate = $checkIn");
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool1 = 1 WHERE isAvailableDate = $checkOut");
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool1 = 1 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 2:
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool2 = 1 WHERE isAvailableDate = $checkIn");
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool2 = 1 WHERE isAvailableDate = $checkOut");
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool2 = 1 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 3:
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool3 = 1 WHERE isAvailableDate = $checkIn");
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool3 = 1 WHERE isAvailableDate = $checkOut");
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool3 = 1 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;
                break;
            case 4:
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool4 = 1 WHERE isAvailableDate = $checkIn");
                yorkshireQuery("UPDATE isAvailable SET isAvailableBool4 = 1 WHERE isAvailableDate = $checkOut");
                yorkshireQuery('UPDATE isAvailable SET isAvailableBool4 = 1 WHERE isAvailableDate BETWEEN \'' . $checkIn . '\' AND \'' . $checkOut . '\'');;    
                break;
        }
        
        //send a good code
        http_response_code(200);
        
    } elseif ($optCode == 3) {
        //get the date 
        $date = $_POST['date'];

        //get the results from the isAvailable table and post them as a JSON file
        $result1 = yorkshireQuery("SELECT * FROM isAvailable WHERE isAvailableDate LIKE '" . $date . "%'");
        $data = array();
        while ($row = mysqli_fetch_assoc($result1)) {
            $data[] = $row;
        }
        echo json_encode($data, JSON_PRETTY_PRINT);

        //get the results from the isBlocked table and post them as a JSON file
        $result2 = yorkshireQuery("SELECT * FROM isBlocked WHERE isBlockedDate LIKE '" . $date . "%'");
        $data = array();
        while ($row = mysqli_fetch_assoc($result2)) {
            $data[] = $row;
        }
        echo json_encode($data, JSON_PRETTY_PRINT);

        //send a good code
        http_response_code(200);
    } else {
        http_response_code(400); // Bad Request
    }
    
}
?>
