<?php
echo "Analizando duplicación en section/show...\n\n";

$url = 'http://localhost/clone/portal_noticias/index.php?controller=section&action=show&slug=licorerias';

try {
    $html = file_get_contents($url);
    
    // Buscar todas las ocurrencias de <nav
    preg_match_all('/<nav[^>]*>/i', $html, $matches, PREG_OFFSET_CAPTURE);
    
    echo "Navbars encontrados: " . count($matches[0]) . "\n\n";
    
    foreach($matches[0] as $index => $match) {
        $navTag = $match[0];
        $position = $match[1];
        
        echo "Navbar #" . ($index + 1) . ":\n";
        echo "Posición: $position\n";
        echo "Tag: $navTag\n";
        
        // Contexto antes y después
        $start = max(0, $position - 200);
        $context = substr($html, $start, 400);
        echo "Contexto:\n" . htmlspecialchars($context) . "\n";
        echo str_repeat("-", 50) . "\n\n";
    }
    
    // Verificar si hay contenido duplicado completo
    $bodyCount = substr_count($html, '<body>');
    $footerCount = substr_count($html, '<footer');
    
    echo "Bodies: $bodyCount\n";
    echo "Footers: $footerCount\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>