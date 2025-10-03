<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Section.php';

try {
    echo "Verificando secciones válidas...\n";
    $db = Database::getInstance();
    $sectionModel = new Section($db);
    
    $allSections = $sectionModel->getAllWithBusinessCount();
    $validSections = array_filter($allSections, function($section) {
        return $section['business_count'] > 0 && !empty($section['title']);
    });
    
    echo "Total secciones en BD: " . count($allSections) . "\n";
    echo "Secciones con negocios: " . count($validSections) . "\n\n";
    
    echo "Secciones válidas:\n";
    foreach($validSections as $section) {
        echo "- " . $section['title'] . ": " . $section['business_count'] . " negocios\n";
        echo "  Descripción: " . ($section['description'] ? substr($section['description'], 0, 50) . '...' : 'Sin descripción') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>