<?php
require_once __DIR__ . '/../../src/util/Response.php';
require_once __DIR__ . '/../../config/db.php';


class Role {
    private string $id, $name, $description;

    public function setName($s){
        $this->name = $s;
    }
    public function setDescription($s){
        $this->description = $s;
    }
    public function create(){
        $name = $this->name;
        $description = $this->description ?? "";
        $conn = db_connect();
        $q = "INSERT INTO roles (name, description) VALUES (:name, :description) RETURNING id;";
        
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        try {
            $stmt->execute();
            $new_id = $conn->lastInsertId();
            return Response::create(true, "Role created successfully", $new_id);
        } catch(PDOException $e) {
            return Response::create(false, "Error: " . $e->getMessage(), null, 500); 
        }
    }

    public function exists($name):bool{
        $conn = db_connect();
        $q = "SELECT id FROM roles WHERE name = :name LIMIT 1;";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':name', $name);
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return true;
            }
        } catch(PDOException $e) {
            echo Response::create(false, "Error: " . $e->getMessage(), null, 500); 
            exit;
        }
        
        return false;
    }
}