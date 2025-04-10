<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
include_once 'product-action.php';
error_reporting(0);
session_start();


function function_alert() { 
      

    echo "<script>alert('Thank you. Your Order has been placed!');</script>"; 
    echo "<script>window.location.replace('your_orders.php');</script>"; 
} 

if(empty($_SESSION["user_id"]))
{
	header('location:login.php');
}
else{
    // Improved discount validation and calculation
    function validateDiscount($code, $total) {
        // You can expand this array with more promo codes
        $valid_promos = [
            'SAM345' => [
                'percentage' => 30,
                'min_order' => 500,
                'expiry' => '2024-12-31'
            ]
        ];
        
        if(isset($valid_promos[$code])) {
            $promo = $valid_promos[$code];
            
            // Check minimum order value
            if($total < $promo['min_order']) {
                return [
                    'valid' => false,
                    'message' => 'Minimum order value of Rs. ' . $promo['min_order'] . ' required'
                ];
            }
            
            // Check expiry
            if(strtotime($promo['expiry']) < time()) {
                return [
                    'valid' => false,
                    'message' => 'Promo code has expired'
                ];
            }
            
            return [
                'valid' => true,
                'percentage' => $promo['percentage']
            ];
        }
        
        return [
            'valid' => false,
            'message' => 'Invalid promo code'
        ];
    }

    $item_total = 0;
    foreach ($_SESSION["cart_item"] as $item)
    {
        $item_total += ($item["price"]*$item["quantity"]);
    }

    // Apply discount with validation
    if(isset($_GET['discount']) && $_GET['discount'] == '30') {
        $promo_result = validateDiscount('SAM345', $item_total);
        if($promo_result['valid']) {
            $original_total = $item_total;
            $discount = $item_total * ($promo_result['percentage'] / 100);
            $item_total = $item_total - $discount;
            $discount_message = "✓ Promo code SAM345 applied successfully!";
        } else {
            $discount_message = "❌ " . $promo_result['message'];
        }
    }

    if(isset($_POST['submit'])) {
        $payment_method = $_POST['mod'];
        
        if($payment_method == 'COD') {
            foreach ($_SESSION["cart_item"] as $item) {
                $price = $item["price"] * $item["quantity"];
                if(isset($_GET['discount']) && $_GET['discount'] == '30') {
                    $price = $price * 0.7; // Apply 30% discount
                }
                
                $SQL = "INSERT INTO users_orders(u_id, title, quantity, price, payment_method, payment_status, status) 
                        VALUES ('".$_SESSION["user_id"]."', '".$item["title"]."', '".$item["quantity"]."', 
                                '".$price."', 'COD', 'pending', 'in process')";
                mysqli_query($db, $SQL);
            }
            
            unset($_SESSION["cart_item"]);
            $success = "Thank you. Your order has been placed!";
            function_alert();
        } else if($payment_method == 'khalti') {
            // Khalti payment will be handled by the JavaScript
        }
    }
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Checkout</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- Khalti SDK -->
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.22.0.0.0/khalti-checkout.iffe.js"></script>
    <style>
        /* Payment option image size control */
        .payment-options img {
            max-width: 60px;
            height: auto;
        }
    </style>
</head>
<body>
    
    <div class="site-wrapper">
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
                      
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item "><span>2</span><a href="#">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active" ><span>3</span><a href="checkout.php">Order and Pay</a></li>
                    </ul>
                </div>
            </div>
			
                <div class="container">
                 
					   <span style="color:green;">
								<?php echo $success; ?>
										</span>
					
                </div>
            
			
			
				  
            <div class="container m-t-30">
			<form action="" method="post">
                <div class="widget clearfix">
                    
                    <div class="widget-body">
                        <form method="post" action="#">
                            <div class="row">
                                
                                <div class="col-sm-12">
                                    <div class="cart-totals margin-b-20">
                                        <div class="cart-totals-title">
                                            <h4>Cart Summary</h4>
                                        </div>
                                        <div class="cart-totals-fields">
                                            <table class="table">
                                                <tbody>
                                                    <?php
                                                    // First show individual items with their prices
                                                    foreach ($_SESSION["cart_item"] as $item) {
                                                        $original_item_price = $item["price"] * $item["quantity"];
                                                        if(isset($_GET['discount']) && $_GET['discount'] == '30') {
                                                            $discounted_price = $original_item_price * 0.7;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $item["title"]; ?> (x<?php echo $item["quantity"]; ?>)</td>
                                                                <td>
                                                                    <span style="text-decoration: line-through; color: #999;">Rs. <?php echo number_format($original_item_price, 2); ?></span><br>
                                                                    <span style="color: #65BE9C;">Rs. <?php echo number_format($discounted_price, 2); ?></span>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $item["title"]; ?> (x<?php echo $item["quantity"]; ?>)</td>
                                                                <td>Rs. <?php echo number_format($original_item_price, 2); ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }

                                                    if(isset($_GET['discount']) && $_GET['discount'] == '30') {
                                                        $discount = $item_total * 0.3;
                                                        $discounted_total = $item_total - $discount;
                                                        ?>
                                                        <tr>
                                                            <td colspan="2"><hr></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Original Total</td>
                                                            <td style="text-decoration: line-through; color: #999;">Rs. <?php echo number_format($original_total, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Discount (30%)</td>
                                                            <td style="color: #65BE9C;">- Rs. <?php echo number_format($discount, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Delivery Charges</td>
                                                            <td>Free</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-color"><strong>Final Total</strong></td>
                                                            <td class="text-color" style="color: #65BE9C;"><strong>Rs. <?php echo number_format($discounted_total, 2); ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center; color: #65BE9C; padding-top: 10px;">
                                                                ✓ Promo code SAM345 applied successfully!
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        // Update item_total for payment processing
                                                        $item_total = $discounted_total;
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td>Cart Subtotal</td>
                                                            <td>Rs. <?php echo $item_total; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Delivery Charges</td>
                                                            <td>Free</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-color"><strong>Total</strong></td>
                                                            <td class="text-color"><strong>Rs. <?php echo $item_total; ?></strong></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="payment-option">
                                        <ul class=" list-unstyled">
                                            <li>
                                                <label class="custom-control custom-radio  m-b-20">
                                                    <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Cash on Delivery</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod" id="radioStacked2" value="khalti" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Pay with Khalti <img src="images/khalti.png" alt="Khalti" width="90"></span>
                                                </label>
                                            </li>
                                        </ul>
                                        <p class="text-xs-center"> 
                                            <input type="submit" onclick="return handlePayment(event);" name="submit" class="btn btn-success btn-block" value="Order Now"> 
                                        </p>
                                    </div>
									</form>
                                </div>
                            </div>
                       
                    </div>
                </div>
				 </form>
            </div>
            
            <footer class="footer">
                    <div class="row bottom-footer">
                        <div class="container">
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
                </div>
            </footer>
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
    <script>
        function handlePayment(e) {
            if(document.getElementById('radioStacked2').checked) {
                e.preventDefault();
                // Initiate payment first
                fetch('initiate_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.payment_url) {
                        // Redirect to Khalti payment page
                        window.location.href = data.payment_url;
                    } else {
                        alert('Payment initiation failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Payment initiation failed. Please try again.');
                });
            } else {
                // For COD, just confirm and let the form submit
                if(!confirm('Do you want to confirm the order?')) {
                    e.preventDefault();
                }
            }
        }
    </script>
</body>

</html>

<?php
}
?>
