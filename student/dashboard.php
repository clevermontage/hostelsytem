<?php
include_once'../includes/session.php';
include_once'../config/db.php';
include_once '../includes/header.php';
include_once '../includes/navbar.php';

$user_id = $_SESSION['user_id'];

// Get student's current bookings
$booking_sql = "SELECT bookings.*, rooms.room_number, rooms.room_type, rooms.price
                FROM bookings
                JOIN rooms ON bookings.room_id = rooms.id
                WHERE bookings.user_id='$user_id'
                ORDER BY bookings.booking_date DESC";
$booking_result = mysqli_query($conn, $booking_sql);
?>

<div class="container mt-5">

    <div class="card shadow p-4 mb-4">

        <h2 class="text-primary">Student Dashboard</h2>

        <p>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>.</p>

        <div class="row g-3">

            <div class="col-md-6">
                <a href="rooms.php" class="btn btn-primary w-100">
                    <i class="bi bi-building"></i>
                    View Available Rooms
                </a>
            </div>

            <div class="col-md-6">
                <a href="mybookings.php" class="btn btn-success w-100">
                    <i class="bi bi-bookmarks"></i>
                    My Bookings
                </a>
            </div>

        </div>

    </div>

    <!-- Current Bookings Section -->
    <div class="card shadow p-4">
        <h3 class="text-success mb-4">
            <i class="bi bi-calendar-check"></i>
            Your Current Bookings
        </h3>

        <?php if(mysqli_num_rows($booking_result) > 0){ ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Price (UGX)</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($booking_result)){ ?>
                    <tr>
                        <td><strong><?php echo $row['room_number']; ?></strong></td>
                        <td><?php echo $row['room_type']; ?></td>
                        <td><?php echo number_format($row['price']); ?></td>
                        <td><?php echo date('d M Y, H:i', strtotime($row['booking_date'])); ?></td>                        <td>
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
                            <button class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($row['admin_reason']); ?>">
                                <i class="bi bi-info-circle"></i> Reason
                            </button>
                            <?php } ?>
                        </td>                        <td>
                            <?php
                            $status = $row['status'] ?? 'pending';
                            if($status == 'approved'){
                                echo '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>';
                            } elseif($status == 'declined'){
                                echo '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Declined</span>';
                                if(!empty($row['admin_reason'])){
                                    echo '<br><small class="text-muted">Reason: ' . htmlspecialchars($row['admin_reason']) . '</small>';
                                }
                            } else {
                                echo '<span class="badge bg-warning"><i class="bi bi-clock"></i> Pending Approval</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php } else { ?>

        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i>
            You have no current bookings. <a href="rooms.php">Book a room now!</a>
        </div>

        <?php } ?>

    </div>

</div>

<?php include '../includes/footer.php'; ?>