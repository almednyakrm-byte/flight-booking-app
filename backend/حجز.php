<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM حجز WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Process output
    if ($result === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

// Handle POST requests
elseif ($method == 'POST') {
    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $required_fields = ['name', 'email', 'phone'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO حجز (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->execute();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE حجز SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM حجز WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}

// Handle other requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}