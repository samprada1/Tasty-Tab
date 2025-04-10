<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if user is logged in and is a staff member
if(empty($_SESSION["adm_id"]) || $_SESSION["user_type"] !== "staff") {
    header('location:index.php');
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Staff Orders</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .restricted-area {
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.5;
        }
        .orders-section {
            cursor: pointer;
        }
        .orders-section:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body class="fix-header fix-sidebar">
   
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
  
    <div id="main-wrapper">
   
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="staff_dashboard.php">
                        <span><img src="images/logo.png" alt="homepage" class="dark-logo" style="max-height: 50px; width: auto;" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
      
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li><a href="staff_dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Orders</li>
                        <li class="orders-section"><a href="staff_orders.php"><i class="fa fa-shopping-cart"></i><span>View Orders</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">All Orders</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Title</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql="SELECT users.*, users_orders.* FROM users INNER JOIN users_orders ON users.u_id=users_orders.u_id ORDER BY users_orders.o_id DESC";
                                            $query=mysqli_query($db,$sql);
                                            
                                            if(!mysqli_num_rows($query) > 0)
                                            {
                                                echo '<td colspan="8"><center>No Orders</center></td>';
                                            }
                                            else
                                            {               
                                                while($rows=mysqli_fetch_array($query))
                                                {
                                                    echo '<tr>
                                                        <td>#'.$rows['o_id'].'</td>
                                                        <td>'.$rows['username'].'</td>
                                                        <td>'.$rows['title'].'</td>
                                                        <td>'.$rows['quantity'].'</td>
                                                        <td>Rs. '.$rows['price'].'</td>';
                                                    
                                                    $status=$rows['status'];
                                                    if($status=="" or $status=="NULL")
                                                    {
                                                        echo '<td><span class="badge badge-info">Pending</span></td>';
                                                    }
                                                    elseif($status=="in process")
                                                    {
                                                        echo '<td><span class="badge badge-warning">In Process</span></td>';
                                                    }
                                                    elseif($status=="closed")
                                                    {
                                                        echo '<td><span class="badge badge-success">Delivered</span></td>';
                                                    }
                                                    elseif($status=="rejected")
                                                    {
                                                        echo '<td><span class="badge badge-danger">Cancelled</span></td>';
                                                    }
                                                    
                                                    echo '<td>'.$rows['date'].'</td>';
                                                    echo '<td>
                                                        <a href="view_order.php?user_upd='.$rows['o_id'].'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>
                                                    </td></tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer"> @2025 - TastyTab (RMS)</footer>
        </div>
    </div>
    
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
</body>
</html> 