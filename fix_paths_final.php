<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Corrección Simple de Rutas</h2>";
    
    // Paso 1: Mostrar el estado actual
    echo "<h3>Estado actual:</h3>";
    $businesses = $db->query("SELECT id, name, logo_path FROM businesses WHERE logo_path IS NOT NULL");
    
    $problematicos = 0;
    foreach ($businesses as $business) {
        if (strpos($business['logo_path'], 'uploads/') === 0) {
            $problematicos++;
            echo "<p>❌ ID {$business['id']} - {$business['name']}: '{$business['logo_path']}'</p>";
        } else {
            echo "<p>✅ ID {$business['id']} - {$business['name']}: '{$business['logo_path']}'</p>";
        }
    }
    
    echo "<p><strong>Total problemáticos: {$problematicos}</strong></p>";
    
    if ($problematicos > 0 && !isset($_GET['ejecutar'])) {
        echo "<p><a href='?ejecutar=1' style='background: red; color: white; padding: 15px; text-decoration: none; font-size: 18px;'>EJECUTAR CORRECCIÓN</a></p>";
    }
    
    // Paso 2: Ejecutar corrección si se solicita
    if (isset($_GET['ejecutar'])) {
        echo "<h3>Ejecutando corrección...</h3>";
        
        // Usar una nueva conexión para evitar problemas de sincronización
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Obtener registros problemáticos
        $stmt = $pdo->prepare("SELECT id, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'");
        $stmt->execute();
        $problemBusinesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $corregidos = 0;
        foreach ($problemBusinesses as $business) {
            $newPath = str_replace('uploads/', '', $business['logo_path']);
            
            $updateStmt = $pdo->prepare("UPDATE businesses SET logo_path = ? WHERE id = ?");
            if ($updateStmt->execute([$newPath, $business['id']])) {
                echo "<p style='color: green;'>✅ ID {$business['id']}: '{$business['logo_path']}' → '{$newPath}'</p>";
                $corregidos++;
            } else {
                echo "<p style='color: red;'>❌ Error en ID {$business['id']}</p>";
            }
        }
        
        echo "<div style='background: green; color: white; padding: 20px; margin: 20px 0; text-align: center;'>";
        echo "<h2>¡CORRECCIÓN COMPLETADA!</h2>";
        echo "<p>Se corrigieron {$corregidos} registros</p>";
        echo "</div>";
        
        echo "<p><a href='dashboard' style='background: blue; color: white; padding: 15px 30px; text-decoration: none; margin: 10px;'>Ir al Dashboard</a></p>";
        echo "<p><a href='.' style='background: green; color: white; padding: 15px 30px; text-decoration: none; margin: 10px;'>Ir a la Página Principal</a></p>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: red; color: white; padding: 15px;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>