<?php
require_once 'models/News.php';

class NewsController {
    private $db;
    private $newsModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->newsModel = new News($database);
    }
    
    // Vista pública principal de noticias
    public function index($segments = []) {
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            
            $news = $this->newsModel->getPublished($limit, $offset) ?? [];
            $featuredNews = $this->newsModel->getFeatured(3) ?? [];
            $latestNews = $this->newsModel->getLatest(5) ?? [];
            $totalNews = $this->newsModel->count(true) ?? 0;
            $totalPages = $totalNews > 0 ? ceil($totalNews / $limit) : 1;
            
            // Asegurar que todas las variables están definidas
            $title = 'Noticias';
            $currentPage = $page;
            $pagination = $this->generatePagination($page, $totalPages, 'index.php?controller=news&action=index');
            
            include 'views/news/index.php';
        } catch (Exception $e) {
            echo "Error al cargar noticias: " . $e->getMessage();
        }
    }
    
    // Ver noticia individual
    public function show($segments = []) {
        $slug = isset($_GET['slug']) ? $_GET['slug'] : (isset($segments[1]) ? $segments[1] : null);
        
        if (!$slug) {
            header('Location: index.php?controller=news&action=index');
            exit;
        }
        
        try {
            $news = $this->newsModel->findBySlug($slug);
            
            if (!$news || $news['status'] !== 'published') {
                $this->notFound();
                return;
            }
            
            // Incrementar vistas
            $this->newsModel->incrementViews($news['id']);
            
            // Obtener imágenes de la noticia
            $images = $this->newsModel->getImages($news['id']);
            
            // Noticias relacionadas
            $relatedNews = $this->newsModel->getLatest(4);
            $relatedNews = array_filter($relatedNews, function($item) use ($news) {
                return $item['id'] !== $news['id'];
            });
            $relatedNews = array_slice($relatedNews, 0, 3);
            
            $data = [
                'title' => $news['title'],
                'news' => $news,
                'images' => $images,
                'relatedNews' => $relatedNews
            ];
            
            include 'views/news/show.php';
        } catch (Exception $e) {
            echo "Error al cargar la noticia: " . $e->getMessage();
        }
    }
    
    // Búsqueda de noticias
    public function search() {
        try {
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';
            $results = [];
            
            if (!empty($query)) {
                $results = $this->newsModel->search($query, 20) ?? [];
            }
            
            include 'views/news/search.php';
        } catch (Exception $e) {
            echo "Error en la búsqueda: " . $e->getMessage();
        }
    }
    
    // === MÉTODOS ADMINISTRATIVOS ===
    
    // Listar noticias en admin
    public function admin() {
        require_once 'seguridad.php';
        verificarEditor(); // Los editores pueden ver noticias
        
        try {
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = 15;
            $offset = ($page - 1) * $limit;
            
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            $publishedOnly = ($status === 'published');
            
            if ($status === 'all') {
                $allNews = $this->newsModel->getAll($limit, $offset, false);
                $totalNews = $this->newsModel->count(false);
            } else {
                $allNews = $this->newsModel->getAll($limit, $offset, $publishedOnly);
                $totalNews = $this->newsModel->count($publishedOnly);
            }
            
            $totalPages = ceil($totalNews / $limit);
            
            include 'views/admin/news-list.php';
        } catch (Exception $e) {
            echo "Error al cargar noticias administrativas: " . $e->getMessage();
        }
    }
    
    // Crear nueva noticia
    public function create() {
        require_once 'seguridad.php';
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'redactor') {
            $_SESSION['error'] = 'Solo el rol redactor puede crear noticias.';
            header('Location: index.php?controller=news&action=admin');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verificarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION['error'] = 'Token de seguridad inválido';
                header('Location: index.php?controller=news&action=create');
                exit;
            }
            
            try {
                $data = [
                    'title' => trim($_POST['title']),
                    'content' => trim($_POST['content']),
                    'excerpt' => trim($_POST['excerpt']),
                    'meta_description' => trim($_POST['meta_description']),
                    'author_id' => $_SESSION['user_id'],
                    'status' => $_POST['status'] ?? 'draft',
                    'featured' => isset($_POST['featured']) ? 1 : 0,
                    'category' => $_POST['category'] ?? 'general'
                ];
                
                // Validaciones básicas
                if (empty($data['title']) || empty($data['content'])) {
                    throw new Exception('El título y contenido son obligatorios');
                }
                
                $newsId = $this->newsModel->create($data);
                
                // Procesar imágenes subidas
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $this->handleImageUpload($newsId, $_FILES['images']);
                }
                
                $_SESSION['success'] = 'Noticia creada exitosamente';
                header('Location: index.php?controller=news&action=admin');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al crear noticia: ' . $e->getMessage();
            }
        }
        
        include 'views/admin/news-create.php';
    }
    
    // Editar noticia
    public function edit() {
        require_once 'seguridad.php';
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'redactor') {
            $_SESSION['error'] = 'Solo el rol redactor puede editar noticias.';
            header('Location: index.php?controller=news&action=admin');
            exit;
        }
        
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=news&action=admin');
            exit;
        }
        
        $id = intval($_GET['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verificarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION['error'] = 'Token de seguridad inválido';
                header('Location: index.php?controller=news&action=edit&id=' . $id);
                exit;
            }
            
            try {
                $data = [
                    'title' => trim($_POST['title']),
                    'content' => trim($_POST['content']),
                    'excerpt' => trim($_POST['excerpt']),
                    'meta_description' => trim($_POST['meta_description']),
                    'status' => $_POST['status'] ?? 'draft',
                    'featured' => isset($_POST['featured']) ? 1 : 0,
                    'category' => $_POST['category'] ?? 'general'
                ];
                
                if (empty($data['title']) || empty($data['content'])) {
                    throw new Exception('El título y contenido son obligatorios');
                }
                
                $this->newsModel->update($id, $data);
                
                // Procesar nuevas imágenes
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $this->handleImageUpload($id, $_FILES['images']);
                }
                
                $_SESSION['success'] = 'Noticia actualizada exitosamente';
                header('Location: index.php?controller=news&action=admin');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al actualizar noticia: ' . $e->getMessage();
            }
        }
        
        try {
            $noticia = $this->newsModel->findById($id);
            if (!$noticia) {
                $_SESSION['error'] = 'Noticia no encontrada';
                header('Location: index.php?controller=news&action=admin');
                exit;
            }
            
            $images = $this->newsModel->getImages($id);
            include 'views/admin/news-edit.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar noticia: ' . $e->getMessage();
            header('Location: index.php?controller=news&action=admin');
            exit;
        }
    }
    
    // Eliminar noticia
    public function delete() {
        require_once 'seguridad.php';
        verificarAdmin(); // Solo admins pueden eliminar
        
        if (!isset($_POST['id']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Acción no autorizada';
            header('Location: index.php?controller=news&action=admin');
            exit;
        }
        
        try {
            $id = intval($_POST['id']);
            $this->newsModel->delete($id);
            $_SESSION['success'] = 'Noticia eliminada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar noticia: ' . $e->getMessage();
        }
        
        header('Location: index.php?controller=news&action=admin');
        exit;
    }
    
    // Cambiar estado de publicación
    public function toggleStatus() {
        require_once 'seguridad.php';
        verificarEditor();
        
        if (!isset($_POST['id']) || !isset($_POST['status']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit;
        }
        
        try {
            $id = intval($_POST['id']);
            $status = $_POST['status'];
            
            if ($status === 'published') {
                $this->newsModel->publish($id);
            } else {
                $this->newsModel->unpublish($id);
            }
            
            echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    
    // Manejar subida de imágenes
    private function handleImageUpload($newsId, $files) {
        $uploadDir = 'uploads/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $originalName = $files['name'][$i];
                $fileType = $files['type'][$i];
                $fileSize = $files['size'][$i];
                
                // Validaciones
                if (!in_array($fileType, $allowedTypes)) {
                    continue;
                }
                
                if ($fileSize > $maxSize) {
                    continue;
                }
                
                // Generar nombre único
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $extension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($tmpName, $filePath)) {
                    $this->newsModel->addImage($newsId, $filePath, null, $_SESSION['user_id']);
                }
            }
        }
    }

    private function generatePagination($currentPage, $totalPages, $baseUrl) {
        $pagination = [];
        
        // Página anterior
        if ($currentPage > 1) {
            $pagination['prev'] = $baseUrl . '&page=' . ($currentPage - 1);
        }
        
        // Páginas numeradas
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pagination['pages'][] = [
                'number' => $i,
                'url' => $baseUrl . '&page=' . $i,
                'current' => $i == $currentPage
            ];
        }
        
        // Página siguiente
        if ($currentPage < $totalPages) {
            $pagination['next'] = $baseUrl . '&page=' . ($currentPage + 1);
        }
        
        return $pagination;
    }
    
    private function notFound() {
        http_response_code(404);
        include 'views/errors/404.php';
    }
}
?>