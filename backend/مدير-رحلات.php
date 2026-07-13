<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle CRUD operations
if (isset($input['action'])) {
    switch ($input['action']) {
        case 'get':
            // Get all records
            $stmt = $pdo->prepare('SELECT * FROM مدير_رحلات');
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json');
            echo json_encode($records);
            break;
        case 'create':
            // Validate input data
            if (!isset($input['name']) || !isset($input['description'])) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $name = htmlspecialchars($input['name']);
            $description = htmlspecialchars($input['description']);

            // Insert new record
            $stmt = $pdo->prepare('INSERT INTO مدير_رحلات (name, description) VALUES (:name, :description)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            header('HTTP/1.1 201 Created');
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record created successfully'));
            break;
        case 'update':
            // Validate input data
            if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $id = htmlspecialchars($input['id']);
            $name = htmlspecialchars($input['name']);
            $description = htmlspecialchars($input['description']);

            // Update existing record
            $stmt = $pdo->prepare('UPDATE مدير_رحلات SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record updated successfully'));
            break;
        case 'delete':
            // Validate input data
            if (!isset($input['id'])) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $id = htmlspecialchars($input['id']);

            // Delete existing record
            $stmt = $pdo->prepare('DELETE FROM مدير_رحلات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record deleted successfully'));
            break;
        default:
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array('error' => 'Invalid request'));
            break;
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'Invalid request'));
}