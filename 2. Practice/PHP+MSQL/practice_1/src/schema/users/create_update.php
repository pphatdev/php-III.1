<?php
require_once __DIR__ . '/../../configs/connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

function saveUser($data)
{
    $conn = connect();

    $data = (object)$data;

    // Sanitize input data
    $id = isset($data->id) ? intval($data->id) : null;
    $name = mysqli_real_escape_string($conn, trim($data->name ?? ''));
    $title = mysqli_real_escape_string($conn, trim($data->title ?? ''));
    $email = mysqli_real_escape_string($conn, trim($data->email ?? ''));
    $role = mysqli_real_escape_string($conn, trim($data->role ?? ''));

    // Validate required fields
    if (empty($name) || empty($title) || empty($email) || empty($role)) {
        return [
            'success' => false,
            'message' => 'All fields are required'
        ];
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid email format'
        ];
    }

    try {
        if ($id && $id > 0) {
            // Update existing user
            $checkSql = "SELECT id FROM users WHERE id = ? LIMIT 1";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows === 0) {
                $conn->close();
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }

            // Check if email is already taken by another user
            $emailCheckSql = "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1";
            $emailStmt = $conn->prepare($emailCheckSql);
            $emailStmt->bind_param("si", $email, $id);
            $emailStmt->execute();
            $emailResult = $emailStmt->get_result();

            if ($emailResult->num_rows > 0) {
                $conn->close();
                return [
                    'success' => false,
                    'message' => 'Email already exists for another user',
                    'field' => 'email'
                ];
            }

            // Update user
            $sql = "UPDATE users SET name = ?, title = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            /**
             * Note on bind_param types:
             * s - string
             * i - integer
            */
            $stmt->bind_param("ssssi", $name, $title, $email, $role, $id);

            if ($stmt->execute()) {
                $conn->close();
                return [
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user_id' => $id,
                    'field' => 'email'
                ];
            } else {
                $conn->close();
                return [
                    'success' => false,
                    'message' => 'Error updating user: ' . $stmt->error
                ];
            }
        } else {
            // Check if email already exists
            $emailCheckSql = "SELECT id FROM users WHERE email = ? LIMIT 1";
            $emailStmt = $conn->prepare($emailCheckSql);
            $emailStmt->bind_param("s", $email);
            $emailStmt->execute();
            $emailResult = $emailStmt->get_result();

            if ($emailResult->num_rows > 0) {
                $conn->close();
                return [
                    'success' => false,
                    'message' => 'Email already exists',
                    'field' => 'email'
                ];
            }

            // Create new user
            $sql = "INSERT INTO users (name, title, email, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $title, $email, $role);

            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                $conn->close();
                return [
                    'success' => true,
                    'message' => 'User created successfully',
                    'user_id' => $userId
                ];
            } else {
                $conn->close();
                return [
                    'success' => false,
                    'message' => 'Error creating user: ' . $stmt->error
                ];
            }
        }
    } catch (Exception $e) {
        $conn->close();
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input === null) {
        // Try to get data from $_POST if JSON parsing fails
        $input = $_POST;
    }

    if (empty($input)) {
        echo json_encode([
            'success' => false,
            'message' => 'No data received'
        ]);
        exit;
    }

    $result = saveUser($input);
    echo json_encode($result);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST method allowed'
    ]);
}
