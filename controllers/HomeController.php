<?php
require_once 'models/News.php';
require_once 'models/Section.php';
require_once 'models/Business.php';

class HomeController {
    private $db;
    private $newsModel;
    private $sectionModel;
    private $businessModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->newsModel = new News($database);
        $this->sectionModel = new Section($database);
        $this->businessModel = new Business($database);
    }
    
    public function index($segments = []) {
        // Obtener noticias destacadas
        $featuredNews = $this->newsModel->getFeatured(3);
        $latestNews = $this->newsModel->getLatest(6);
        
        // Obtener secciones con conteo de negocios
        $sections = $this->sectionModel->getAllWithBusinessCount();
        
        // Obtener negocios destacados
        $featuredBusinesses = $this->businessModel->getPublished(6);
        
        $data = [
            'title' => 'Inicio - ' . SITE_NAME,
            'featuredNews' => $featuredNews,
            'latestNews' => $latestNews,
            'sections' => $sections,
            'featuredBusinesses' => $featuredBusinesses
        ];
        
        $this->render('home/index', $data);
    }
    
    public function search($segments = []) {
        $query = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? 'all'; // all, news, business
        
        $results = [];
        
        if (!empty($query)) {
            if ($type === 'all' || $type === 'news') {
                $results['news'] = $this->newsModel->search($query, 10);
            }
            
            if ($type === 'all' || $type === 'business') {
                $results['businesses'] = $this->businessModel->search($query, 10);
            }
        }
        
        $data = [
            'title' => 'Buscar: ' . $query,
            'query' => $query,
            'type' => $type,
            'results' => $results
        ];
        
        $this->render('home/search', $data);
    }
    
    private function render($view, $data = []) {
        // Extraer variables
        extract($data);
        
        // Generar ruta del archivo de vista
        $viewFile = __DIR__ . "/../views/{$view}.php";
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            
            include __DIR__ . '/../views/layout/main.php';
        } else {
            echo "Vista no encontrada: {$view}";
        }
    }
}
?>