<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Restaurants</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css">
      .header-scroll {
        background: rgba(22, 22, 22, 0.8);
        padding: 5px 0;
        position: fixed;
        width: 100%;
        z-index: 1000;
        top: 0;
        height: 90px;
      }
      .navbar-brand {
        padding: 5px 15px;
      }
      .navbar-brand img {
        max-height: 45px !important;
        width: auto;
      }
      .page-wrapper {
        padding-top: 80px;
        position: relative;
        z-index: 1;
      }
      .restaurant-entry {
        background: #ffffff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 25px;
      }
      .top-links {
        margin: 15px 0 30px;
        background: #fff;
        padding: 15px 0;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      }
      .inner-page-hero {
        margin-bottom: 30px;
        padding: 30px 0;
        background-position: center;
        background-size: cover;
      }
      .nav-item {
        padding: 0 10px;
      }
      .nav-link {
        color: #fff !important;
        font-size: 14px;
        padding: 10px 0 !important;
      }
      @media (max-width: 991px) {
        .header-scroll {
          height: auto;
        }
        .page-wrapper {
          padding-top: 70px;
        }
      }
    </style>
</head>

<body>
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Logot.png" alt=""> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>
                            
							<?php
						if(empty($_SESSION["user_id"]))
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
							}
						else
							{
									
									
										echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a> </li>';
									echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
							}

						?>
							 
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="page-wrapper">
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                       
                        <li class="col-xs-12 col-sm-4 link-item active"><span>1</span><a href="#">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>
                    </ul>
                </div>
            </div>
            <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
                <div class="container"> </div>
            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">     
                        <?php
                        if(isset($_GET['search'])) {
                            $search = mysqli_real_escape_string($db, $_GET['search']);
                            
                            // Search in restaurants
                            $restaurant_query = "SELECT * FROM restaurant WHERE title LIKE '%$search%' OR address LIKE '%$search%'";
                            $restaurant_result = mysqli_query($db, $restaurant_query);
                            
                            // Search in dishes
                            $dish_query = "SELECT d.*, r.title as restaurant_name, r.image as restaurant_image 
                                         FROM dishes d 
                                         JOIN restaurant r ON d.rs_id = r.rs_id 
                                         WHERE d.title LIKE '%$search%' OR d.slogan LIKE '%$search%'";
                            $dish_result = mysqli_query($db, $dish_query);
                            
                            if(mysqli_num_rows($restaurant_result) > 0 || mysqli_num_rows($dish_result) > 0) {
                                echo '<div class="col-12"><h3>Search Results for: "'.$search.'"</h3></div>';
                                
                                // Display restaurant results
                                if(mysqli_num_rows($restaurant_result) > 0) {
                                    echo '<div class="col-12"><h4>Restaurants</h4></div>';
                                    while($row = mysqli_fetch_array($restaurant_result)) {
                                        echo '<div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="restaurant-entry">
                                                    <div class="entry-logo">
                                                        <a href="dishes.php?res_id='.$row['rs_id'].'">
                                                            <img src="admin/Res_img/'.$row['image'].'" alt="Restaurant logo" class="img-fluid">
                                                        </a>
                                                    </div>
                                                    <div class="entry-dscr">
                                                        <h5><a href="dishes.php?res_id='.$row['rs_id'].'">'.$row['title'].'</a></h5>
                                                        <span>'.$row['address'].'</span>
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                }
                                
                                // Display dish results
                                if(mysqli_num_rows($dish_result) > 0) {
                                    echo '<div class="col-12"><h4>Dishes</h4></div>';
                                    while($row = mysqli_fetch_array($dish_result)) {
                                        echo '<div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="restaurant-entry">
                                                    <div class="entry-logo">
                                                        <a href="dishes.php?res_id='.$row['rs_id'].'">
                                                            <img src="admin/Res_img/dishes/'.$row['img'].'" alt="Dish image" class="img-fluid">
                                                        </a>
                                                    </div>
                                                    <div class="entry-dscr">
                                                        <h5><a href="dishes.php?res_id='.$row['rs_id'].'">'.$row['title'].'</a></h5>
                                                        <p>'.$row['slogan'].'</p>
                                                        <span>Restaurant: '.$row['restaurant_name'].'</span>
                                                        <div class="price">$'.$row['price'].'</div>
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                }
                            } else {
                                echo '<div class="col-12"><h3>No results found for: "'.$search.'"</h3></div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <section class="restaurants-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-3">
                        </div>
                        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-9">
                            <div class="bg-gray restaurant-entry">
                                <div class="row">
								<?php $ress= mysqli_query($db,"SELECT DISTINCT r.*, c.c_name 
                                                               FROM restaurant r 
                                                               JOIN res_category c ON r.c_id = c.c_id 
                                                               GROUP BY r.title 
                                                               ORDER BY r.rs_id DESC");
									      while($rows=mysqli_fetch_array($ress))
										  {
													
						
													 echo' <div class="col-sm-12 col-md-12 col-lg-8 text-xs-center text-sm-left">
															<div class="entry-logo">
																<a class="img-fluid" href="dishes.php?res_id='.$rows['rs_id'].'" > <img src="admin/Res_img/'.$rows['image'].'" alt="Food logo"></a>
															</div>
															<!-- end:Logo -->
															<div class="entry-dscr">
																<h5><a href="dishes.php?res_id='.$rows['rs_id'].'" >'.$rows['title'].'</a></h5> <span>'.$rows['address'].'</span>
																
															</div>
															<!-- end:Entry description -->
														</div>
														
														 <div class="col-sm-12 col-md-12 col-lg-4 text-xs-center">
																<div class="right-content bg-white">
																	<div class="right-review">
																		
																		<a href="dishes.php?res_id='.$rows['rs_id'].'" class="btn btn-purple">View Menu</a> </div>
																</div>
																<!-- end:right info -->
															</div>';
										  }
						
						
						?>
                                    
                                </div>
                
                            </div>
                         
                            
                                
                            </div>
                          
                          
                           
                        </div>
                    </div>
                </div>
            </section>
       
        <footer class="footer">
            <div class="container">
                
              
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