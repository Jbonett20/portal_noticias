<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Business.php';

try {
    $db = Database::getInstance();
    $businessModel = new Business($db);
    $businesses = $businessModel->getAll();
    
    echo "<h2>Lista de Negocios Disponibles</h2>";
    
    if (!empty($businesses)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 1rem 0;'>";
        echo "<tr style='background: #f8f9fa;'><th>ID</th><th>Nombre</th><th>Estado</th><th>Enlaces de Prueba</th></tr>";
        
        foreach ($businesses as $business) {
            echo "<tr>";
            echo "<td>" . $business['id'] . "</td>";
            echo "<td>" . htmlspecialchars($business['name']) . "</td>";
            echo "<td>" . ($business['is_published'] ? 'Publicado' : 'No publicado') . "</td>";
            echo "<td>";
            
            // Enlaces por ID
            echo "<a href='index.php?controller=business&action=show&id=" . $business['id'] . "' target='_blank'>Ver por ID</a><br>";
            
            // Enlaces por slug (si existe)
            if (!empty($business['slug'])) {
                echo "<a href='index.php?controller=business&action=show&slug=" . $business['slug'] . "' target='_blank'>Ver por Slug</a><br>";
                echo "<a href='business/" . $business['slug'] . "' target='_blank'>URL Amigable</a>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        if (count($businesses) > 0) {
            $firstBusiness = $businesses[0];
            echo "<h3>Prueba Rápida - Primer Negocio:</h3>";
            echo "<p>ID: " . $firstBusiness['id'] . " - " . htmlspecialchars($firstBusiness['name']) . "</p>";
            echo "<p><a href='index.php?controller=business&action=show&id=" . $firstBusiness['id'] . "' target='_blank' style='background: #007bff; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;'>🔗 Probar Este Enlace</a></p>";
        }
        
    } else {
        echo "<p>❌ No hay negocios en la base de datos.</p>";
        echo "<p><a href='check_db.php'>Ejecutar verificación de BD</a></p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 2rem; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
th { background: #f8f9fa; font-weight: bold; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>