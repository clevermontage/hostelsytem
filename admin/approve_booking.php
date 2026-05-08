<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if($_SESSION['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit();
}

$message = "";

if(isset($_GET['id']) && isset($_GET['action'])){
    $booking_id = intval($_GET['id']);
    $action = $_GET['action'];

    if($action == 'approve'){
        $sql = "UPDATE bookings SET status='approved', admin_action_date=NOW() WHERE id='$booking_id'";
        // Also update room status to booked
        $get_room = "SELECT room_id FROM bookings WHERE id='$booking_id'";
        $room_result = mysqli_query($conn, $get_room);
        $room_data = mysqli_fetch_assoc($room_result);
        if($room_data){
            mysqli_query($conn, "UPDATE rooms SET status='booked' WHERE id='" . $room_data['room_id'] . "'");
        }
        $message = "<div class='alert alert-success'>Booking approved successfully! Room status updated to booked.</div>";
    } elseif($action == 'decline'){
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
        if(empty($reason)){
            $message = "<div class='alert alert-warning'>Please provide a reason for declining.</div>";
        } else {
            $sql = "UPDATE bookings SET status='declined', admin_reason='$reason', admin_action_date=NOW() WHERE id='$booking_id'";
            $message = "<div class='alert alert-info'>Booking declined with reason provided.</div>";
        }
    }

    if(isset($sql) && mysqli_query($conn, $sql)){
        // Success
    } else {
        $message = "<div class='alert alert-danger'>Error updating booking: " . mysqli_error($conn) . "</div>";
    }
}

// Get booking details
if(isset($_GET['id'])){
    $booking_id = intval($_GET['id']);
    $sql = "SELECT bookings.*, users.fullname, users.email, rooms.room_number, rooms.room_type
            FROM bookings
            JOIN users ON bookings.user_id = users.id
            JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.id='$booking_id'";
    $result = mysqli_query($conn, $sql);
    $booking = mysqli_fetch_assoc($result);
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <h2 class="text-center text-primary mb-4">
                    <i class="bi bi-check-circle"></i>
                    Approve/Decline Booking
                </h2>

                <?php echo $message; ?>

                <?php if(isset($booking)){ ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5>Booking Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Student:</strong> <?php echo htmlspecialchars($booking['fullname']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                        <p><strong>Room:</strong> <?php echo $booking['room_number']; ?> (<?php echo $booking['room_type']; ?>)</p>
                        <p><strong>Booking Date:</strong> <?php echo date('d M Y, H:i', strtotime($booking['booking_date'])); ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?php echo ($booking['status'] == 'approved' ? 'success' : ($booking['status'] == 'declined' ? 'danger' : 'secondary')); ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </p>
                    </div>
                </div>

                <?php if($booking['status'] == 'pending'){ ?>
                <div class="row">
                    <div class="col-md-6">
                        <a href="?id=<?php echo $booking_id; ?>&action=approve" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i>
                            Approve Booking
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#declineModal">
                            <i class="bi bi-x-circle"></i>
                            Decline Booking
                        </button>
                    </div>
                </div>
                <?php } ?>

                <!-- Decline Modal -->
                <div class="modal fade" id="declineModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Decline Booking</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="?id=<?php echo $booking_id; ?>&action=decline">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for declining:</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Decline Booking</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php } else { ?>
                <div class="alert alert-warning">No booking found.</div>
                <?php } ?>

                <hr>
                <a href="viewbooking.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to All Bookings
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>