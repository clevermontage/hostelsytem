<?php

// =====================================
// STUDENT ROOMS PAGE
// =====================================

include '../includes/session.php';
include '../config/db.php';

include '../includes/header.php';
include '../includes/navbar.php';

$message = "";

// =====================================
// SEARCH ROOM
// =====================================

$search = "";

if(isset($_GET['search'])){

    $search = $_GET['search'];

    $sql = "SELECT * FROM rooms
            WHERE status='available'
            AND room_type LIKE '%$search%'";

} else {

    $sql = "SELECT * FROM rooms
            WHERE status='available'";
}

$result = mysqli_query($conn, $sql);

?>

<div class="container mt-5">

<!-- PAGE TITLE -->

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="text-primary fw-bold">

<i class="bi bi-building"></i>
Available Rooms

</h2>

<a href="dashboard.php"
class="btn btn-dark">

<i class="bi bi-speedometer2"></i>
Dashboard

</a>

</div>

<!-- SEARCH FORM -->

<div class="card shadow border-0 p-4 mb-4">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input type="text"
name="search"
class="form-control"
placeholder="Search by room type">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

<i class="bi bi-search"></i>
Search

</button>

</div>

</div>

</form>

</div>

<!-- ROOM DISPLAY -->

<div class="row">

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<div class="col-md-4 mb-4">

<div class="card shadow-lg border-0 h-100">

<!-- ROOM IMAGE -->
<?php
$room_number = $row['id'];
$image_index = ($room_number - 1) % 9 + 1; // Cycle through 9 images
$image_path = "../assets/images/A1 ({$image_index}).jpeg";
?>
<img src="<?php echo $image_path; ?>"
class="card-img-top"
height="220"
style="object-fit:cover;"
alt="Room <?php echo $row['room_number']; ?>">

<div class="card-body">

<h4 class="text-primary">

Room <?= $row['room_number']; ?>

</h4>

<hr>

<p>

<strong>Room Type:</strong>
<?= $row['room_type']; ?>

</p>

<p>

<strong>Price:</strong>
UGX <?= number_format($row['price']); ?>

</p>

<p>

<span class="badge bg-success">

Available

</span>

</p>

</div>

<div class="card-footer bg-white border-0">

<a href="bookroom.php?id=<?= $row['id']; ?>"
class="btn btn-success w-100">

<i class="bi bi-check-circle"></i>
Book Room

</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

<?php include '../includes/footer.php'; ?>