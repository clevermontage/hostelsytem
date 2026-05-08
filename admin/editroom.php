<?php
session_start();
include '../config/db.php';

if($_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM rooms WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$room = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $update = "UPDATE rooms SET 
                room_number='$room_number',
                room_type='$room_type',
                price='$price',
                status='$status'
                WHERE id='$id'";

    mysqli_query($conn, $update);

    header("Location: .manage_rooms.php");
}
?>

<h2>Edit Room</h2>

<form method="POST">

<input type="text" name="room_number" value="<?php echo $room['room_number']; ?>"><br><br>

<input type="text" name="room_type" value="<?php echo $room['room_type']; ?>"><br><br>

<input type="number" name="price" value="<?php echo $room['price']; ?>"><br><br>

<select name="status">
    <option value="available">Available</option>
    <option value="booked">Booked</option>
</select><br><br>

<button type="submit" name="update">Update</button>

</form>