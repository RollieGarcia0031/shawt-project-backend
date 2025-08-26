<?php

require_once __DIR__ . '/../../src/util/Response.php';
require_once __DIR__ . '/../../src/model/Group.php';
require_once __DIR__ . '/../../src/middleware/Cors.php';
require_once __DIR__ . '/../../src/middleware/Header.php';
require_once __DIR__ . '/../../src/model/Role.php';

header("Content-Type: application/json");
session_start();
corsAllow();
headerMiddleware();

$group = new Group();

$data = json_decode( file_get_contents('php://input'), true );
$name = $data['name'];

try {
    echo $group->create($name);
    exit;
} catch (Exception $e) {
    echo Response::create(false, "Error: " . $e->getMessage(), null, 500);
    exit;
}