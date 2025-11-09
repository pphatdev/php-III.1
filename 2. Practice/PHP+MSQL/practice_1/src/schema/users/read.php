<?php

require_once __DIR__ . '/../../configs/connection.php';

function getUsers()
{
    $conn = connect();
    try {
        $conn->set_charset("utf8mb4");
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        $conn->close();
        return $users;
    } catch (Exception $e) {
        $conn->close();
        return [];
    }
}
