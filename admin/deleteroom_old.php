<?php
session_start();
include '../config/db.php';

if($_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $action = isset($_GET['action']) ? $_GET['action'] : 'delete';

    if($action == 'deactivate'){
        $sql = "UPDATE rooms SET status='deactivated' WHERE id='$id'";
        $message = "Room deactivated successfully!";
    } elseif($action == 'activate'){
        $sql = "UPDATE rooms SET status='available' WHERE id='$id'";
        $message = "Room activated successfully!";
    } elseif($action == 'delete'){
        $sql = "DELETE FROM rooms WHERE id='$id'";
        $message = "Room deleted successfully!";
    }

    if(mysqli_query($conn, $sql)){
        header("Location: managerooms.php?message=" . urlencode($message));
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: managerooms.php");
    exit();
}
?>