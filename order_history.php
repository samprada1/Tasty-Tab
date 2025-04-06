<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id']))  
{
    header('location:login.php');
}
else
{
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Order History</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .navbar-brand img {
            max-height: 45px !important;
            width: auto;
        }
        .header-scroll {
            padding: 5px 0;
        }
        .page-wrapper {
            padding-top: 80px;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background: #404040;
            color: white;
        }
        .order-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
    </style>
</head>

<body>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Logot.png" alt=""> </a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"> <a class="nav-link active" href="index.php">Home</a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants</a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="your_orders.php">My Orders</a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="order_history.php">Order History</a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="logout.php">Logout</a> </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="text-center mb-4">Order History</h2>
                    <?php 
                    $query_history = mysqli_query($db,"select * from users_orders where u_id='".$_SESSION['user_id']."' AND status = 'closed' ORDER BY date DESC");
                    if(!mysqli_num_rows($query_history) > 0) {
                        echo '<div class="alert alert-info">No order history available.</div>';
                    } else {
                        while($row = mysqli_fetch_array($query_history)) {
                    ?>
                        <div class="order-card">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Order ID:</strong> #<?php echo $row['o_id']; ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Item:</strong> <?php echo $row['title']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Quantity:</strong> <?php echo $row['quantity']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Price:</strong> $<?php echo $row['price']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Date:</strong> <?php echo $row['date']; ?>
                                </div>
                            </div>
                        </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="bottom-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-3 payment-options color-gray">
                    <h5>Payment Option</h5>
                    <ul>
                        <li>
                            <a href="#"> <img src="images/paypal.png" alt="Paypal"> </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-4 address color-gray">
                    <h5>Address</h5>
                    <p>Kathmandu, Nepal</p>
                    <h5>Phone: 9800000000</h5>
                </div>
                <div class="col-xs-12 col-sm-5 additional-info color-gray">
                    <h5>Addition informations</h5>
                    <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                </div>
            </div>
        </div>
    </footer>

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
<?php
}
?> 