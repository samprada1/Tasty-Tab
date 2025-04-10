<?php
include("connection/connect.php");

function getSimilarFoods($food_id, $limit = 5) {
    global $db;
    
    $stmt = $db->prepare("
        SELECT d.*, r.similarity_score 
        FROM dishes d
        JOIN food_relationships r ON d.d_id = r.related_food_id
        WHERE r.food_id = ?
        ORDER BY r.similarity_score DESC
        LIMIT ?
    ");
    
    $stmt->bind_param("ii", $food_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $similar_foods = [];
    while($row = $result->fetch_assoc()) {
        $similar_foods[] = $row;
    }
    
    $stmt->close();
    return $similar_foods;
}

function calculateFoodSimilarity($food1, $food2) {
    // Calculate similarity based on:
    // 1. Category
    // 2. Price range
    // 3. Keywords in description
    // 4. Restaurant type
    
    $similarity = 0;
    
    // Category similarity (40% weight)
    if($food1['c_id'] == $food2['c_id']) {
        $similarity += 0.4;
    }
    
    // Price range similarity (30% weight)
    $price_diff = abs($food1['price'] - $food2['price']);
    $max_price = max($food1['price'], $food2['price']);
    if($max_price > 0) {
        $price_similarity = 1 - ($price_diff / $max_price);
        $similarity += $price_similarity * 0.3;
    }
    
    // Description similarity (20% weight)
    $words1 = explode(' ', strtolower($food1['about']));
    $words2 = explode(' ', strtolower($food2['about']));
    $common_words = array_intersect($words1, $words2);
    $description_similarity = count($common_words) / max(count($words1), count($words2));
    $similarity += $description_similarity * 0.2;
    
    // Restaurant type similarity (10% weight)
    if($food1['rs_id'] == $food2['rs_id']) {
        $similarity += 0.1;
    }
    
    return $similarity;
}

function updateFoodRelationships() {
    global $db;
    
    // Get all dishes
    $dishes_query = "SELECT * FROM dishes";
    $dishes_result = $db->query($dishes_query);
    $dishes = [];
    
    while($row = $dishes_result->fetch_assoc()) {
        $dishes[] = $row;
    }
    
    // Clear existing relationships
    $db->query("TRUNCATE TABLE food_relationships");
    
    // Calculate and store new relationships
    $stmt = $db->prepare("INSERT INTO food_relationships (food_id, related_food_id, similarity_score) VALUES (?, ?, ?)");
    
    foreach($dishes as $food1) {
        foreach($dishes as $food2) {
            if($food1['d_id'] != $food2['d_id']) {
                $similarity = calculateFoodSimilarity($food1, $food2);
                if($similarity > 0.3) { // Only store significant relationships
                    $stmt->bind_param("iid", $food1['d_id'], $food2['d_id'], $similarity);
                    $stmt->execute();
                }
            }
        }
    }
    
    $stmt->close();
}
?> 