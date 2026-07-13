<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/malahta' => ['GET', 'GET ALL'],
    '/malahta' => ['POST', 'CREATE'],
    '/malahta/{id}' => ['GET', 'GET BY ID'],
    '/malahta/{id}' => ['PUT', 'UPDATE'],
    '/malahta/{id}' => ['DELETE', 'DELETE']
];

// Get current route
$route = explode('/', $_SERVER['REQUEST_URI']);
$route = $route[count($route) - 1];

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Validate route
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

// Get method and action
list($method, $action) = $routes[$route];

// Process request
if ($method == 'GET') {
    // Get all malahta
    if ($action == 'GET ALL') {
        try {
            $stmt = $pdo->prepare('SELECT * FROM malahta');
            $stmt->execute();
            $malahta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($malahta);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    }
    // Get malahta by id
    elseif ($action == 'GET BY ID') {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare('SELECT * FROM malahta WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $malahta = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$malahta) {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found']);
            } else {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($malahta);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    }
} elseif ($method == 'POST') {
    // Create malahta
    try {
        $name = trim($input['name']);
        $description = trim($input['description']);
        $stmt = $pdo->prepare('INSERT INTO malahta (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Malahta created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($method == 'PUT') {
    // Update malahta
    $id = $_GET['id'];
    try {
        $name = trim($input['name']);
        $description = trim($input['description']);
        $stmt = $pdo->prepare('UPDATE malahta SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Malahta updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($method == 'DELETE') {
    // Delete malahta
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare('DELETE FROM malahta WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Malahta deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}