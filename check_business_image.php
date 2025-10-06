<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Verificación Detallada de Imágenes</h2>\n";
    
    // Obtener el primer negocio con logo_path
    $business = $db->queryFirstRow("SELECT * FROM businesses WHERE logo_path IS NOT NULL LIMIT 1");
    
    if ($business) {
        echo "<h3>Negocio de prueba: {$business['name']}</h3>\n";
        echo "<p><strong>Logo Path en DB:</strong> {$business['logo_path']}</p>\n";
        
        // Verificar diferentes combinaciones de rutas
        $possiblePaths = [
            UPLOAD_PATH . $business['logo_path'],
            UPLOAD_PATH . 'logos/' . $business['logo_path'],
            UPLOAD_PATH . 'businesses/' . $business['logo_path'],
            __DIR__ . '/' . $business['logo_path'],
            __DIR__ . '/uploads/' . $business['logo_path']
        ];
        
        echo "<h4>Verificación de rutas físicas:</h4>\n";
        foreach ($possiblePaths as $path) {
            $exists = file_exists($path) ? '✅' : '❌';
            echo "<p>{$exists} {$path}</p>\n";
        }
        
        // Verificar URLs
        $possibleUrls = [
            UPLOAD_URL . $business['logo_path'],
            BASE_URL . $business['logo_path'],
            BASE_URL . 'uploads/' . $business['logo_path'],
            BASE_URL . 'uploads/logos/' . $business['logo_path'],
            BASE_URL . 'uploads/businesses/' . $business['logo_path']
        ];
        
        echo "<h4>URLs posibles:</h4>\n";
        foreach ($possibleUrls as $url) {
            echo "<p><a href='{$url}' target='_blank'>{$url}</a></p>\n";
            echo "<img src='{$url}' style='max-width: 50px; max-height: 50px; margin: 5px;' onerror='this.style.border=\"2px solid red\"' onload='this.style.border=\"2px solid green\"'>\n";
        }
        
    } else {
        echo "<p>No hay negocios con logo_path en la base de datos</p>\n";
    }
    
    // Mostrar constantes
    echo "<h4>Constantes de configuración:</h4>\n";
    echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>\n";
    echo "<p><strong>UPLOAD_URL:</strong> " . UPLOAD_URL . "</p>\n";
    echo "<p><strong>UPLOAD_PATH:</strong> " . UPLOAD_PATH . "</p>\n";
    
    // Listar archivos reales en uploads
    echo "<h4>Archivos reales en uploads:</h4>\n";
    $uploadDirs = ['logos', 'businesses', 'news'];
    
    foreach ($uploadDirs as $dir) {
        $fullDir = __DIR__ . '/uploads/' . $dir;
        if (is_dir($fullDir)) {
            $files = scandir($fullDir);
            echo "<p><strong>{$dir}/:</strong></p>\n";
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "<p>  - {$file}</p>\n";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>