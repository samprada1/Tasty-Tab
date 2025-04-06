<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id']))  
{
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Status Board</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
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
            background: #f5f5f5;
            min-height: 100vh;
        }
        .kanban-board {
            display: flex;
            gap: 20px;
            padding: 20px;
            overflow-x: auto;
            min-height: calc(100vh - 300px);
        }
        .kanban-column {
            background: rgba(22, 22, 22, 0.8);
            border-radius: 8px;
            min-width: 300px;
            width: 300px;
            padding: 15px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
        .column-header {
            color: white;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: inherit;
            z-index: 1;
        }
        .column-header .count {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 14px;
            color: white;
        }
        .order-card {
            background: white;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .order-card .title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .order-card .meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        .order-card .price {
            color: #5c4ac7;
            font-weight: bold;
            font-size: 16px;
            margin: 8px 0;
        }
        .priority {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .priority.high {
            background: #ff4444;
            color: white;
        }
        .priority.medium {
            background: #ffbb33;
            color: white;
        }
        .priority.low {
            background: #00C851;
            color: white;
        }
        .status-label {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 8px;
            font-weight: 500;
        }
        .status-label.pending {
            background: #33b5e5;
            color: white;
        }
        .status-label.processing {
            background: #ffbb33;
            color: white;
        }
        .status-label.onway {
            background: #5c4ac7;
            color: white;
        }
        .status-label.delivered {
            background: #00C851;
            color: white;
        }
        @media (max-width: 768px) {
            .kanban-board {
                flex-direction: column;
                padding: 10px;
            }
            .kanban-column {
                width: 100%;
                min-width: 100%;
                margin-bottom: 20px;
            }
        }
        /* Custom scrollbar */
        .kanban-column::-webkit-scrollbar {
            width: 8px;
        }
        .kanban-column::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }
        .kanban-column::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .kanban-column::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
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
                        <li class="nav-item"> <a class="nav-link active" href="logout.php">Logout</a> </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-4">Order Status Board</h2>
                    <div class="kanban-board">
                        <!-- To Do Column -->
                        <div class="kanban-column">
                            <div class="column-header">
                                <span>To Do</span>
                                <?php 
                                $todo_count = mysqli_num_rows(mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND (status = '' OR status IS NULL)"));
                                ?>
                                <span class="count"><?php echo $todo_count; ?></span>
                            </div>
                            <?php 
                            $query_todo = mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND (status = '' OR status IS NULL)");
                            while($row = mysqli_fetch_array($query_todo)) {
                            ?>
                            <div class="order-card">
                                <div class="priority high">New Order</div>
                                <div class="title"><?php echo $row['title']; ?></div>
                                <div class="meta">Order #<?php echo $row['o_id']; ?></div>
                                <div class="meta">Quantity: <?php echo $row['quantity']; ?></div>
                                <div class="price">$<?php echo $row['price']; ?></div>
                                <div class="meta">Date: <?php echo $row['date']; ?></div>
                                <div class="status-label pending">Pending</div>
                            </div>
                            <?php } ?>
                        </div>

                        <!-- In Processing Column -->
                        <div class="kanban-column">
                            <div class="column-header">
                                <span>In Processing</span>
                                <?php 
                                $processing_count = mysqli_num_rows(mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'in process'"));
                                ?>
                                <span class="count"><?php echo $processing_count; ?></span>
                            </div>
                            <?php 
                            $query_processing = mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'in process'");
                            while($row = mysqli_fetch_array($query_processing)) {
                            ?>
                            <div class="order-card">
                                <div class="priority medium">Processing</div>
                                <div class="title"><?php echo $row['title']; ?></div>
                                <div class="meta">Order #<?php echo $row['o_id']; ?></div>
                                <div class="meta">Quantity: <?php echo $row['quantity']; ?></div>
                                <div class="price">$<?php echo $row['price']; ?></div>
                                <div class="meta">Date: <?php echo $row['date']; ?></div>
                                <div class="status-label processing">In Kitchen</div>
                            </div>
                            <?php } ?>
                        </div>

                        <!-- On The Way Column -->
                        <div class="kanban-column">
                            <div class="column-header">
                                <span>On The Way</span>
                                <?php 
                                $onway_count = mysqli_num_rows(mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'on_the_way'"));
                                ?>
                                <span class="count"><?php echo $onway_count; ?></span>
                            </div>
                            <?php 
                            $query_onway = mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'on_the_way'");
                            while($row = mysqli_fetch_array($query_onway)) {
                            ?>
                            <div class="order-card">
                                <div class="priority medium">Out for Delivery</div>
                                <div class="title"><?php echo $row['title']; ?></div>
                                <div class="meta">Order #<?php echo $row['o_id']; ?></div>
                                <div class="meta">Quantity: <?php echo $row['quantity']; ?></div>
                                <div class="price">$<?php echo $row['price']; ?></div>
                                <div class="meta">Date: <?php echo $row['date']; ?></div>
                                <div class="status-label onway">On The Way</div>
                            </div>
                            <?php } ?>
                        </div>

                        <!-- Delivered Column -->
                        <div class="kanban-column">
                            <div class="column-header">
                                <span>Delivered</span>
                                <?php 
                                $delivered_count = mysqli_num_rows(mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'closed'"));
                                ?>
                                <span class="count"><?php echo $delivered_count; ?></span>
                            </div>
                            <?php 
                            $query_delivered = mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' AND status = 'closed' ORDER BY date DESC LIMIT 5");
                            while($row = mysqli_fetch_array($query_delivered)) {
                            ?>
                            <div class="order-card">
                                <div class="priority low">Completed</div>
                                <div class="title"><?php echo $row['title']; ?></div>
                                <div class="meta">Order #<?php echo $row['o_id']; ?></div>
                                <div class="meta">Quantity: <?php echo $row['quantity']; ?></div>
                                <div class="price">$<?php echo $row['price']; ?></div>
                                <div class="meta">Date: <?php echo $row['date']; ?></div>
                                <div class="status-label delivered">Delivered</div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
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
            <div class="row">
                <div class="col-xs-12 text-center" style="margin-top: 15px; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 15px;">
                    <p style="color: #666;">@2025 - TastyTab (RMS)</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html> 