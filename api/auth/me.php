<?php
require_once __DIR__ . "/../../src/util/Response.php";
require_once __DIR__ . "/../../src/model/User.php";
require_once __DIR__ . "/../../src/middleware/Cors.php";
require_once __DIR__ . "/../../src/middleware/Header.php";

session_start();

corsAllow();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo Response::create(false, "Method not allowed", null, 405);
    exit;
}

$user = new User();
$userId = $_SESSION['user_id'] ?? null;

if(!$userId) {
    echo Response::create(false, "User not logged in", null, 401);
    exit;
}

try {
    echo $user->getInfo($userId);
    exit;
} catch (Exception $e) {
    echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
    exit;
}