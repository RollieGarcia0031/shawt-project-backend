<?php
require_once __DIR__ . '/../util/Response.php';
require_once __DIR__ . '/../../config/db.php';

class Group {
    public function create($name){
        $conn = db_connect();

        $q = "INSERT INTO groups (name) VALUES (:name) RETURNING id;";
        
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':name', $name);
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