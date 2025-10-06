<?php
require_once __DIR__ . '/config/config.php';

echo "=== CORRECCIÓN DE RUTAS DE IMÁGENES ===\n";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos\n";
    
    // Verificar registros problemáticos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM businesses WHERE logo_path LIKE 'uploads/%'");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Registros problemáticos: " . $count['total'] . "\n";
    
    if ($count['total'] > 0) {
        // Mostrar registros problemáticos
        echo "\n=== REGISTROS PROBLEMÁTICOS ===\n";
        $stmt = $pdo->query("SELECT id, name, logo_path FROM businesses WHERE logo_path LIKE 'uploads/%'");
        $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($problems as $business) {
            echo "ID: {$business['id']} | {$business['name']} | '{$business['logo_path']}'\n";
        }
        
        // Ejecutar corrección
        echo "\n=== EJECUTANDO CORRECCIÓN ===\n";
        $stmt = $pdo->prepare("UPDATE businesses SET logo_path = REPLACE(logo_path, 'uploads/', '') WHERE logo_path LIKE 'uploads/%'");
        $result = $stmt->execute();
        $affected = $stmt->rowCount();
        
        echo "✅ Corrección ejecutada. Filas afectadas: $affected\n";
        
        // Verificar resultado
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM businesses WHERE logo_path LIKE 'uploads/%'");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Registros problemáticos restantes: " . $count['total'] . "\n";
        
        if ($count['total'] == 0) {
            echo "\n🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!\n";
            echo "👀 Ahora verifica las imágenes en:\n";
            echo "   - Dashboard: http://localhost/clone/portal_noticias/dashboard\n";
            echo "   - Página principal: http://localhost/clone/portal_noticias/\n";
        }
    } else {
        echo "✅ No hay registros problemáticos\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>