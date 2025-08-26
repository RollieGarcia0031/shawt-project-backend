<?php
require_once __DIR__ . '/../../src/util/Response.php';
require_once __DIR__ . '/../../src/model/User.php';
require_once __DIR__ . '/../../src/middleware/Cors.php';
require_once __DIR__ . '/../../src/middleware/Header.php';
require_once __DIR__ . '/../../src/model/Role.php';

header("Content-Type: application/json");
session_start();
corsAllow();
headerMiddleware();

$user = new User();

$data = json_decode( file_get_contents('php://input'), true );
$role = $data['role'] ?? null;
$group = $data['group'] ?? null;

if(!$user->exists()) {
    echo Response::create(false, "User not logged in", null, 401);
    exit;
}

if($role){
    try {
        echo $user->updateRole($role);
        exit;
    } catch (Exception $e) {
        echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
        exit;
    }
}