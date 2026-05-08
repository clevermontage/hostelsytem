<?php
session_start();
include '../config/db.php';

if($_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $sql = "DELETE FROM rooms WHERE id='$id'";

    if(mysqli_query($conn, $sql)){
        header("Location: managerooms.php?message=" . urlencode('Room deleted successfully!'));
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: managerooms.php");
    exit();
}
?>