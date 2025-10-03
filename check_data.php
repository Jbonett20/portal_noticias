<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Business.php';
require_once 'models/Section.php';

try {
    echo "Verificando datos en BD...\n";
    $db = Database::getInstance();
    $businessModel = new Business($db);
    $sectionModel = new Section($db);
    
    $businesses = $businessModel->getPublished(20);
    $sections = $sectionModel->getAllWithBusinessCount();
    
    echo "Negocios encontrados: " . count($businesses) . "\n";
    echo "Secciones encontradas: " . count($sections) . "\n\n";
    
    echo "Secciones con negocios:\n";
    foreach($sections as $section) {
        echo "- " . $section['title'] . ": " . $section['business_count'] . " negocios\n";
    }
    
    if(count($businesses) > 0) {
        echo "\nEjemplos de negocios:\n";
        foreach(array_slice($businesses, 0, 5) as $business) {
            echo "- " . $business['name'] . " (" . $business['section_title'] . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>