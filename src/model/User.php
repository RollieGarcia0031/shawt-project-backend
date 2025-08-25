<?php
require_once __DIR__ . '/../util/Response.php';
require_once __DIR__ . '/../../config/db.php';

class User {
    private string $name;
    private string $email;
    private string $password;
    private string $first_name;
    private string $last_name;

    public function setName(string $name) {
        $this->name = $name;
    }
    public function setEmail(string $email){
        $this->email = $email;
    }
    public function setPassword(string $password){
        $this->password = $password;
    }
    public function setFirstName(string $first_name){
        $this->first_name = $first_name;
    }
    public function setLastName(string $last_name){
        $this->last_name = $last_name;
    }

    public function register(){
        $conn = db_connect();
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $q = "INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (:name, :email, :password, :first_name, :last_name)";
        
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);

        try {
            $stmt->execute();
            $new_id = $conn->lastInsertId();
            return Response::create(true, "User registered successfully", $new_id);
        } catch(PDOException $e) {
            return Response::create(false, "Error: " . $e->getMessage(), null, 500); 
        }
    }
}