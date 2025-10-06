<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Corrección de rutas de logos duplicadas</h2>\n";
    
    // Buscar negocios con rutas duplicadas
    $businesses = $db->query("SELECT id, name, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'");
    
    echo "<p>Encontrados " . count($businesses) . " negocios con rutas duplicadas</p>\n";
    
    foreach ($businesses as $business) {
        $oldPath = $business['logo_path'];
        $newPath = str_replace('uploads/', '', $oldPath);
        
        echo "<p><strong>{$business['name']}</strong></p>\n";
        echo "<p>Antes: {$oldPath}</p>\n";
        echo "<p>Después: {$newPath}</p>\n";
        
        // Actualizar en la base de datos
        $result = $db->update('businesses', ['logo_path' => $newPath], ['id' => $business['id']]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Actualizado correctamente</p>\n";
        } else {
            echo "<p style='color: red;'>❌ Error al actualizar</p>\n";
        }
        echo "<hr>\n";
    }
    
    echo "<h3>¡Corrección completada!</h3>\n";
    echo "<p><a href='dashboard'>Volver al dashboard</a> para verificar que las imágenes ahora se ven correctamente.</p>\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>