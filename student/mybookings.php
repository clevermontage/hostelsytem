<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT bookings.*, rooms.room_number, rooms.room_type, rooms.price
        FROM bookings
        JOIN rooms ON bookings.room_id = rooms.id
        WHERE bookings.user_id='$user_id'
        ORDER BY bookings.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="card shadow p-4">

<h2>My Bookings</h2>

<table class="table table-bordered">

<tr>
<th>Room</th>
<th>Type</th>
<th>Price</th>
<th>Date</th>
<th>Status</th>
<th>Admin Note</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['room_type']; ?></td>
                    <td><?php echo number_format($row['price']); ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $badge_class = 'secondary';
                        $status_text = 'Pending Approval';
                        if($status == 'approved') {
                            $badge_class = 'success';
                            $status_text = 'Approved';
                        } elseif($status == 'declined') {
                            $badge_class = 'danger';
                            $status_text = 'Declined';
                        }
                        ?>
                        <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                    </td>
                    <td>
                        <?php if($status == 'declined' && !empty($row['admin_reason'])){ ?>
                        <small class="text-danger"><?php echo htmlspecialchars($row['admin_reason']); ?></small>
                        <?php } ?>
                    </td>
                </tr>
<?php } ?>

</table>

</div>

<?php include '../includes/footer.php'; ?>