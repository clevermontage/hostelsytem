<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$error = "";
$success = "";

if(isset($_POST['add'])){

    $room_number = trim($_POST['room_number']);
    $room_type = trim($_POST['room_type']);
    $price = trim($_POST['price']);

    if(empty($room_number) || empty($room_type) || empty($price)){

        $error = "All fields are required";

    } else {

        $check = "SELECT * FROM rooms
                  WHERE room_number='$room_number'";

        $check_result = mysqli_query($conn, $check);

        if(mysqli_num_rows($check_result) > 0){

            $error = "Room already exists";

        } else {

            $sql = "INSERT INTO rooms(room_number, room_type, price, status)
                    VALUES('$room_number','$room_type','$price','available')";

            mysqli_query($conn, $sql);

            $success = "Room added successfully";
        }
    }
}
?>

<div class="card shadow p-4">

<h2>Add Room</h2>

<?php if($error != ""){ ?>
<div class="alert alert-danger">
<?php echo $error; ?>
</div>
<?php } ?>

<?php if($success != ""){ ?>
<div class="alert alert-success">
<?php echo $success; ?>
</div>
<?php } ?>

<form method="POST">

<label>Room Number</label>
<input type="text"
name="room_number"
class="form-control">

<br>

<label>Room Type</label>
<input type="text"
name="room_type"
class="form-control">

<br>

<label>Price</label>
<input type="number"
name="price"
class="form-control">

<br>

<button type="submit"
name="add"
class="btn btn-primary">
Add Room
</button>

</form>

</div>

<?php include '../includes/footer.php'; ?>