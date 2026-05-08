<?php
include '../config/db.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_admin'])){
    
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if(empty($fullname) || empty($email) || empty($password) || empty($confirm_password)){
        $message = "<div class='alert alert-danger'>All fields are required</div>";
    } else if($password != $confirm_password){
        $message = "<div class='alert alert-danger'>Passwords do not match</div>";
    } else if(strlen($password) < 6){
        $message = "<div class='alert alert-danger'>Password must be at least 6 characters</div>";
    } else {
        
        // Check if email already exists
        $check_sql = "SELECT * FROM users WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0){
            $message = "<div class='alert alert-danger'>Email already exists</div>";
        } else {
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Ensure email contains 'admin' for admin role
            if(strpos($email, 'admin') === false){
                $email = 'admin' . time() . '@hostel.com';
            }
            
            $sql = "INSERT INTO users(fullname, email, password, role)
                    VALUES('$fullname', '$email', '$hashed_password', 'admin')";
            
            if(mysqli_query($conn, $sql)){
                $message = "<div class='alert alert-success'>Admin account created successfully!<br>Email: $email<br>You can now login with these credentials.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error creating admin: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center text-primary mb-4">Create Admin Account</h2>
                
                <?= $message; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email (must contain 'admin')</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="e.g., admin@hostel.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="create_admin" class="btn btn-primary w-100">Create Admin</button>
                </form>
                
                <hr>
                <p class="text-center mt-3">
                    <a href="../authentication/login.php">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
