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

    public function login(){
        $conn = db_connect();

        $q = "SELECT id, username, email, password_hash FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':email', $this->email);

        try{
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $password_hash = $row['password_hash'] ?? "";
            $password_input = $this->password;
            $verified = password_verify($password_input, $password_hash);
            
            if(!$row){
                return Response::create(false, "User not found", null, 404);
            }
            if ($verified) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];

                $row['password_hash'] = "*******";
                return Response::create(true, "User logged in successfully", $row);
            }
            return Response::create(false, "Incorrect password", null, 401);
            
        } catch(PDOException $e) {
            return Response::create(false, "Error: " . $e->getMessage(), null, 500);
        }

    }

    public function getInfo(){
        $conn = db_connect();
        $q = "SELECT username, email, first_name, last_name FROM users WHERE id = :id LIMIT 1";

        $stmt = $conn->prepare($q);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return Response::create(true, "User info", $row);
        } catch (PDOException $e){
            return Response::create(false, "Error: " . $e->getMessage(), null, 500);
        }
    }

    public function logout(){
        session_destroy();
        return Response::create(true, "User logged out successfully", null);
    }

    public function exists(){
        $id = $_SESSION['user_id'] ?? null;
        if ($id) {
            return true;
        }
        return false;
    }

    public function updateRole($role){
        $conn = db_connect();

        //check if role exists
        $stment = $conn->prepare("SELECT id FROM roles WHERE name = :role LIMIT 1;");
        $stment->bindParam(':role', $role);
        $stment->execute();
        $row = $stment->fetch(PDO::FETCH_ASSOC);

        if(!$row){
            //role not found
            return Response::create(false, "Role not found", null, 404);
        }

        $user_id = $_SESSION['user_id'];

        $stment = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
        $stment->bindParam(':user_id', $user_id);
        $stment->bindParam(':role_id', $row['id']);
        
        try {
            $stment->execute();
            return Response::create(true, "Role updated successfully", null);
        } catch (PDOException $e){
            return Response::create(false, "Error: " . $e->getMessage(), null, 500);
        }
    }
}