<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="restaurants.php">Restaurants</a>
    </li>
    <li class="nav-item">
        <form class="form-inline" action="search.php" method="GET">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search food..." style="width: 200px;">
                <div class="input-group-append">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </li>
    <?php
    if(empty($_SESSION["user_id"])) {
        echo '<li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>';
        echo '<li class="nav-item"><a href="registration.php" class="nav-link">Register</a></li>';
    } else {
        echo '<li class="nav-item"><a href="your_orders.php" class="nav-link">My Orders</a></li>';
        echo '<li class="nav-item"><a href="table_reservation.php" class="nav-link">Table Reservation</a></li>';
        echo '<li class="nav-item"><a href="user_details.php" class="nav-link">My Profile</a></li>';
        echo '<li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>';
    }
    ?>
</ul> 