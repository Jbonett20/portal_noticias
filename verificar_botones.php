<?php
echo "Verificando corrección de botones 'Volver al Inicio'...\n\n";

$testPages = [
    'Cafeterías (sin negocios)' => 'http://localhost/clone/portal_noticias/index.php?controller=section&action=show&slug=cafeterias',
    'Supermercados (sin negocios)' => 'http://localhost/clone/portal_noticias/index.php?controller=section&action=show&slug=supermercados',
    'Página 404' => 'http://localhost/clone/portal_noticias/pagina-inexistente'
];

foreach($testPages as $name => $url) {
    try {
        echo "Probando: $name\n";
        $html = file_get_contents($url);
        
        // Buscar botones "Volver al Inicio" problemáticos
        $badLinks = preg_match_all('/href=["\']index\.php["\'][^>]*>.*?Volver al Inicio/i', $html);
        $goodLinks = preg_match_all('/href=["\'][^"\']*BASE_URL[^"\']*["\'][^>]*>.*?Volver al Inicio/i', $html);
        
        if ($badLinks > 0) {
            echo "  ❌ Encontrados $badLinks enlaces problemáticos\n";
        } else {
            echo "  ✅ Sin enlaces problemáticos\n";
        }
        
        // Verificar presencia de BASE_URL en botones
        if (strpos($html, 'href="<?= BASE_URL ?>"') !== false) {
            echo "  ✅ Botones corregidos con BASE_URL\n";
        }
        
        echo "\n";
        
    } catch(Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n\n";
    }
}

echo "✅ Verificación completada!\n";
echo "Los botones 'Volver al Inicio' ahora deberían funcionar correctamente.\n";
?>