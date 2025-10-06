<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

$db = Database::getInstance();

echo "<h2>Estado actual de las rutas de logos</h2>";

$businesses = $db->query("SELECT id, name, logo_path FROM businesses WHERE logo_path IS NOT NULL");

foreach ($businesses as $business) {
    $currentPath = $business['logo_path'];
    $hasUploadsPrefix = strpos($currentPath, 'uploads/') === 0;
    
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<h4>{$business['name']} (ID: {$business['id']})</h4>";
    echo "<p><strong>Ruta actual:</strong> '{$currentPath}'</p>";
    echo "<p><strong>¿Tiene 'uploads/' al inicio?:</strong> " . ($hasUploadsPrefix ? '❌ SÍ (problemático)' : '✅ NO (correcto)') . "</p>";
    
    if ($hasUploadsPrefix) {
        echo "<p><strong>URL problemática:</strong> " . UPLOAD_URL . $currentPath . "</p>";
        echo "<p><a href='?fix={$business['id']}' style='background: red; color: white; padding: 5px 10px; text-decoration: none;'>Corregir este registro</a></p>";
    } else {
        echo "<p><strong>URL correcta:</strong> " . UPLOAD_URL . $currentPath . "</p>";
        echo "<img src='" . UPLOAD_URL . $currentPath . "' style='max-width: 100px; max-height: 100px;' alt='Preview'>";
    }
    echo "</div>";
}

// Manejar corrección individual
if (isset($_GET['fix'])) {
    $businessId = (int)$_GET['fix'];
    $business = $db->queryFirstRow("SELECT logo_path FROM businesses WHERE id = ?", [$businessId]);
    
    if ($business && strpos($business['logo_path'], 'uploads/') === 0) {
        $newPath = str_replace('uploads/', '', $business['logo_path']);
        $result = $db->update('businesses', ['logo_path' => $newPath], ['id' => $businessId]);
        
        if ($result) {
            echo "<script>alert('Ruta corregida exitosamente'); window.location.reload();</script>";
        }
    }
}

echo "<hr>";
echo "<p><a href='?fixall=1' style='background: blue; color: white; padding: 10px 20px; text-decoration: none;'>Corregir todos los registros problemáticos</a></p>";

// Corrección masiva
if (isset($_GET['fixall'])) {
    $problemBusinesses = $db->query("SELECT id, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'");
    $fixed = 0;
    
    foreach ($problemBusinesses as $business) {
        $newPath = str_replace('uploads/', '', $business['logo_path']);
        $result = $db->update('businesses', ['logo_path' => $newPath], ['id' => $business['id']]);
        if ($result) $fixed++;
    }
    
    echo "<script>alert('Se corrigieron $fixed registros'); window.location.reload();</script>";
}
?>