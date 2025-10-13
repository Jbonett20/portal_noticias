<?php
class User {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        // Convertir rol string a número
        $role = $data['role'] ?? 'user';
        if ($role === 'admin') {
            $role = 1;
        } elseif ($role === 'editor') {
            $role = 2;
        } elseif ($role === 'redactor') {
            $role = 4;
        } else {
            $role = 3; // usuario básico
        }
        
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'full_name' => $data['full_name'],
            'role' => $role,
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

        // Log temporal para depuración
        $logMsg = date('Y-m-d H:i:s') . " | updateUser: id=$id, role recibido=" . (isset($data['role']) ? $data['role'] : 'NO SET') . "\n";

        // Convertir rol string a número si se actualiza

        if (isset($data['role'])) {
            $role = $data['role'];
            // Si es numérico, usarlo directamente
            if (is_numeric($role)) {
                $role = intval($role);
            } else {
                if ($role === 'admin') {
                    $role = 1;
                } elseif ($role === 'editor') {
                    $role = 2;
                } elseif ($role === 'redactor') {
                    $role = 4;
                } else {
                    $role = 3;
                }
            }
            $updateData['role'] = $role;
            $logMsg .= "role guardado=$role\n";
        }


        // Campos opcionales que se actualizarán si están presentes
        $allowedFields = ['username', 'email', 'full_name', 'is_active', 'business_id'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        // Guardar log en archivo
        file_put_contents(__DIR__ . '/../logs/user_update_log.txt', $logMsg, FILE_APPEND);

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