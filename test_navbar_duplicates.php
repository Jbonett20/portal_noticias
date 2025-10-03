<?php
$pages = [
    'business' => 'http://localhost/clone/portal_noticias/business',
    'section' => 'http://localhost/clone/portal_noticias/section', 
    'section-detail' => 'http://localhost/clone/portal_noticias/index.php?controller=section&action=show&slug=licorerias',
    'home' => 'http://localhost/clone/portal_noticias/',
    'news' => 'http://localhost/clone/portal_noticias/news'
];

echo "Verificando duplicación de navbar PRINCIPAL en páginas...\n\n";

foreach($pages as $name => $url) {
    try {
        $html = file_get_contents($url);
        
        // Contar solo navbar principal (no breadcrumbs)
        $mainNavCount = substr_count($html, '<nav class="navbar navbar-expand-lg');
        $bodyCount = substr_count($html, '<body>');
        $footerCount = substr_count($html, '<footer');
        
        echo "Página $name:\n";
        echo "  - Navbars principales: $mainNavCount\n";
        echo "  - Bodies: $bodyCount\n"; 
        echo "  - Footers: $footerCount\n";
        
        if($mainNavCount > 1 || $bodyCount > 1 || $footerCount > 1) {
            echo "  ⚠️  DUPLICACIÓN DETECTADA!\n";
        } else {
            echo "  ✅ Sin duplicación\n";
        }
        echo "\n";
    } catch(Exception $e) {
        echo "Página $name: Error - " . $e->getMessage() . "\n\n";
    }
}
?>