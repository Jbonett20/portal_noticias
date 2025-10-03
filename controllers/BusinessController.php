<?php
require_once 'models/Business.php';
require_once 'models/Section.php';

class BusinessController {
    private $db;
    private $businessModel;
    private $sectionModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->businessModel = new Business($database);
        $this->sectionModel = new Section($database);
    }
    
    public function index($segments = []) {
        $page = $_GET['page'] ?? 1;
        $sectionId = $_GET['section'] ?? null;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $businesses = $this->businessModel->getPublished($limit, $offset, $sectionId);
        $totalBusinesses = $this->businessModel->count($sectionId);
        $totalPages = ceil($totalBusinesses / $limit);
        
        // Obtener secciones para el filtro
        $sections = $this->sectionModel->getAll();
        
        $data = [
            'title' => 'Negocios - ' . SITE_NAME,
            'businesses' => $businesses,
            'sections' => $sections,
            'currentSection' => $sectionId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pagination' => $this->generatePagination($page, $totalPages, BASE_URL . 'business', $sectionId)
        ];
        
        $this->render('business/index', $data);
    }
    
    public function show($segments = []) {
        // Obtener el identificador del negocio desde GET o segments
        $identifier = null;
        $isId = false;
        
        if (isset($_GET['id'])) {
            // Si viene por parámetro GET
            $identifier = $_GET['id'];
            $isId = true;
        } elseif (isset($_GET['slug'])) {
            // Si viene slug por parámetro GET
            $identifier = $_GET['slug'];
            $isId = false;
        } elseif (isset($segments[1])) {
            // Si viene por URL segments
            $identifier = $segments[1];
            $isId = is_numeric($identifier);
        }
        
        if (!$identifier) {
            $this->notFound();
            return;
        }
        
        // Buscar el negocio por ID o slug
        if ($isId) {
            $business = $this->businessModel->findById($identifier);
        } else {
            $business = $this->businessModel->findBySlug($identifier);
        }
        
        if (!$business) {
            $this->notFound();
            return;
        }
        
        // Obtener imágenes del negocio
        $images = $this->db->fetchAll(
            "SELECT image_path, caption, is_featured, display_order 
             FROM business_images 
             WHERE business_id = ? 
             ORDER BY is_featured DESC, display_order ASC, uploaded_at DESC",
            [$business['id']]
        );
        
        // Obtener videos del negocio
        $videos = $this->db->fetchAll(
            "SELECT video_type, video_url, video_path, title, description, display_order 
             FROM business_videos 
             WHERE business_id = ? AND is_active = 1 
             ORDER BY display_order ASC, created_at DESC",
            [$business['id']]
        );
        
        // Obtener negocios relacionados (misma sección)
        $relatedBusinesses = $this->businessModel->getPublished(4, 0, $business['section_id']);
        // Filtrar el negocio actual
        $relatedBusinesses = array_filter($relatedBusinesses, function($item) use ($business) {
            return $item['id'] !== $business['id'];
        });
        $relatedBusinesses = array_slice($relatedBusinesses, 0, 3);
        
        $data = [
            'title' => $business['name'] . ' - ' . SITE_NAME,
            'business' => $business,
            'images' => $images,
            'videos' => $videos,
            'relatedBusinesses' => $relatedBusinesses
        ];
        
        $this->render('business/show', $data);
    }
    
    private function generatePagination($currentPage, $totalPages, $baseUrl, $sectionId = null) {
        $pagination = [];
        $params = $sectionId ? "?section={$sectionId}&" : '?';
        
        // Página anterior
        if ($currentPage > 1) {
            $pagination['prev'] = $baseUrl . $params . 'page=' . ($currentPage - 1);
        }
        
        // Páginas numeradas
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pagination['pages'][] = [
                'number' => $i,
                'url' => $baseUrl . $params . 'page=' . $i,
                'current' => $i == $currentPage
            ];
        }
        
        // Página siguiente
        if ($currentPage < $totalPages) {
            $pagination['next'] = $baseUrl . $params . 'page=' . ($currentPage + 1);
        }
        
        return $pagination;
    }
    
    private function notFound() {
        http_response_code(404);
        $data = ['title' => 'Negocio no encontrado'];
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