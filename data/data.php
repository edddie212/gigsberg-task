
<?php

include './db.php';
include './helpers.php';

//Get all users
function getUsers($conn) {
    $sql = "SELECT Username, Email FROM users";
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $users]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No users found']);
    }

}

//Insert user to DB
function registerUser($conn) {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['birthdate']) && isset($_POST['phone']) && isset($_POST['url'])) {
        $username = validate($_POST['username']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $birthdate = validate($_POST['birthdate']);
        $phone = validate($_POST['phone']);
        $url = validate($_POST['url']);

        // Combined check for username and email
        $checkStmt = $conn->prepare("SELECT Username, Email FROM users WHERE Username = ? OR Email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $existingUsername = false;
        $existingEmail = false;

        while ($row = $result->fetch_assoc()) {
            if ($row['Username'] === $username) {
                $existingUsername = true;
            }
            if ($row['Email'] === $email) {
                $existingEmail = true;
            }
        }

        if ($existingUsername && $existingEmail) {
            echo json_encode(['success' => false, 'message' => 'Both username and email already exist']);
            return;
        } elseif ($existingUsername) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            return;
        } elseif ($existingEmail) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (Username, Email, Password, Birthdate, Phone_number, URL) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $password, $birthdate, $phone, $url);

        // Execute and respond
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User registered successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    }
}


//Delete a User
function deleteUser($conn, $username) {
    $username = validate($_POST['username']);
    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);

    // Execute and respond
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user: ' . $stmt->error]);
    }

}

//Get single userData
function getUserData($conn, $username) {
    $username = validate($username);
    // SQL query to fetch user data
    $sql = "SELECT Username, Email, Birthdate, Phone_number, URL FROM users WHERE Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $userData]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}


