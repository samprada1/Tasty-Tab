<?php
session_start();
include("connection/connect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Calculate total amount
    $item_total = 0;
    foreach ($_SESSION["cart_item"] as $item) {
        $item_total += ($item["price"] * $item["quantity"]);
    }

    // Prepare the payload
    $payload = [
        'return_url' => 'http://localhost/Tasty%20Tab/verify_payment.php',
        'website_url' => 'http://localhost/Tasty%20Tab',
        'amount' => $item_total * 100, // Convert to paisa
        'purchase_order_id' => 'ORDER_' . $_SESSION["user_id"] . '_' . time(),
        'purchase_order_name' => 'TastyTab Food Order',
        'customer_info' => [
            'name' => $_SESSION["user_name"] ?? 'Customer',
            'email' => $_SESSION["user_email"] ?? '',
            'phone' => $_SESSION["user_phone"] ?? ''
        ],
        'product_details' => []
    ];

    // Add items to product details
    foreach ($_SESSION["cart_item"] as $item) {
        $payload['product_details'][] = [
            'identity' => $item["d_id"],
            'name' => $item["title"],
            'total_price' => $item["price"] * $item["quantity"] * 100,
            'quantity' => $item["quantity"],
            'unit_price' => $item["price"] * 100
        ];
    }

    // Call Khalti's initiate payment API
    $url = "https://dev.khalti.com/api/v2/epayment/initiate/";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
        echo json_encode(['status' => 'error', 'message' => 'Payment initiation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?> 