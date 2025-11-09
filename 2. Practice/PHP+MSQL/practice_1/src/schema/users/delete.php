<?php
require_once __DIR__ . '/../../configs/connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

function deleteUser($userId)
{
    $conn = connect();

    // Sanitize input
    $userId = intval($userId);

    if ($userId <= 0) {
        return [
            'success' => false,
            'message' => 'Invalid user ID'
        ];
    }

    try {
        // Check if user exists
        $checkSql = "SELECT id, name FROM users WHERE id = ? LIMIT 1";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            $conn->close();
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        $user = $result->fetch_assoc();

        // Delete user
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $conn->close();
            return [
                'success' => true,
                'message' => 'User "' . $user['name'] . '" deleted successfully'
            ];
        } else {
            $conn->close();
            return [
                'success' => false,
                'message' => 'Error deleting user: ' . $stmt->error
            ];
        }
    } catch (Exception $e) {
        $conn->close();
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input === null) {
        $input = $_POST;
    }

    $userId = $input['id'] ?? $input['user_id'] ?? null;

    if (empty($userId)) {
        echo json_encode([
            'success' => false,
            'message' => 'User ID is required'
        ]);
        exit;
    }

    $result = deleteUser($userId);
    echo json_encode($result);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST and DELETE methods allowed'
    ]);
}
