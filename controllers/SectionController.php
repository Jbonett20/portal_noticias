<?php
require_once 'models/Section.php';
require_once 'models/Business.php';

class SectionController {
    private $db;
    private $sectionModel;
    private $businessModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->sectionModel = new Section($database);
        $this->businessModel = new Business($database);
    }
    
    public function index($segments = []) {
        // Obtener secciones con conteo de negocios
        $allSections = $this->sectionModel->getAllWithBusinessCount();
        
        // Filtrar solo secciones que tienen negocios activos
        $sectionsWithBusinesses = array_filter($allSections, function($section) {
            return $section['business_count'] > 0;
        });
        
        // Verificar que existan datos válidos
        $validSections = [];
        foreach ($sectionsWithBusinesses as $section) {
            if (!empty($section['title']) && !empty($section['id'])) {
                $validSections[] = $section;
            }
        }
        
        $data = [
            'title' => 'Secciones - ' . SITE_NAME,
            'sections' => $validSections
        ];
        
        $this->render('sections/index', $data);
    }
    
    public function show($segments = []) {
        $slug = $_GET['slug'] ?? null;
        
        if (!$slug) {
            $this->notFound();
            return;
        }
        
        $section = $this->sectionModel->findBySlug($slug);
        
        if (!$section) {
            $this->notFound();
            return;
        }
        
        // Obtener negocios de la sección
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $businesses = $this->businessModel->getPublished($limit, $offset, $section['id']);
        $totalBusinesses = $this->businessModel->count($section['id']);
        $totalPages = ceil($totalBusinesses / $limit);
        
        $data = [
            'title' => $section['title'] . ' - ' . SITE_NAME,
            'section' => $section,
            'businesses' => $businesses,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pagination' => $this->generatePagination($page, $totalPages, 'index.php?controller=section&action=show&slug=' . $slug)
        ];
        
        $this->render('sections/show', $data);
    }
    
    private function generatePagination($currentPage, $totalPages, $baseUrl) {
        $pagination = [];
        
        // Página anterior
        if ($currentPage > 1) {
            $pagination['prev'] = $baseUrl . '?page=' . ($currentPage - 1);
        }
        
        // Páginas numeradas
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pagination['pages'][] = [
                'number' => $i,
                'url' => $baseUrl . '?page=' . $i,
                'current' => $i == $currentPage
            ];
        }
        
        // Página siguiente
        if ($currentPage < $totalPages) {
            $pagination['next'] = $baseUrl . '?page=' . ($currentPage + 1);
        }
        
        return $pagination;
    }
    
    private function notFound() {
        http_response_code(404);
        $data = ['title' => 'Sección no encontrada'];
        $this->render('errors/404', $data);
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