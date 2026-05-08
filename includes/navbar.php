<!-- NAVIGATION BAR -->
<!-- includes/navbar.php -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>
    
 
<nav class="navbar navbar-expand-lg navbar-dark shadow">

<div class="container">

    <!-- Logo / Brand -->
    <a class="navbar-brand fw-bold" href="index.php">

        <i class="bi bi-building"></i>
        Hostel Booking

    </a>

    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

        <span class="navbar-toggler-icon"></span>

    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarMenu">

        <ul class="navbar-nav ms-auto">

            <!-- Home -->
            <li class="nav-item">

                <a class="nav-link" href="../student/bookroom.php">

                    <i class="bi bi-house-door"></i> bookrooms</a>

            </li>

            <!-- Bookroom -->
           
            <!-- Rooms -->
            <li class="nav-item">

                <a class="nav-link" href="../student/rooms.php">

                    <i class="bi bi-box-arrow-in-right"></i>
                    rooms

                </a>

            </li>

            <?php if(isset($_SESSION['user_id'])){ ?>

            <!-- Dashboard -->
            <li class="nav-item">

                <a class="nav-link" href="../student/dashboard.php">

                    <i class="bi bi-speedometer2"></i>
                    Dashboard

                </a>

            </li>

            <!-- Logout -->
            <li class="nav-item">

                <a class="nav-link text-warning"
                   href="../authentication/logout.php">

                    <i class="bi bi-box-arrow-right"></i>
                    Logout

                </a>

            </li>

            <?php } ?>

        </ul>

    </div>

</div>

</nav>
</body>
 </html>
