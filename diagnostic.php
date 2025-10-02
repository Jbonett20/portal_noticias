<?php
require_once 'config/config.php';

echo "<h2>🔧 Diagnóstico del Sistema</h2>";

// 1. Verificar conexión a base de datos
try {
    require_once 'config/Database.php';
    $db = Database::getInstance();
    echo "<p>✅ <strong>Base de datos:</strong> Conexión exitosa</p>";
    
    // Probar consulta simple
    $result = $db->fetch("SELECT COUNT(*) as count FROM users");
    echo "<p>✅ <strong>Consultas:</strong> Funcionando - {$result['count']} usuarios encontrados</p>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Base de datos:</strong> Error - " . $e->getMessage() . "</p>";
}

// 2. Verificar sesiones
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p>✅ <strong>Sesiones:</strong> Funcionando</p>";
} else {
    echo "<p>❌ <strong>Sesiones:</strong> No activas</p>";
}

// 3. Verificar si hay usuario logueado
if (isLoggedIn()) {
    $user = getCurrentUser();
    echo "<p>✅ <strong>Usuario logueado:</strong> " . $user['username'] . " (" . $user['role'] . ")</p>";
} else {
    echo "<p>⚠️ <strong>Usuario:</strong> No hay sesión activa - <a href='login'>Hacer login</a></p>";
}

// 4. Verificar controladores
$controllers = ['AdminController', 'DashboardController', 'AuthController'];
foreach ($controllers as $controller) {
    $file = "controllers/{$controller}.php";
    if (file_exists($file)) {
        echo "<p>✅ <strong>{$controller}:</strong> Archivo existe</p>";
    } else {
        echo "<p>❌ <strong>{$controller}:</strong> Archivo no encontrado</p>";
    }
}

// 5. Verificar vistas críticas
$views = ['admin/index', 'admin/users', 'admin/create-user', 'admin/edit-user'];
foreach ($views as $view) {
    $file = "views/{$view}.php";
    if (file_exists($file)) {
        echo "<p>✅ <strong>Vista {$view}:</strong> Existe</p>";
    } else {
        echo "<p>❌ <strong>Vista {$view}:</strong> No encontrada</p>";
    }
}

echo "<hr>";
echo "<h3>🧪 Enlaces de Prueba</h3>";
echo "<ul>";
echo "<li><a href='./' target='_blank'>Página Principal</a></li>";
echo "<li><a href='login' target='_blank'>Login</a></li>";
echo "<li><a href='admin' target='_blank'>Panel Admin</a></li>";
echo "<li><a href='dashboard' target='_blank'>Dashboard</a></li>";
echo "</ul>";
?>