<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");  
error_reporting(0);  
session_start();

if(empty($_SESSION["user_id"])) {
    header('location:login.php');
}

if(isset($_POST['submit'])) {
    $user_id = $_SESSION["user_id"];
    $restaurant_id = $_POST['restaurant'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];
    $special_requests = $_POST['special_requests'];
    
    $SQL = "INSERT INTO table_reservations(user_id, restaurant_id, reservation_date, reservation_time, guests, special_requests, status) 
            VALUES ('$user_id', '$restaurant_id', '$date', '$time', '$guests', '$special_requests', 'pending')";
    
    if(mysqli_query($db, $SQL)) {
        $success = "Table reservation request submitted successfully!";
    } else {
        $error = "Error submitting reservation. Please try again.";
    }
}
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
            max-width: 800px;
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
        .reservation-form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-control {
            width: 100%;
            height: 45px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #65BE9C;
            outline: none;
            box-shadow: 0 0 0 2px rgba(101, 190, 156, 0.2);
        }
        textarea.form-control {
            height: 100px;
            resize: vertical;
        }
        .btn-reserve {
            background: #65BE9C;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: auto;
            display: inline-block;
        }
        .btn-reserve:hover {
            background: #54a987;
            transform: translateY(-2px);
        }
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .footer {
            background: #1a1a1a;
            color: #fff;
            padding: 40px 0;
            margin-top: 60px;
        }
        .color-gray {
            color: #aaa;
        }
        .footer h5 {
            color: #fff;
            margin-bottom: 20px;
        }
        .footer ul {
            list-style: none;
            padding: 0;
        }
        .footer p {
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .container {
                padding-top: 100px;
            }
            .navbar-brand img {
                max-height: 40px;
            }
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
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="reservation-form">
                        <h2 class="text-center mb-4">Table Reservation</h2>
                        
                        <?php if(isset($success)) { ?>
                            <div class="alert alert-success">
                                <?php echo $success; ?>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($error)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php } ?>
                        
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="restaurant">Select Restaurant</label>
                                <select class="form-control" id="restaurant" name="restaurant" required>
                                    <option value="">Choose a restaurant</option>
                                    <?php
                                    $query = mysqli_query($db, "SELECT * FROM restaurant");
                                    while($row = mysqli_fetch_array($query)) {
                                        echo '<option value="'.$row['rs_id'].'">'.$row['title'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="guests">Number of Guests</label>
                                <select class="form-control" id="guests" name="guests" required>
                                    <option value="">Select number of guests</option>
                                    <?php for($i = 1; $i <= 10; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="special_requests">Special Requests (Optional)</label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-reserve">Make Reservation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="bottom-footer">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 payment-options color-gray">
                        <h5>Payment Options</h5>
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