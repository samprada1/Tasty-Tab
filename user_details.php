<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id'])) {
    header('location:login.php');
}

if(isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $address = mysqli_real_escape_string($db, $_POST['address']);

    $sql = "UPDATE users SET username='$username', f_name='$firstname', l_name='$lastname', 
            email='$email', phone='$phone', address='$address' WHERE u_id='$user_id'";
    
    $query = mysqli_query($db, $sql);
    if($query) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile. Please try again.";
    }
}

// Fetch current user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE u_id='$user_id'";
$query = mysqli_query($db, $sql);
$user = mysqli_fetch_assoc($query);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>User Details</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        /* Update navbar styles to match the reference */
        .navbar-dark {
            background: transparent !important;
            padding: 15px 0;
        }

        .nav-link {
            font-family: 'Poppins', sans-serif;
            color: white !important;
            font-size: 15px;
            padding: 8px 15px !important;
        }

        .signup-btn {
            background: #65BE9C;
            color: white !important;
            padding: 8px 20px !important;
            border-radius: 25px;
            margin-left: 10px;
        }

        .navbar-nav {
            font-family: 'Poppins', sans-serif;
        }

        .navbar-brand img {
            max-height: 120px;
            width: auto;
        }

        /* Page specific styles */
        .page-wrapper {
            background: #fafafa;
            min-height: calc(100vh - 200px);
            padding: 20px 0;
        }

        #header {
            background: rgba(0, 0, 0, 0.8);
            width: 100%;
            z-index: 1000;
        }

        .widget {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 25px;
            padding: 10px 20px;
            height: auto;
            border: 1px solid #eee;
        }

        .form-control:focus {
            border-color: #65BE9C;
            box-shadow: none;
        }

        .theme-btn {
            background: #65BE9C !important;
            color: white !important;
            border: none;
            height: 50px;
            border-radius: 30px !important;
            padding: 0 30px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .theme-btn:hover {
            background: #54a987 !important;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="">
            <div class="" style="width:full; display: flex; justify-content: space-between; align-items: center; padding: 0 50px;">
                <!-- <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button> -->
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Logot.png" alt=""> </a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>
                        <?php
                        if(empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
                            <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
                        } else {
                            echo '<li class="nav-item"><a href="user_orders.php" class="nav-link active">My Orders</a> </li>';
                            echo '<li class="nav-item"><a href="user_details.php" class="nav-link active">My Profile</a> </li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page-wrapper">
        <div class="container" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-center mb-4">My Profile</h3>
                                    <?php 
                                    if(isset($success)) { 
                                        echo '<div class="alert alert-success text-center">'.$success.'</div>';
                                    } else if(isset($error)) {
                                        echo '<div class="alert alert-danger text-center">'.$error.'</div>';
                                    }
                                    ?>
                                    <form action="" method="post">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" name="firstname" value="<?php echo $user['f_name']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" name="lastname" value="<?php echo $user['l_name']; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control" name="phone" value="<?php echo $user['phone']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Delivery Address</label>
                                            <textarea class="form-control" name="address" rows="3" required><?php echo $user['address']; ?></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" name="submit" class="btn theme-btn">Update Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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