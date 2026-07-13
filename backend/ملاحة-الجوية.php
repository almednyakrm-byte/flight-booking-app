<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all records
    $stmt = $pdo->prepare('SELECT * FROM ملاحة_الجوية');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output records
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Insert record
    $stmt = $pdo->prepare('INSERT INTO ملاحة_الجوية (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output record
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Update record
    $stmt = $pdo->prepare('UPDATE ملاحة_الجوية SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete record
    $stmt = $pdo->prepare('DELETE FROM ملاحة_الجوية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
}

// Output error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}