<?php
class User {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'full_name' => $data['full_name'],
            'role' => $data['role'] ?? 2,
            'business_id' => $data['business_id'] ?? null
        ];
        
        return $this->db->insert('users', $userData);
    }
    
    public function findById($id) {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public function findByUsername($username) {
        return $this->db->fetch("SELECT * FROM users WHERE username = ?", [$username]);
    }
    
    public function findByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }
    
    public function authenticate($username, $password) {
        $user = $this->findByUsername($username);
        
        if ($user && password_verify($password, $user['password_hash']) && $user['is_active']) {
            return $user;
        }
        
        return false;
    }
    
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT id, username, email, full_name, role, is_active, created_at 
                FROM users ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }
        
        return $this->db->fetchAll($sql);
    }
    
    public function update($id, $data) {
        $updateData = [
            'email' => $data['email'],
            'full_name' => $data['full_name'],
            'role' => $data['role'],
            'business_id' => $data['business_id'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Solo actualizar contraseña si se proporciona
        if (isset($data['password'])) {
            $updateData['password_hash'] = $data['password'];
        }
        
        return $this->db->update('users', $updateData, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', $id);
    }
    
    public function count() {
        return $this->db->count('users');
    }
}
?>