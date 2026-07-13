<?php

require_once 'db.php';

// Get user data from session
$userData = $_SESSION['userData'];

// Check if user is logged in
if (!isset($userData['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/get' => 'get',
    '/create' => 'create',
    '/update/:id' => 'update',
    '/delete/:id' => 'delete',
];

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . preg_quote($pattern, '/') . '$/', $route, $matches)) {
        $route = $matches[0];
        break;
    }
}

// Handle route
switch ($route) {
    case '/get':
        get();
        break;
    case '/create':
        create();
        break;
    case '/update/:id':
        update();
        break;
    case '/delete/:id':
        delete();
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit;
}

// Define functions
function get() {
    global $pdo, $userData;

    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare query
    $stmt = $pdo->prepare('SELECT * FROM ملاحة_الرحلات');

    // Execute query
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function create() {
    global $pdo, $userData;

    // Validate input
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input
    $name = $pdo->quote($inputData['name']);
    $description = $pdo->quote($inputData['description']);

    // Prepare query
    $stmt = $pdo->prepare('INSERT INTO ملاحة_الرحلات (name, description) VALUES (:name, :description)');

    // Execute query
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

function update() {
    global $pdo, $userData;

    // Get id from route
    $id = (int) $_GET['id'];

    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input
    $name = $pdo->quote($inputData['name']);
    $description = $pdo->quote($inputData['description']);

    // Prepare query
    $stmt = $pdo->prepare('UPDATE ملاحة_الرحلات SET name = :name, description = :description WHERE id = :id');

    // Execute query
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

function delete() {
    global $pdo, $userData;

    // Get id from route
    $id = (int) $_GET['id'];

    // Check if user is admin
    if ($userData['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare query
    $stmt = $pdo->prepare('DELETE FROM ملاحة_الرحلات WHERE id = :id');

    // Execute query
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}