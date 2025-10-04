<?php
require_once 'config/config.php';
require_once 'config/Database.php';

try {
    $db = new Database();
    echo "Conexión exitosa a la base de datos\n";
    
    // Probar una consulta simple
    $result = $db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 1");
    echo "Noticias publicadas: " . $result['count'] . "\n";
    
    $result = $db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 0");
    echo "Noticias en borrador: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>