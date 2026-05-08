<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}


$search = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];

    $sql = "SELECT * FROM rooms
            WHERE room_number LIKE '%$search%'
            OR room_type LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM rooms";
}

$result = mysqli_query($conn, $sql);
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

<h2 class="text-primary">Manage Rooms</h2>

<a href="add_room.php" class="btn btn-success mb-3">
Add Room
</a>

<a href="dashboard.php" class="btn btn-secondary mb-3">
Dashboard
</a>

<form method="GET" class="mb-3">

<input type="text"
name="search"
class="form-control"
placeholder="Search room">

<br>

<button class="btn btn-primary">
Search
</button>

</form>

<table class="table table-bordered table-striped">

<tr>
<th>ID</th>
<th>Room</th>
<th>Type</th>
<th>Price</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['room_number']; ?></td>

<td><?php echo $row['room_type']; ?></td>

<td><?php echo $row['price']; ?></td>

<td>

<?php
if($row['status'] == 'available'){
    echo "<span class='badge bg-success'>Available</span>";
} else {
    echo "<span class='badge bg-danger'>Booked</span>";
}
?>

</td>

<td>

<a href="editroom.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">
Edit
</a>

<a href="deleteroom.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this room?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>
</body>
</html>


<?php include '../includes/footer.php'; ?>