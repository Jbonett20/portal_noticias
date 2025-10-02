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
        $sections = $this->sectionModel->getAllWithBusinessCount();
        
        $data = [
            'title' => 'Secciones - ' . SITE_NAME,
            'sections' => $sections
        ];
        
        $this->render('sections/index', $data);
    }
    
    public function show($segments = []) {
        if (!isset($segments[1])) {
            $this->notFound();
            return;
        }
        
        $slug = $segments[1];
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
            'pagination' => $this->generatePagination($page, $totalPages, BASE_URL . 'section/' . $slug)
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
            include $viewFile;
        } else {
            echo "Vista no encontrada: {$view}";
        }
    }
}
?>