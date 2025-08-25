<?php
require_once __DIR__ . '/../util/Response.php';
require_once __DIR__ . '/../../src/model/User.php';
require_once __DIR__ . '/../middleware/Cors.php';

corsAllow();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo Response::create(false, "Method not allowed", null, 405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$password = $data['password'];
$email = $data['email'];
$first_name = $data['first_name'];
$last_name = $data['last_name'];

$user = new User();

try {
    $user->setName($name);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setFirstName($first_name);
    $user->setLastName($last_name);
} catch (Exception $e) {
    echo Response::create(false, "Error: " . $e->getMessage(), null, 400);
    exit;
}

echo $user->register();