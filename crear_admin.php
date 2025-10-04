<?php
require_once 'config/config.php';
require_once 'config/Database.php';

try {
    $db = new Database();
    
    // Verificar si ya existe un admin
    $existingAdmin = $db->fetch("SELECT * FROM users WHERE role = 1 OR role = 'admin'");
    
    if ($existingAdmin) {
        echo "✅ Ya existe un usuario administrador:\n";
        echo "   Username: " . $existingAdmin['username'] . "\n";
        echo "   Email: " . $existingAdmin['email'] . "\n";
        echo "   Rol: " . $existingAdmin['role'] . "\n";
    } else {
        // Crear usuario administrador
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@portal.com',
            'full_name' => 'Administrador Principal',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $userId = $db->insert('users', $adminData);
        
        echo "✅ Usuario administrador creado exitosamente!\n";
        echo "   ID: $userId\n";
        echo "   Username: admin\n";
        echo "   Password: admin123\n";
        echo "   Email: admin@portal.com\n";
    }
    
    echo "\n🌐 Puedes acceder al panel en:\n";
    echo "   http://localhost/clone/portal_noticias/admin\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>