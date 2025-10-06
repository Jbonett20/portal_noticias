<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

$db = Database::getInstance();

echo "<h2>Corrección FORZADA de rutas</h2>";

// Obtener todos los negocios con rutas problemáticas
$sql = "SELECT id, name, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'";
$businesses = $db->query($sql);

echo "<p>Encontrados " . count($businesses) . " negocios con rutas problemáticas</p>";

foreach ($businesses as $business) {
    $oldPath = $business['logo_path'];
    $newPath = str_replace('uploads/', '', $oldPath);
    
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border-left: 4px solid #007bff;'>";
    echo "<h4>{$business['name']}</h4>";
    echo "<p><strong>Antes:</strong> {$oldPath}</p>";
    echo "<p><strong>Después:</strong> {$newPath}</p>";
    
    // Ejecutar la actualización
    $sql = "UPDATE businesses SET logo_path = ? WHERE id = ?";
    $result = $db->query($sql, [$newPath, $business['id']]);
    
    if ($result !== false) {
        echo "<p style='color: green;'>✅ ACTUALIZADO</p>";
    } else {
        echo "<p style='color: red;'>❌ ERROR</p>";
    }
    echo "</div>";
}

echo "<h3>¡Corrección completada!</h3>";
echo "<p><a href='dashboard' style='background: blue; color: white; padding: 10px 20px; text-decoration: none;'>Ir al Dashboard</a></p>";
echo "<p><a href='.' style='background: green; color: white; padding: 10px 20px; text-decoration: none;'>Ir a la Página Principal</a></p>";
?>