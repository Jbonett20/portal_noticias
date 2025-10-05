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
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Campos opcionales que se actualizarán si están presentes
        $allowedFields = ['username', 'email', 'full_name', 'role', 'is_active', 'business_id'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        // Solo actualizar contraseña si se proporciona
        if (isset($data['password_hash'])) {
            $updateData['password_hash'] = $data['password_hash'];
        }
        
        return $this->db->update('users', $updateData, 'id = ?', $id);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', $id);
    }
    
    public function count() {
        return $this->db->count('users');
    }
    
    public function findAll() {
        return $this->db->fetchAll("SELECT * FROM users ORDER BY created_at DESC");
    }
}
?>