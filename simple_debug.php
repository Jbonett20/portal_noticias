<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

$db = Database::getInstance();

echo "<h2>Debug Simple - Primer Negocio con Logo</h2>";

// Obtener el primer negocio con logo
$business = $db->queryFirstRow("SELECT * FROM businesses WHERE logo_path IS NOT NULL ORDER BY id LIMIT 1");

if (!$business) {
    echo "No hay negocios con logo_path";
    exit;
}

echo "<h3>Datos del negocio:</h3>";
echo "<p><strong>ID:</strong> {$business['id']}</p>";
echo "<p><strong>Nombre:</strong> {$business['name']}</p>";
echo "<p><strong>Logo Path en DB:</strong> '{$business['logo_path']}'</p>";

echo "<h3>Constantes:</h3>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>UPLOAD_URL:</strong> " . UPLOAD_URL . "</p>";
echo "<p><strong>UPLOAD_PATH:</strong> " . UPLOAD_PATH . "</p>";

echo "<h3>URL construida:</h3>";
$fullUrl = UPLOAD_URL . $business['logo_path'];
echo "<p><strong>URL final:</strong> {$fullUrl}</p>";

echo "<h3>Verificación física:</h3>";
$physicalPath = UPLOAD_PATH . $business['logo_path'];
echo "<p><strong>Ruta física:</strong> {$physicalPath}</p>";
echo "<p><strong>¿Existe?:</strong> " . (file_exists($physicalPath) ? 'SÍ ✅' : 'NO ❌') . "</p>";

if (file_exists($physicalPath)) {
    echo "<p><strong>Tamaño:</strong> " . filesize($physicalPath) . " bytes</p>";
}

echo "<h3>Prueba de imagen:</h3>";
echo "<p>Intentando cargar: <a href='{$fullUrl}' target='_blank'>{$fullUrl}</a></p>";
echo "<img src='{$fullUrl}' style='max-width: 200px; border: 2px solid #ccc;' onload='this.style.borderColor=\"green\"; this.nextElementSibling.innerHTML=\"✅ Imagen cargada correctamente\"' onerror='this.style.borderColor=\"red\"; this.nextElementSibling.innerHTML=\"❌ Error al cargar imagen\"'>";
echo "<p id='result'>⏳ Cargando...</p>";

// Verificar archivos en el directorio
echo "<h3>Archivos en uploads/logos/:</h3>";
$logoDir = UPLOAD_PATH . 'logos/';
if (is_dir($logoDir)) {
    $files = scandir($logoDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "<p>📄 {$file}</p>";
        }
    }
} else {
    echo "<p>❌ Directorio uploads/logos/ no existe</p>";
}

echo "<h3>Archivos en uploads/businesses/:</h3>";
$businessDir = UPLOAD_PATH . 'businesses/';
if (is_dir($businessDir)) {
    $files = scandir($businessDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "<p>📄 {$file}</p>";
        }
    }
} else {
    echo "<p>❌ Directorio uploads/businesses/ no existe</p>";
}
?>