<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    


<div class="card shadow p-4">

<h1 class="text-primary">Admin Dashboard</h1>

<hr>

<div class="row">

<div class="col-md-4">
<div class="card bg-primary text-white p-3">
<h3>Rooms</h3>

<?php
$sql = "SELECT * FROM rooms";
$result = mysqli_query($conn, $sql);
echo mysqli_num_rows($result);
?>

</div>
</div>

<div class="col-md-4">
<div class="card bg-success text-white p-3">
<h3>Bookings</h3>

<?php
$sql2 = "SELECT * FROM bookings";
$result2 = mysqli_query($conn, $sql2);
echo mysqli_num_rows($result2);
?>

</div>
</div>

</div>

<hr>

<a href="../admin/managerooms.php" class="btn btn-primary">
Manage Rooms
</a>

<a href="viewbooking.php" class="btn btn-success">
View Bookings
</a>

<a href="../authentication/logout.php" class="btn btn-danger">
Logout
</a>

</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>