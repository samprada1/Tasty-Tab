<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");  
error_reporting(0);  
session_start();

if(empty($_SESSION["user_id"])) {
    header('location:login.php');
}

// Fetch user's reservations
$user_id = $_SESSION["user_id"];
$reservations_query = "SELECT tr.*, r.title as restaurant_name 
                      FROM table_reservations tr 
                      JOIN restaurant r ON tr.restaurant_id = r.rs_id 
                      WHERE tr.user_id = $user_id 
                      ORDER BY tr.reservation_date DESC, tr.reservation_time DESC";
$reservations_result = mysqli_query($db, $reservations_query);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 40px 20px;
            box-sizing: border-box;
        }
        .navbar {
            background-color: #1a1a1a !important;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar-brand img {
            max-height: 50px;
            width: auto;
        }
        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #65BE9C !important;
        }
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
            padding: 0.25rem 0.5rem;
        }
        .reservation-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .reservation-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }
        .reservation-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #333;
        }
        .btn-add-reservation {
            background: #65BE9C;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-add-reservation:hover {
            background: #54a987;
            transform: translateY(-2px);
            color: white;
        }
        .no-reservations {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .no-reservations p {
            color: #666;
            margin-bottom: 20px;
        }
        /* Payment option image size control */
        .payment-options img {
            max-width: 60px;
            height: auto;
        }
    </style>
</head>

<body class="home">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/Logot.png" alt="Tasty Tab" style="max-height: 50px; width: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse" aria-controls="mainNavbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="restaurants.php">Restaurants</a>
                        </li>
                        <?php
                        if(empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>';
                            echo '<li class="nav-item"><a href="registration.php" class="nav-link">Register</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link">My Orders</a></li>';
                            echo '<li class="nav-item"><a href="table_reservation.php" class="nav-link active">Table Reservation</a></li>';
                            echo '<li class="nav-item"><a href="user_details.php" class="nav-link">My Profile</a></li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="container">
            <a href="add_table_reservation.php" class="btn-add-reservation">+ Add New Reservation</a>
            
            <?php if(mysqli_num_rows($reservations_result) > 0): ?>
                <?php while($reservation = mysqli_fetch_array($reservations_result)): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h3 class="reservation-title"><?php echo $reservation['restaurant_name']; ?></h3>
                            <span class="reservation-status status-<?php echo $reservation['status']; ?>">
                                <?php echo ucfirst($reservation['status']); ?>
                            </span>
                        </div>
                        <div class="reservation-details">
                            <div class="detail-item">
                                <div class="detail-label">Date</div>
                                <div class="detail-value"><?php echo date('F j, Y', strtotime($reservation['reservation_date'])); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Time</div>
                                <div class="detail-value"><?php echo date('g:i A', strtotime($reservation['reservation_time'])); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Number of Guests</div>
                                <div class="detail-value"><?php echo $reservation['guests']; ?></div>
                            </div>
                            <?php if(!empty($reservation['special_requests'])): ?>
                                <div class="detail-item">
                                    <div class="detail-label">Special Requests</div>
                                    <div class="detail-value"><?php echo $reservation['special_requests']; ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-reservations">
                    <h3>No Reservations Found</h3>
                    <p>You haven't made any table reservations yet.</p>
                    <a href="add_table_reservation.php" class="btn-add-reservation">Make Your First Reservation</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="bottom-footer">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 payment-options color-gray">
                        <h5>Payment Option</h5>
                        <ul>
                            <li>
                                <a href="#"> <img src="images/khalti.png" alt="Khalti"> </a>
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
</html> 