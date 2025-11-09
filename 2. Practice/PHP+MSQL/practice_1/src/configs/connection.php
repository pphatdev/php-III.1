<?php

require_once __DIR__ ."/../../vendor/autoload.php";
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__."/../../");
$dotenv->load();


function connect() {
    // Database configuration
    $dbHost = $_ENV['DB_HOST'] ?? "localhost"; # 127.0.0.1
    $dbName = $_ENV['DB_NAME'] ?? "demo";
    $dbUser = $_ENV['DB_USER'] ?? "root";
    $dbPass = $_ENV['DB_PASS'] ?? "";
    $dbPort = (int)($_ENV['DB_PORT'] ?? 3306);
    $connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    return $connection;
}

if (connect()->connect_error) {
    die("Connection failed: " . connect()->connect_error);
}