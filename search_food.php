<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure proper content type
header('Content-Type: application/json');

try {
    // Include database connection
    include("connection/connect.php");
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }

    if(isset($_POST['query'])) {
        $query = mysqli_real_escape_string($db, $_POST['query']);
        
        // Debug the query
        error_log("Search query: " . $query);
        
        $sql = "SELECT d.*, r.rs_id, r.title as restaurant_name 
                FROM dishes d 
                JOIN restaurant r ON d.rs_id = r.rs_id 
                WHERE d.title LIKE '%$query%' 
                OR d.slogan LIKE '%$query%'
                OR r.title LIKE '%$query%'
                ORDER BY d.title ASC
                LIMIT 10";
                
        // Debug the SQL
        error_log("SQL Query: " . $sql);
        
        $result = mysqli_query($db, $sql);
        
        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($db));
        }
        
        $dishes = array();
        
        while($row = mysqli_fetch_assoc($result)) {
            $dishes[] = array(
                'title' => htmlspecialchars($row['title']),
                'slogan' => htmlspecialchars($row['slogan']),
                'rs_id' => (int)$row['rs_id'],
                'restaurant_name' => htmlspecialchars($row['restaurant_name']),
                'price' => (float)$row['price']
            );
        }
        
        // Debug the results
        error_log("Found " . count($dishes) . " results");
        
        echo json_encode($dishes);
    } else {
        echo json_encode(array('error' => 'No search query provided'));
    }
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(array('error' => 'Server error: ' . $e->getMessage()));
}
?> 