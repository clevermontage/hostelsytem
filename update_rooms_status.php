<?php
include 'config/db.php';

$sql = "ALTER TABLE rooms MODIFY COLUMN status ENUM('available', 'booked', 'deactivated') DEFAULT 'available'";

if(mysqli_query($conn, $sql)){
    echo "Rooms status updated successfully. Deactivated status added.";
} else {
    echo "Error updating rooms status: " . mysqli_error($conn);
}
?>