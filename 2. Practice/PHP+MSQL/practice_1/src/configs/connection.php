<?php

function connect() {
    // Database configuration

    $dbHost = "localhost"; # 127.0.0.1
    $dbName = "demo";
    $dbUser = "root";
    $dbPass = "";
    $dbPort = "3306";
    $connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    return $connection;
}

if (connect()->connect_error) {
    die("Connection failed: " . connect()->connect_error);
}