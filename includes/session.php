<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: /HostelSystem/authentication/login.php");
    exit();
}

?>