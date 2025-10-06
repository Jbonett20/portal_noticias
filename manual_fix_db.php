<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

$db = Database::getInstance();

echo "<h2>Verificación Manual de Base de Datos</h2>";

// Mostrar TODOS los negocios y sus rutas exactas
$businesses = $db->query("SELECT id, name, logo_path FROM businesses ORDER BY id");

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th><th>Nombre</th><th>Logo Path (exacto)</th><th>URL Resultante</th><th>Acción</th>";
echo "</tr>";

foreach ($businesses as $business) {
    $logoPath = $business['logo_path'];
    $finalUrl = UPLOAD_URL . $logoPath;
    
    echo "<tr>";
    echo "<td>{$business['id']}</td>";
    echo "<td>{$business['name']}</td>";
    echo "<td style='font-family: monospace; background: #f8f8f8;'>'{$logoPath}'</td>";
    echo "<td style='font-family: monospace; font-size: 12px;'>{$finalUrl}</td>";
    
    if (empty($logoPath)) {
        echo "<td>Sin logo</td>";
    } elseif (strpos($logoPath, 'uploads/') === 0) {
        echo "<td><a href='?manual_fix={$business['id']}' style='background: red; color: white; padding: 5px; text-decoration: none;'>CORREGIR</a></td>";
    } else {
        echo "<td style='color: green;'>✅ OK</td>";
    }
    echo "</tr>";
}
echo "</table>";

// Manejar corrección manual
if (isset($_GET['manual_fix'])) {
    $businessId = (int)$_GET['manual_fix'];
    
    // Obtener la ruta actual
    $currentBusiness = $db->queryFirstRow("SELECT logo_path FROM businesses WHERE id = ?", [$businessId]);
    
    if ($currentBusiness) {
        $oldPath = $currentBusiness['logo_path'];
        
        // Si empieza con 'uploads/', quitarlo
        if (strpos($oldPath, 'uploads/') === 0) {
            $newPath = substr($oldPath, 8); // Quitar 'uploads/'
            
            // Actualizar en la base de datos
            $sql = "UPDATE businesses SET logo_path = ? WHERE id = ?";
            $result = $db->query($sql, [$newPath, $businessId]);
            
            if ($result !== false) {
                echo "<div style='background: green; color: white; padding: 10px; margin: 10px;'>";
                echo "✅ Negocio ID {$businessId} corregido:";
                echo "<br>Antes: '{$oldPath}'";
                echo "<br>Después: '{$newPath}'";
                echo "</div>";
                echo "<script>setTimeout(function(){ window.location.href = 'manual_fix_db.php'; }, 2000);</script>";
            } else {
                echo "<div style='background: red; color: white; padding: 10px; margin: 10px;'>";
                echo "❌ Error al actualizar el negocio ID {$businessId}";
                echo "</div>";
            }
        }
    }
}

echo "<hr>";
echo "<h3>Corrección masiva manual</h3>";
echo "<p><a href='?fix_all_manual=1' style='background: blue; color: white; padding: 15px 30px; text-decoration: none; font-size: 16px;'>CORREGIR TODOS LOS PROBLEMÁTICOS</a></p>";

if (isset($_GET['fix_all_manual'])) {
    echo "<h4>Ejecutando corrección masiva...</h4>";
    
    $problemBusinesses = $db->query("SELECT id, name, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'");
    
    foreach ($problemBusinesses as $business) {
        $oldPath = $business['logo_path'];
        $newPath = substr($oldPath, 8); // Quitar 'uploads/'
        
        $sql = "UPDATE businesses SET logo_path = ? WHERE id = ?";
        $result = $db->query($sql, [$newPath, $business['id']]);
        
        if ($result !== false) {
            echo "<p style='color: green;'>✅ {$business['name']}: '{$oldPath}' → '{$newPath}'</p>";
        } else {
            echo "<p style='color: red;'>❌ Error en {$business['name']}</p>";
        }
    }
    
    echo "<p><strong>¡Corrección completada!</strong></p>";
    echo "<script>setTimeout(function(){ window.location.href = 'manual_fix_db.php'; }, 3000);</script>";
}
?>