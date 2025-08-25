<?php
require_once __DIR__ . '/../../src/util/Response.php';
require_once __DIR__ . '/../../src/model/User.php';
require_once __DIR__ . '/../../src/middleware/Cors.php';
require_once __DIR__ . '/../../src/middleware/Header.php';

corsAllow();
session_start();
headerMiddleware();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo Response::create(false, "Method not allowed", null, 405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'];
$password = $data['password'];

$user = new User();

try {
    $user->setEmail($email);
    $user->setPassword($password);
    echo $user->login();
} catch (Exception $e) {
    echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
    exit;
}