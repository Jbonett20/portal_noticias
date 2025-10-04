<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/User.php';
require_once 'models/Business.php';
require_once 'models/News.php';
require_once 'models/Section.php';

try {
    $db = new Database();
    echo "=== PROBANDO PANEL DE ADMINISTRACIÓN ===\n";
    
    // Probar las estadísticas como las hace AdminController
    $stats = [
        'total_users' => $db->fetch("SELECT COUNT(*) as count FROM users")['count'],
        'total_businesses' => $db->fetch("SELECT COUNT(*) as count FROM businesses")['count'],
        'total_news' => $db->fetch("SELECT COUNT(*) as count FROM news")['count'],
        'total_sections' => $db->fetch("SELECT COUNT(*) as count FROM sections")['count'],
        'users_by_role' => $db->fetchAll("SELECT role, COUNT(*) as count FROM users GROUP BY role"),
        'businesses_published' => $db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_published = 1")['count'],
        'businesses_pending' => $db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_published = 0")['count'],
        'news_published' => $db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 1")['count'],
        'news_draft' => $db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 0")['count']
    ];
    
    echo "✅ Usuarios totales: " . $stats['total_users'] . "\n";
    echo "✅ Negocios totales: " . $stats['total_businesses'] . "\n";
    echo "✅ Noticias totales: " . $stats['total_news'] . "\n";
    echo "✅ Secciones totales: " . $stats['total_sections'] . "\n";
    echo "✅ Negocios publicados: " . $stats['businesses_published'] . "\n";
    echo "✅ Negocios pendientes: " . $stats['businesses_pending'] . "\n";
    echo "✅ Noticias publicadas: " . $stats['news_published'] . "\n";
    echo "✅ Noticias en borrador: " . $stats['news_draft'] . "\n";
    
    echo "\n=== PROBANDO MODELOS ===\n";
    
    // Probar modelo News
    $newsModel = new News($db);
    $newsWithDetails = $newsModel->getAllWithDetails();
    echo "✅ Noticias con detalles: " . count($newsWithDetails) . "\n";
    
    if (!empty($newsWithDetails)) {
        $firstNews = $newsWithDetails[0];
        echo "✅ Primera noticia - Status: " . ($firstNews['status'] ?? 'no definido') . "\n";
        echo "✅ Primera noticia - Autor: " . ($firstNews['author_name'] ?? 'no definido') . "\n";
    }
    
    echo "\n🎉 TODAS LAS PRUEBAS EXITOSAS - Panel listo para usar!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>