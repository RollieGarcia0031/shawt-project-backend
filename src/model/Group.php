<?php
require_once __DIR__ . '/../util/Response.php';
require_once __DIR__ . '/../../config/db.php';

class Group {
    public function create($name, $description) {
        $conn = db_connect();

        $user_id = $_SESSION['user_id'];

        $q = "INSERT INTO groups (name, created_by, description) VALUES (:name, :uid, :description) RETURNING id;";
        
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':description', $description);
        
        try {
            $stmt->execute();
            $new_id = $conn->lastInsertId();
            return Response::create(true, "Group created successfully", $new_id);
        } catch(PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate key value') !== false) {
                return Response::create(false, "Error: Group $name already exists", null, 400);
            }
            return Response::create(false, "Error: " . $e->getMessage(), null, 500); 
        }
    }
}