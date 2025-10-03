<?php
$pages = [
    'business' => 'http://localhost/clone/portal_noticias/business',
    'section' => 'http://localhost/clone/portal_noticias/section', 
    'home' => 'http://localhost/clone/portal_noticias/',
    'news' => 'http://localhost/clone/portal_noticias/news'
];

echo "Verificando duplicación de navbar en páginas...\n\n";

foreach($pages as $name => $url) {
    try {
        $html = file_get_contents($url);
        $navCount = substr_count($html, '<nav class="navbar');
        echo "Página $name: $navCount navbars\n";
        
        if($navCount > 1) {
            echo "  ⚠️  DUPLICACIÓN DETECTADA!\n";
        } else {
            echo "  ✅ Sin duplicación\n";
        }
    } catch(Exception $e) {
        echo "Página $name: Error - " . $e->getMessage() . "\n";
    }
}
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