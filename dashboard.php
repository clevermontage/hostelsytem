<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: authentication/login.php");
    exit();
}

if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
    header("Location: admin/dashboard.php");
    exit();
}

header("Location: student/dashboard.php");
exit();
