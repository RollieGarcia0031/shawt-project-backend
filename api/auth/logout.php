<?php
require_once __DIR__ . '/../../src/util/Response.php';
require_once __DIR__ . '/../../src/model/User.php';
require_once __DIR__ . '/../../src/middleware/Cors.php';
require_once __DIR__ . '/../../src/middleware/Header.php';

session_start();
corsAllow();
headerMiddleware();

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo Response::create(false, "Method not allowed", null, 405);
    exit;
}

$user = new User();
try {
    echo $user->logout();
} catch (Exception $e) {
    echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
    exit;
}