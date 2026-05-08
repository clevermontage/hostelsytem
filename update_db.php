<?php
include 'config/db.php';

$sql1 = "ALTER TABLE bookings ADD COLUMN status ENUM('pending', 'approved', 'declined') DEFAULT 'pending'";
$sql2 = "ALTER TABLE bookings ADD COLUMN admin_reason TEXT NULL";
$sql3 = "ALTER TABLE bookings ADD COLUMN admin_action_date DATETIME NULL";

if(mysqli_query($conn, $sql1)){
    echo "Status column added successfully.<br>";
} else {
    echo "Error adding status column: " . mysqli_error($conn) . "<br>";
}

if(mysqli_query($conn, $sql2)){
    echo "Admin reason column added successfully.<br>";
} else {
    echo "Error adding admin reason column: " . mysqli_error($conn) . "<br>";
}

if(mysqli_query($conn, $sql3)){
    echo "Admin action date column added successfully.<br>";
} else {
    echo "Error adding admin action date column: " . mysqli_error($conn) . "<br>";
}

echo "Database update complete!";
?>