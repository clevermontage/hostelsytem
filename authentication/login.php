<?php

session_start();

include '../config/db.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){

        $message = "<div class='alert alert-danger'>
                    All Fields Are Required
                    </div>";

    }else{

        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result) > 0){

            $user = mysqli_fetch_assoc($result);
            if(password_verify($password,$user['password'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];

                if($_SESSION['role'] === 'admin'){

                    header("Location: ../admin/dashboard.php");

                }else{

                    header("Location: ../student/dashboard.php");
                }

            }else{

                $message = "<div class='alert alert-danger'>
                            Incorrect Password
                            </div>";
            }

        }else{

            $message = "<div class='alert alert-danger'>
                        User Not Found
                        </div>";
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

<div class="col-md-5">

<div class="card shadow p-4">

<h2 class="text-center text-success">
Login
</h2>

<?= $message; ?>

<form method="POST">

<label>Email</label>

<input type="email" name="email" class="form-control">

<br>

<label>Password</label>

<input type="password" name="password" class="form-control">

<br>

<button type="submit"
name="login"class="btn btn-success w-100"> Login</button>

</form>
<p class="mt-3">
    Dont have an account? <a href="register.php">Register Here</a>

</div>

</div>

</div>

</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>