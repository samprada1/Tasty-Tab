<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION["adm_id"])) {
    header('location:index.php');
}

// Handle status update
if(isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['status'];
    
    $stmt = $db->prepare("UPDATE table_reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $reservation_id);
    $stmt->execute();
    $stmt->close();
}

// Handle deletion
if(isset($_GET['delete'])) {
    $reservation_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM table_reservations WHERE id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $stmt->close();
    header('location:all_reservations.php');
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>All Table Reservations</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 12px;
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
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons form {
            margin: 0;
        }
    </style>
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    
    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="images/logo.png" alt="homepage" class="dark-logo" style="max-height: 50px; width: auto;" /></span>
                    </a>
                </div>

                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                    </ul>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
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
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php">  <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">All Restaurant</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menues</a></li>
                                <li><a href="add_menu.php">Add Menu</a></li>
                            </ul>
                        </li>
                        <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                        <li> <a href="all_reservations.php"><i class="fa fa-calendar" aria-hidden="true"></i><span>Table Reservations</span></a></li>
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
                                    <h4 class="m-b-0 text-white">All Table Reservations</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Restaurant</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Guests</th>
                                                <th>Special Requests</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT tr.*, r.title as restaurant_name, u.username, u.email, u.phone 
                                                     FROM table_reservations tr 
                                                     JOIN restaurant r ON tr.restaurant_id = r.rs_id 
                                                     JOIN users u ON tr.user_id = u.u_id 
                                                     ORDER BY tr.reservation_date DESC, tr.reservation_time DESC";
                                            $result = mysqli_query($db, $query);
                                            
                                            if(!mysqli_num_rows($result) > 0) {
                                                echo '<tr><td colspan="10"><center>No Reservations Found</center></td></tr>';
                                            } else {
                                                while($row = mysqli_fetch_array($result)) {
                                                    echo '<tr>
                                                            <td>'.$row['id'].'</td>
                                                            <td>
                                                                <strong>'.$row['username'].'</strong><br>
                                                                Email: '.$row['email'].'<br>
                                                                Phone: '.$row['phone'].'
                                                            </td>
                                                            <td>'.$row['restaurant_name'].'</td>
                                                            <td>'.date('F j, Y', strtotime($row['reservation_date'])).'</td>
                                                            <td>'.date('g:i A', strtotime($row['reservation_time'])).'</td>
                                                            <td>'.$row['guests'].'</td>
                                                            <td>'.($row['special_requests'] ? $row['special_requests'] : '-').'</td>
                                                            <td>
                                                                <form method="post" action="" style="display: inline;">
                                                                    <input type="hidden" name="reservation_id" value="'.$row['id'].'">
                                                                    <select name="status" onchange="this.form.submit()" class="form-control">
                                                                        <option value="pending"'.($row['status'] == 'pending' ? ' selected' : '').'>Pending</option>
                                                                        <option value="confirmed"'.($row['status'] == 'confirmed' ? ' selected' : '').'>Confirmed</option>
                                                                        <option value="cancelled"'.($row['status'] == 'cancelled' ? ' selected' : '').'>Cancelled</option>
                                                                    </select>
                                                                    <input type="hidden" name="update_status" value="1">
                                                                </form>
                                                            </td>
                                                            <td>'.date('F j, Y g:i A', strtotime($row['created_at'])).'</td>
                                                            <td class="action-buttons">
                                                                <a href="?delete='.$row['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this reservation?\')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                          </tr>';
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
        </div>
        
        <footer class="footer"> @2025 - TastyTab (RMS) </footer>
    </div>
    
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html> 