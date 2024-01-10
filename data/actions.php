<?php

//Imports
include './db.php';
include './data.php';

header('Content-Type: application/json');

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "POST":
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        switch ($action) {
            case 'delete':
                if (isset($_POST['username'])) {
                    deleteUser($conn, $_POST['username']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Username not provided for deletion']);
                }
                break;
            case 'create':
                registerUser($conn);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
        break;
    case "GET":
        if (isset($_GET['username'])) {
            // Fetch specific user data
            getUserData($conn, $_GET['username']);
        } else {
            // Fetch all users
            getUsers($conn);
        }
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        break;
}

$conn->close();
