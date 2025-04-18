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
    <title>Home</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/img/dashboard.jpg');
            background-size: cover;
            background-position: center;
            height: 400px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 0 20px;
        }

        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 48px;
            font-weight: 600;
            color: white;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 30px;
            padding: 5px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: relative;
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #555;
            background: transparent;
        }

        .search-input:focus {
            outline: none;
        }

        .search-input::placeholder {
            color: #999;
        }

        .search-btn {
            background: #65BE9C;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 5px;
        }

        .search-btn:hover {
            background: #54a987;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-result-item:hover {
            background-color: #f5f5f5;
        }

        .search-result-item h5 {
            margin: 0;
            color: #333;
            font-size: 14px;
        }

        .search-result-item p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }

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
            max-height: 50px;
            width: auto;
        }

        .banner-form {
            max-width: 600px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .form-control-lg {
            background: white;
            border: none;
            border-radius: 30px !important;
            padding: 0 25px;
            height: 50px;
            font-size: 16px;
            width: 100%;
            max-width: 500px;
            margin-right: 10px;
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

        .form-inline {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        /* Restaurant card standardization */
        .restaurant-listing {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .single-restaurant {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .single-restaurant:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .restaurant-wrap {
            padding: 20px;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .restaurant-logo {
            margin-bottom: 15px;
        }

        .restaurant-logo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .restaurant-info {
            text-align: center;
        }

        .restaurant-wrap h5 {
            font-size: 18px;
            margin: 0 0 8px 0;
            font-weight: 600;
            color: #333;
        }

        .restaurant-wrap h5 a {
            color: #333;
            text-decoration: none;
        }

        .restaurant-wrap span {
            color: #666;
            font-size: 14px;
            display: block;
        }

        @media (max-width: 991px) {
            .restaurant-listing {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 576px) {
            .restaurant-listing {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        /* Filter button styles */
        .restaurants-filter {
            margin-bottom: 30px;
            text-align: center;
        }

        .restaurants-filter ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: inline-flex;
            gap: 15px;
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 50px;
        }

        .restaurants-filter ul li {
            display: inline-block;
        }

        .restaurants-filter ul li a {
            display: inline-block;
            padding: 8px 25px;
            color: #333;
            text-decoration: none;
            border-radius: 25px;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: transparent;
        }

        .restaurants-filter ul li a:hover {
            background: #65BE9C;
            color: white;
        }

        .restaurants-filter ul li a.selected {
            background: #65BE9C;
            color: white;
        }

        .payment-options img {
            max-width: 60px;
            height: auto;
        }
    </style>
</head>

<body class="home">
    
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Logot.png" alt="" style="max-height: 120px; width: auto;"> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>
                            
                           
							<?php
						if(empty($_SESSION["user_id"])) // if user is not login
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
							}
						else
							{

									
									echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a> </li>';
									echo  '<li class="nav-item"><a href="table_reservation.php" class="nav-link active">Table Reservation</a> </li>';
									echo  '<li class="nav-item"><a href="user_details.php" class="nav-link active">My Profile</a> </li>';
									echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
							}

						?>
							 
                        </ul>
						 
                    </div>
                </div>
            </nav>

        </header>

        <section class="hero">
            <div class="hero-content">
                <h1>Online Restaurants</h1>
                <p>Top restaurants and specials in town</p>
                <form action="restaurants.php" method="GET" class="search-box">
                    <input type="text" name="search" class="search-input" placeholder="Enter Search..." id="searchInput" autocomplete="off">
                    <button type="submit" class="search-btn">Search food</button>
                    <div class="search-results" id="searchResults"></div>
                </form>
            </div>
        </section>
      
      
	  
	
     
        <section class="popular">
            <div class="container">
                <div class="title text-xs-center m-b-30">
                    <h2>Popular Dishes of the Month</h2>
                    <p class="lead">Easiest way to order your favourite food among these top 6 dishes</p>
                </div>
                <div class="row">
						<?php 					
						$query_res= mysqli_query($db,"select * from dishes LIMIT 6"); 
                                while($r=mysqli_fetch_array($query_res))
                                {
                                        
                                    echo '  <div class="col-xs-12 col-sm-6 col-md-4 food-item">
                                            <div class="food-item-wrap">
                                                <div class="figure-wrap bg-image" data-image-src="admin/Res_img/dishes/'.$r['img'].'"></div>
                                                <div class="content">
                                                    <h5><a href="dishes.php?res_id='.$r['rs_id'].'">'.$r['title'].'</a></h5>
                                                    <div class="product-name">'.$r['slogan'].'</div>
                                                    <div class="price-btn-block"> <span class="price">Rs. '.$r['price'].'</span> <a href="dishes.php?res_id='.$r['rs_id'].'" class="btn theme-btn-dash pull-right">Order Now</a> </div>
                                                </div>
                                                
                                            </div>
                                    </div>';                                      
                                }	
						?>
                </div>
            </div>
        </section>
 
        <section class="how-it-works">
            <div class="container">
                <div class="text-xs-center">
                    <h2>Easy to Order</h2>
                    <div class="row how-it-works-solution">
                        <div class="col-xs-12 col-sm-12 col-md-4 how-it-works-steps white-txt col1">
                            <div class="how-it-works-wrap">
                                <div class="step step-1">
                                    <div class="icon" data-step="1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 483 483" width="512" height="512">
                                            <g fill="#FFF">
                                                <path d="M467.006 177.92c-.055-1.573-.469-3.321-1.233-4.755L407.006 62.877V10.5c0-5.799-4.701-10.5-10.5-10.5h-310c-5.799 0-10.5 4.701-10.5 10.5v52.375L17.228 173.164a10.476 10.476 0 0 0-1.22 4.938h-.014V472.5c0 5.799 4.701 10.5 10.5 10.5h430.012c5.799 0 10.5-4.701 10.5-10.5V177.92zM282.379 76l18.007 91.602H182.583L200.445 76h81.934zm19.391 112.602c-4.964 29.003-30.096 51.143-60.281 51.143-30.173 0-55.295-22.139-60.258-51.143H301.77zm143.331 0c-4.96 29.003-30.075 51.143-60.237 51.143-30.185 0-55.317-22.139-60.281-51.143h120.518zm-123.314-21L303.78 76h86.423l48.81 91.602H321.787zM97.006 55V21h289v34h-289zm-4.198 21h86.243l-17.863 91.602h-117.2L92.808 76zm65.582 112.602c-5.028 28.475-30.113 50.19-60.229 50.19s-55.201-21.715-60.23-50.19H158.39zM300 462H183V306h117v156zm21 0V295.5c0-5.799-4.701-10.5-10.5-10.5h-138c-5.799 0-10.5 4.701-10.5 10.5V462H36.994V232.743a82.558 82.558 0 0 0 3.101 3.255c15.485 15.344 36.106 23.794 58.065 23.794s42.58-8.45 58.065-23.794a81.625 81.625 0 0 0 13.525-17.672c14.067 25.281 40.944 42.418 71.737 42.418 30.752 0 57.597-17.081 71.688-42.294 14.091 25.213 40.936 42.294 71.688 42.294 24.262 0 46.092-10.645 61.143-27.528V462H321z" />
                                                <path d="M202.494 386h22c5.799 0 10.5-4.701 10.5-10.5s-4.701-10.5-10.5-10.5h-22c-5.799 0-10.5 4.701-10.5 10.5s4.701 10.5 10.5 10.5z" /> </g>
                                        </svg>
                                    </div>
                                    <h3>Choose a restaurant</h3>
                                    <p>We"ve got your covered with menus from a variety of delivery restaurants online.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 how-it-works-steps white-txt col2">
                            <div class="step step-2">
                                <div class="icon" data-step="2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewbox="0 0 380.721 380.721">
                                        <g fill="#FFF">
                                            <path d="M58.727 281.236c.32-5.217.657-10.457 1.319-15.709 1.261-12.525 3.974-25.05 6.733-37.296a543.51 543.51 0 0 1 5.449-17.997c2.463-5.729 4.868-11.433 7.25-17.01 5.438-10.898 11.491-21.07 18.724-29.593 1.737-2.19 3.427-4.328 5.095-6.46 1.912-1.894 3.805-3.747 5.676-5.588 3.863-3.509 7.221-7.273 11.107-10.091 7.686-5.711 14.529-11.137 21.477-14.506 6.698-3.724 12.455-6.982 17.631-8.812 10.125-4.084 15.883-6.141 15.883-6.141s-4.915 3.893-13.502 10.207c-4.449 2.917-9.114 7.488-14.721 12.147-5.803 4.461-11.107 10.84-17.358 16.992-3.149 3.114-5.588 7.064-8.551 10.684-1.452 1.83-2.928 3.712-4.427 5.6a1225.858 1225.858 0 0 1-3.84 6.286c-5.537 8.208-9.673 17.858-13.995 27.664-1.748 5.1-3.566 10.283-5.391 15.534a371.593 371.593 0 0 1-4.16 16.476c-2.266 11.271-4.502 22.761-5.438 34.612-.68 4.287-1.022 8.633-1.383 12.979 94 .023 166.775.069 268.589.069.337-4.462.534-8.97.534-13.536 0-85.746-62.509-156.352-142.875-165.705 5.17-4.869 8.436-11.758 8.436-19.433-.023-14.692-11.921-26.612-26.631-26.612-14.715 0-26.652 11.92-26.652 26.642 0 7.668 3.265 14.558 8.464 19.426-80.396 9.353-142.869 79.96-142.869 165.706 0 4.543.168 9.027.5 13.467 9.935-.002 19.526-.002 28.926-.002zM0 291.135h380.721v33.59H0z" /> </g>
                                    </svg>
                                </div>
                                <h3>Choose a dish</h3>
                                <p>We"ve got your covered with a variety of delivery restaurants online.</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 how-it-works-steps white-txt col3">
                            <div class="step step-3">
                                <div class="icon" data-step="3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewbox="0 0 612.001 612">
                                        <path d="M604.131 440.17h-19.12V333.237c0-12.512-3.776-24.787-10.78-35.173l-47.92-70.975a62.99 62.99 0 0 0-52.169-27.698h-74.28c-8.734 0-15.737 7.082-15.737 15.738v225.043h-121.65c11.567 9.992 19.514 23.92 21.796 39.658H412.53c4.563-31.238 31.475-55.396 63.972-55.396 32.498 0 59.33 24.158 63.895 55.396h63.735c4.328 0 7.869-3.541 7.869-7.869V448.04c-.001-4.327-3.541-7.87-7.87-7.87zM525.76 312.227h-98.044a7.842 7.842 0 0 1-7.868-7.869v-54.372c0-4.328 3.541-7.869 7.868-7.869h59.724c2.597 0 4.957 1.259 6.452 3.305l38.32 54.451c3.619 5.194-.079 12.354-6.452 12.354zM476.502 440.17c-27.068 0-48.943 21.953-48.943 49.021 0 26.99 21.875 48.943 48.943 48.943 26.989 0 48.943-21.953 48.943-48.943 0-27.066-21.954-49.021-48.943-49.021zm0 73.495c-13.535 0-24.472-11.016-24.472-24.471 0-13.535 10.937-24.473 24.472-24.473 13.533 0 24.472 10.938 24.472 24.473 0 13.455-10.938 24.471-24.472 24.471zM68.434 440.17c-4.328 0-7.869 3.543-7.869 7.869v23.922c0 4.328 3.541 7.869 7.869 7.869h87.971c2.282-15.738 10.229-29.666 21.718-39.658H68.434v-.002zm151.864 0c-26.989 0-48.943 21.953-48.943 49.021 0 26.99 21.954 48.943 48.943 48.943 27.068 0 48.943-21.953 48.943-48.943.001-27.066-21.874-49.021-48.943-49.021zm0 73.495c-13.534 0-24.471-11.016-24.471-24.471 0-13.535 10.937-24.473 24.471-24.473s24.472 10.938 24.472 24.473c0 13.455-10.938 24.471-24.472 24.471zm117.716-363.06h-91.198c4.485 13.298 6.846 27.54 6.846 42.255 0 74.28-60.431 134.711-134.711 134.711-13.535 0-26.675-2.045-39.029-5.744v86.949c0 4.328 3.541 7.869 7.869 7.869h265.96c4.329 0 7.869-3.541 7.869-7.869V174.211c-.001-13.062-10.545-23.606-23.606-23.606zM118.969 73.866C53.264 73.866 0 127.129 0 192.834s53.264 118.969 118.969 118.969 118.97-53.264 118.97-118.969-53.265-118.968-118.97-118.968zm0 210.864c-50.752 0-91.896-41.143-91.896-91.896s41.144-91.896 91.896-91.896c50.753 0 91.896 41.144 91.896 91.896 0 50.753-41.143 91.896-91.896 91.896zm35.097-72.488c-1.014 0-2.052-.131-3.082-.407L112.641 201.5a11.808 11.808 0 0 1-8.729-11.396v-59.015c0-6.516 5.287-11.803 11.803-11.803 6.516 0 11.803 5.287 11.803 11.803v49.971l29.614 7.983c6.294 1.698 10.02 8.177 8.322 14.469-1.421 5.264-6.185 8.73-11.388 8.73z" fill="#FFF" /> </svg>
                                </div>
                                <h3>Pick up or Delivery</h3>
                                <p>Get your food delivered! And enjoy your meal! </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p class="pay-info">Cash on Delivery</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="featured-restaurants">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="title-block pull-left">
                            <h4>Featured Restaurants</h4> </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="restaurants-filter pull-right">
                            <nav class="primary">
                                <ul>
                                    <li><a class="selected" data-filter="*">All</a></li>
                                    <?php 
                                    $res= mysqli_query($db,"select * from res_category");
                                    while($row=mysqli_fetch_array($res))
                                    {
                                        $categoryName = htmlspecialchars(trim($row['c_name']));
                                        echo '<li><a data-filter="'.htmlspecialchars($categoryName).'">'.$categoryName.'</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="restaurant-listing">
                        <?php  
                        $ress = mysqli_query($db,"SELECT r.*, c.c_name, c.c_id 
                                                FROM restaurant r 
                                                JOIN res_category c ON r.c_id = c.c_id 
                                                GROUP BY r.title 
                                                ORDER BY r.rs_id DESC");
                        
                        while($rows = mysqli_fetch_array($ress))
                        {
                            $categoryName = htmlspecialchars(trim($rows['c_name']));
                            echo '<div class="single-restaurant" data-category="'.$categoryName.'">
                                <div class="restaurant-wrap">
                                    <div class="restaurant-logo">
                                        <a href="dishes.php?res_id='.$rows['rs_id'].'"> 
                                            <img src="admin/Res_img/'.$rows['image'].'" alt="Restaurant logo"> 
                                        </a>
                                    </div>
                                    <div class="restaurant-info">
                                        <h5><a href="dishes.php?res_id='.$rows['rs_id'].'">'.$rows['title'].'</a></h5>
                                        <span>'.$rows['address'].'</span>
                                    </div>
                                </div>
                            </div>';
                        }
                        ?>
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
    
    <!-- Load all scripts in correct order -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize restaurant filtering
            var $grid = $('.restaurant-listing').isotope({
                itemSelector: '.single-restaurant',
                layoutMode: 'fitRows'
            });

            // Debug: Log all available categories
            $('.single-restaurant').each(function() {
                console.log('Restaurant category:', $(this).attr('data-category'));
            });

            $('.restaurants-filter ul li a').on('click', function(e) {
                e.preventDefault();
                
                // Remove selected class from all and add to current
                $('.restaurants-filter ul li a').removeClass('selected');
                $(this).addClass('selected');
                
                var filterValue = $(this).attr('data-filter');
                console.log('Filter clicked:', filterValue);
                
                if (filterValue === '*') {
                    $grid.isotope({ filter: '*' });
                } else {
                    // Debug: Log the filter selector
                    console.log('Filter selector:', '[data-category="' + filterValue + '"]');
                    console.log('Matching elements:', $('.restaurant-listing').find('[data-category="' + filterValue + '"]').length);
                    
                    $grid.isotope({ filter: function() {
                        var category = $(this).attr('data-category');
                        console.log('Comparing:', category, 'with', filterValue);
                        return category === filterValue;
                    }});
                }
            });

            // Initialize search functionality
            const searchInput = $('#searchInput');
            const searchResults = $('#searchResults');
            
            searchInput.on('input', function() {
                const query = $(this).val().trim();
                
                if (query.length < 2) {
                    searchResults.hide();
                    return;
                }

                $.ajax({
                    url: 'search_food.php',
                    method: 'POST',
                    data: { query: query },
                    success: function(response) {
                        try {
                            const results = typeof response === 'string' ? JSON.parse(response) : response;
                            let html = '';
                            
                            if (results.error) {
                                html = '<div class="search-result-item">Error: ' + results.error + '</div>';
                            } else if (results && results.length > 0) {
                                results.forEach(item => {
                                    html += `
                                        <div class="search-result-item" onclick="window.location.href='dishes.php?res_id=${item.rs_id}'">
                                            <h5>${item.title}</h5>
                                            <p>${item.restaurant_name || ''} - Rs. ${item.price || 'N/A'}</p>
                                        </div>
                                    `;
                                });
                            } else {
                                html = '<div class="search-result-item">No results found</div>';
                            }
                            
                            searchResults.html(html).show();
                        } catch (e) {
                            console.error('Error:', e);
                            searchResults.html('<div class="search-result-item">Error processing results</div>').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        searchResults.html('<div class="search-result-item">Error searching</div>').show();
                    }
                });
            });

            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-box').length) {
                    searchResults.hide();
                }
            });
        });
    </script>
</body>

</html>