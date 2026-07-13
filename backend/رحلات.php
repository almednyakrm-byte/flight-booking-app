<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all trips
    $stmt = $pdo->prepare('SELECT * FROM رحلات');
    $stmt->execute();
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return trips
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($trips);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['name']) || !isset($input['description']) || !isset($input['date'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($input['date'], FILTER_SANITIZE_STRING);

    // Insert trip
    $stmt = $pdo->prepare('INSERT INTO رحلات (name, description, date) VALUES (:name, :description, :date)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Trip created successfully'));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description']) || !isset($input['date'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($input['date'], FILTER_SANITIZE_STRING);

    // Update trip
    $stmt = $pdo->prepare('UPDATE رحلات SET name = :name, description = :description, date = :date WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Trip updated successfully'));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete trip
    $stmt = $pdo->prepare('DELETE FROM رحلات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Trip deleted successfully'));
    exit;
}