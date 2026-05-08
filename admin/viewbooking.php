<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if($_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}

// Handle approve/decline actions
if(isset($_POST['approve_booking'])){
    $booking_id = intval($_POST['booking_id']);
    mysqli_query($conn, "UPDATE bookings SET status='approved' WHERE id='$booking_id'");
    // Also update room status to booked
    $room_query = mysqli_query($conn, "SELECT room_id FROM bookings WHERE id='$booking_id'");
    if($room_row = mysqli_fetch_assoc($room_query)){
        mysqli_query($conn, "UPDATE rooms SET status='booked' WHERE id='{$room_row['room_id']}'");
    }
    header("Location: viewbooking.php?msg=approved");
    exit();
}

if(isset($_POST['decline_booking'])){
    $booking_id = intval($_POST['booking_id']);
    $reason = trim($_POST['decline_reason']);
    mysqli_query($conn, "UPDATE bookings SET status='declined', admin_reason='$reason' WHERE id='$booking_id'");
    header("Location: viewbooking.php?msg=declined");
    exit();
}

$sql = "SELECT bookings.*, users.fullname, users.email, rooms.room_number, rooms.room_type, rooms.price
        FROM bookings
        JOIN users ON bookings.user_id = users.id
        JOIN rooms ON bookings.room_id = rooms.id
        ORDER BY bookings.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">

<?php if(isset($_GET['msg'])){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i>
        <?php if($_GET['msg'] == 'approved') echo 'Booking approved successfully!'; ?>
        <?php if($_GET['msg'] == 'declined') echo 'Booking declined and student notified.'; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold">
        <i class="bi bi-calendar-check"></i>
        Manage Student Bookings
    </h2>
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Back to Dashboard
    </a>
</div>

<?php if(mysqli_num_rows($result) > 0){ ?>

<div class="card shadow border-0">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Price (UGX)</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><strong><?php echo $row['id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['room_type']; ?></td>
                    <td><?php echo number_format($row['price']); ?></td>
                    <td><?php echo date('d M Y, H:i', strtotime($row['booking_date'])); ?></td>                    <td>
                        <?php
                        $status = $row['status'];
                        $badge_class = 'secondary';
                        $status_text = 'Pending';
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
                        <?php if($status == 'pending'){ ?>
                        <a href="approve_booking.php?id=<?php echo $row['id']; ?>&action=approve" class="btn btn-success btn-sm">Approve</a>
                        <a href="approve_booking.php?id=<?php echo $row['id']; ?>&action=decline" class="btn btn-danger btn-sm">Decline</a>
                        <?php } else { ?>
                        <span class="text-muted">Action taken</span>
                        <?php } ?>
                    </td>                    <td>
                        <?php
                        $status = $row['status'] ?? 'pending';
                        if($status == 'approved'){
                            echo '<span class="badge bg-success">Approved</span>';
                        } elseif($status == 'declined'){
                            echo '<span class="badge bg-danger">Declined</span>';
                        } else {
                            echo '<span class="badge bg-warning">Pending</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if(($row['status'] ?? 'pending') == 'pending'){ ?>
                        <div class="btn-group" role="group">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="approve_booking" class="btn btn-success btn-sm">
                                    <i class="bi bi-check"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#declineModal<?php echo $row['id']; ?>">
                                <i class="bi bi-x"></i> Decline
                            </button>
                        </div>

                        <!-- Decline Modal -->
                        <div class="modal fade" id="declineModal<?php echo $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Decline Booking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="decline_reason" class="form-label">Reason for declining:</label>
                                                <textarea name="decline_reason" id="decline_reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="decline_booking" class="btn btn-danger">Decline Booking</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <span class="text-muted">Processed</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php } else { ?>

<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle"></i>
    No bookings yet.
</div>

<?php } ?>

</div>

<?php include '../includes/footer.php'; ?>