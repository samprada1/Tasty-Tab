<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id'])) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>User Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/img/food-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-section h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 15px 25px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .search-btn {
            background: #65BE9C;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: #54a987;
            transform: translateY(-2px);
        }

        .dashboard-content {
            padding: 40px 0;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background: #65BE9C;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        .order-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-delivered {
            background: #65BE9C;
            color: white;
        }

        .status-pending {
            background: #ffc107;
            color: white;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="hero-section">
        <div class="container">
            <h1>Online Restaurants</h1>
            <p class="lead">Top restaurants and specials in town</p>
            <div class="search-container">
                <form class="search-form" action="restaurants.php" method="GET">
                    <input type="text" class="search-input" name="search" placeholder="Enter Search...">
                    <button type="submit" class="search-btn">Search food</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container dashboard-content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Recent Orders
                    </div>
                    <div class="card-body">
                        <?php
                        $user_id = $_SESSION["user_id"];
                        $query = mysqli_query($db, "SELECT * FROM users_orders WHERE u_id='$user_id' ORDER BY date DESC LIMIT 5");
                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_array($query)) {
                        ?>
                        <div class="order-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1"><?php echo $row['title']; ?></h5>
                                    <p class="mb-1">Order ID: <?php echo $row['o_id']; ?></p>
                                    <small>Date: <?php echo $row['date']; ?></small>
                                </div>
                                <span class="status-badge <?php echo ($row['status'] == 'delivered') ? 'status-delivered' : 'status-pending'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<p class='text-center'>No orders found</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Profile Information
                    </div>
                    <div class="card-body">
                        <h5>Welcome, <?php echo $_SESSION["username"]; ?>!</h5>
                        <p>Manage your orders and explore new restaurants.</p>
                        <a href="restaurants.php" class="btn btn-success btn-block">Browse Restaurants</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html>
