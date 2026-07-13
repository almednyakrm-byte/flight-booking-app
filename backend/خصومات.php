<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON or POST request
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Define database table name
$tableName = 'خصومات';

// Handle GET request to retrieve all records
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Prepare SQL query to retrieve all records
    $stmt = $pdo->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return HTTP response with application/json Content-Type header
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// Handle GET request to retrieve a single record by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Validate input ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // Prepare SQL query to retrieve a single record by ID
    $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return HTTP response with application/json Content-Type header
    header('Content-Type: application/json');
    echo json_encode($record);
    exit;
}

// Handle POST request to create a new record
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description']) || !isset($inputData['discount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
    $discount = filter_var($inputData['discount'], FILTER_SANITIZE_NUMBER_INT);
    
    // Prepare SQL query to create a new record
    $stmt = $pdo->prepare("INSERT INTO $tableName (name, description, discount) VALUES (:name, :description, :discount)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':discount', $discount);
    $stmt->execute();
    
    // Return HTTP response with application/json Content-Type header
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record created successfully'));
    exit;
}

// Handle PUT request to update an existing record
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate input ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description']) || !isset($inputData['discount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required fields'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
    $discount = filter_var($inputData['discount'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin to perform update operation
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }
    
    // Prepare SQL query to update an existing record
    $stmt = $pdo->prepare("UPDATE $tableName SET name = :name, description = :description, discount = :discount WHERE id = :id");
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':discount', $discount);
    $stmt->execute();
    
    // Return HTTP response with application/json Content-Type header
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record updated successfully'));
    exit;
}

// Handle DELETE request to delete a record
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate input ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }
    
    // Check if user is admin to perform delete operation
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }
    
    // Prepare SQL query to delete a record
    $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    
    // Return HTTP response with application/json Content-Type header
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
    exit;
}

// Return HTTP response with 404 status code if no action is specified
http_response_code(404);
echo json_encode(array('error' => 'Not found'));
exit;

?>