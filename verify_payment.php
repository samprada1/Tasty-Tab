<?php
session_start();
include("connection/connect.php");

// Debug: Log session data
error_log("Session data: " . print_r($_SESSION, true));
echo "<script>console.log('Payment verification started');</script>";

// Check if this is a return from Khalti
if (isset($_GET['pidx'])) {
    $pidx = $_GET['pidx'];
    error_log("Received pidx: " . $pidx);
    echo "<script>console.log('Received pidx: " . $pidx . "');</script>";
    
    // Verify session data exists
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["cart_item"])) {
        error_log("Missing session data - user_id or cart_item not set");
        echo "<script>console.error('Missing session data - user_id or cart_item not set');</script>";
        die("Session data missing. Please try again.");
    }
    
    // Use test environment URL for development
    $url = "https://dev.khalti.com/api/v2/epayment/lookup/";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['pidx' => $pidx]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // Use test secret key for development
    $headers = [
        'Authorization: Key 3ed06e79498c4d13ae218508c6b6d659',
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log("Khalti API Response: " . $response);
    error_log("Status Code: " . $status_code);
    echo "<script>console.log('Khalti API Response Status: " . $status_code . "');</script>";
    echo "<script>console.log('Khalti API Response: " . addslashes($response) . "');</script>";

    if ($status_code == 200) {
        $response_data = json_decode($response, true);
        
        if ($response_data['status'] === 'Completed') {
            echo "<script>console.log('Payment status: Completed');</script>";
            // Start transaction for database operations
            mysqli_begin_transaction($db);
            
            try {
                // Debug: Log cart items
                error_log("Cart items: " . print_r($_SESSION["cart_item"], true));
                echo "<script>console.log('Processing cart items...');</script>";
                
                // Insert order details for each item
                foreach ($_SESSION["cart_item"] as $item) {
                    $SQL = "INSERT INTO users_orders(u_id, title, quantity, price, payment_method, payment_status, status) 
                            VALUES ('".$_SESSION["user_id"]."', '".$item["title"]."', '".$item["quantity"]."', 
                                    '".$item["price"]."', 'khalti', 'paid', 'in process')";
                    
                    error_log("Executing SQL: " . $SQL);
                    echo "<script>console.log('Executing SQL for item: " . addslashes($item["title"]) . "');</script>";
                    
                    if (!mysqli_query($db, $SQL)) {
                        throw new Exception("Failed to insert order: " . mysqli_error($db));
                    }
                    
                    error_log("Successfully inserted order for item: " . $item["title"]);
                    echo "<script>console.log('Successfully inserted order for item: " . addslashes($item["title"]) . "');</script>";
                }

                // Clear the cart
                unset($_SESSION["cart_item"]);
                
                // Commit the transaction
                mysqli_commit($db);
                error_log("Transaction committed successfully");
                echo "<script>console.log('Transaction committed successfully');</script>";
                echo "<script>console.log('Redirecting to orders page...');</script>";
                
                // Redirect to orders page
                header('Location: your_orders.php');
                exit;
            } catch (Exception $e) {
                // Rollback the transaction on error
                mysqli_rollback($db);
                error_log("Error in transaction: " . $e->getMessage());
                echo "<script>console.error('Error in transaction: " . addslashes($e->getMessage()) . "');</script>";
                echo "Error: " . $e->getMessage();
                exit;
            }
        } else {
            // Payment not completed
            error_log("Payment not completed. Status: " . $response_data['status']);
            echo "<script>console.error('Payment not completed. Status: " . $response_data['status'] . "');</script>";
            echo "Payment not completed. Please try again.";
            exit;
        }
    } else {
        error_log("Payment verification failed. Status code: " . $status_code);
        echo "<script>console.error('Payment verification failed. Status code: " . $status_code . "');</script>";
        echo "Payment verification failed. Please try again.";
        exit;
    }
} else {
    // Handle API verification requests
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $pidx = $data['pidx'];

        // Use test environment URL for development
        $url = "https://dev.khalti.com/api/v2/epayment/lookup/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['pidx' => $pidx]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $headers = [
            'Authorization: Key 3ed06e79498c4d13ae218508c6b6d659',
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status_code == 200) {
            $response_data = json_decode($response, true);
            echo json_encode($response_data);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Payment verification failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}
?> 