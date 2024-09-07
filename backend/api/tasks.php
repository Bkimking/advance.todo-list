<?php
require 'db.php';
header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch tasks
        $stmt = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        // Add new task
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO tasks (title, description) VALUES (?, ?)');
        $stmt->execute([$input['title'], $input['description']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        // Update task
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?');
        $stmt->execute([$input['title'], $input['description'], $input['status'], $input['id']]);
        echo json_encode(['status' => 'success']);
        break;

    case 'DELETE':
        // Delete task
        $id = $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['error' => 'Invalid Request']);
        break;
}
?>
