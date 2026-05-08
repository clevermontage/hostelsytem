<?php

include '../config/db.php';

$message = "";

if(isset($_POST['register'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($fullname) || empty($email) || empty($password)){

        $message = "<div class='alert alert-danger'>
                    All Fields Are Required
                    </div>";

    }else{

        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn,$check);

        if(mysqli_num_rows($result) > 0){

            $message = "<div class='alert alert-warning'>
                        Email Already Exists
                        </div>";

        }else{

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Determine role: if email contains 'admin', set as admin, else student
            $role = (strpos($email, 'admin') !== false) ? 'admin' : 'student';

            $sql = "INSERT INTO users(fullname,email,password,role)
                    VALUES('$fullname','$email',
                    '$hashed_password','$role')";

            if(mysqli_query($conn,$sql)){

                $message = "<div class='alert alert-success'>
                            Registration Successful. Your role is set to: " . ucfirst($role) . "
                            </div>";
            }
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow p-4">

<h2 class="text-center text-primary">
Student Registration
</h2>

<?= $message; ?>

<form method="POST">

<label>Full Name</label>

<input type="text"
name="fullname"
class="form-control">

<br>

<label>Email</label>

<input type="email"
name="email"
class="form-control">

<br>

<label>Password</label>

<input type="password"
name="password"
class="form-control">

<br>

<button type="submit"
name="register"
class="btn btn-primary w-100">

Register

</button>

</form>
<p>
    Already have an account? <a href="login.php">Login Here</a>

</div>

</div>

</div>

</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>