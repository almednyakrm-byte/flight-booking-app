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
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    // SQL query structure
    $query = 'SELECT * FROM دوائر_رحلات';
    $params = [];

    // Add condition if id is provided
    if ($id) {
        $query .= ' WHERE id = :id';
        $params[':id'] = $id;
    }

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Output processing
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = isset($data['name']) ? trim($data['name']) : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    // Check for required fields
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $query = 'INSERT INTO دوائر_رحلات (name, description) VALUES (:name, :description)';
    $params = [
        ':name' => $name,
        ':description' => $description,
    ];

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Output processing
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

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = isset($data['id']) ? (int) $data['id'] : null;
    $name = isset($data['name']) ? trim($data['name']) : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    // Check for required fields
    if (!$id || !$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $query = 'UPDATE دوائر_رحلات SET name = :name, description = :description WHERE id = :id';
    $params = [
        ':id' => $id,
        ':name' => $name,
        ':description' => $description,
    ];

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Output processing
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

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = isset($data['id']) ? (int) $data['id'] : null;

    // Check for required fields
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $query = 'DELETE FROM دوائر_رحلات WHERE id = :id';
    $params = [
        ':id' => $id,
    ];

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Output processing
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