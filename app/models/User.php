<?php
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function register($data) {
        $this->db->query('INSERT INTO usuarios (name, email, password) VALUES (:name, :email, :password)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function login($email, $password) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($row && password_verify($password, $row->password)) {
            return $row;
        } else {
            return false;
        }
    }
    
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getUserById($id) {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
}
?>