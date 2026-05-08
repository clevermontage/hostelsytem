<?php
include '../includes/session.php';
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($room_id <= 0){
    echo "Invalid room ID.";
    exit();
}

$check = "SELECT * FROM bookings
          WHERE room_id='$room_id' AND status IN ('pending', 'approved')";

$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0){

    echo "<div class='alert alert-warning'>Room Already Booked or Pending Approval</div>";

} else {

    $sql = "INSERT INTO bookings(user_id, room_id, booking_date, status)
            VALUES('$user_id','$room_id', NOW(), 'pending')";

    if(mysqli_query($conn, $sql)){

        echo "Booking Request Submitted Successfully. Waiting for admin approval.";

    } else {

        echo "Booking Failed";
    }
}
?>