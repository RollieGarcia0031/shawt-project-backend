<?php
include_once __DIR__ . '/../util/Response.php';

function db_connect() {
    $user = 'postgres';
    $password = '123456';
    $db = 'final_proj_dev';
    $host = 'localhost';
    $port = '5433';
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    } catch (PDOException $e) {
        echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
        exit();
    }
}