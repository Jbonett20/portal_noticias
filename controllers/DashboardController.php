<?php
require_once 'models/Business.php';
require_once 'models/Section.php';

class DashboardController {
    private $db;
    private $businessModel;
    private $sectionModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->businessModel = new Business($database);
        $this->sectionModel = new Section($database);
    }
    
    public function index($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarEditor();
        
        $user = getCurrentUser();
        
        // Obtener negocios del usuario con información completa de estado
        if ($user['role'] === 'admin') {
            $businesses = $this->db->fetchAll(
                "SELECT b.*, s.title as section_title,
                        CASE WHEN b.is_open = 1 THEN 'open' ELSE 'closed' END as display_status,
                        b.closed_reason
                 FROM businesses b 
                 LEFT JOIN sections s ON b.section_id = s.id 
                 ORDER BY b.status DESC, b.is_open DESC, b.created_at DESC"
            );
        } else {
            // Para editores/autores, solo mostrar EL negocio asignado en su business_id
            $businesses = [];
            if (!empty($user['business_id'])) {
                $businesses = $this->db->fetchAll(
                    "SELECT b.*, s.title as section_title,
                            CASE WHEN b.is_open = 1 THEN 'open' ELSE 'closed' END as display_status,
                            b.closed_reason
                     FROM businesses b 
                     LEFT JOIN sections s ON b.section_id = s.id 
                     WHERE b.id = ?
                     ORDER BY b.status DESC, b.is_open DESC, b.created_at DESC",
                    [$user['business_id']]
                );
            }
        }
        
        // Obtener estadísticas
        $stats = [];
        if ($user['role'] === 'admin') {
            $stats = [
                'total_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses")['count'],
                'open_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_open = 1")['count'],
                'closed_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_open = 0")['count'],
                'active_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses WHERE status = 'active'")['count']
            ];
        } else {
            $stats = [
                'my_businesses' => count($businesses),
                'open_businesses' => count(array_filter($businesses, fn($b) => $b['is_open'] == 1)),
                'closed_businesses' => count(array_filter($businesses, fn($b) => $b['is_open'] == 0)),
                'active_businesses' => count(array_filter($businesses, fn($b) => $b['status'] == 'active'))
            ];
        }
        
        $data = [
            'title' => 'Panel de Control',
            'user' => $user,
            'businesses' => $businesses,
            'stats' => $stats
        ];
        
        $this->render('dashboard/index', $data);
    }
    
    public function business($segments = []) {
        if (!isLoggedIn()) {
            redirectToLogin();
        }
        
        $action = $segments[1] ?? 'index';
        
        switch ($action) {
            case 'create':
                $this->createBusiness();
                break;
            case 'edit':
                $this->editBusiness($segments);
                break;
            default:
                $this->index();
        }
    }
    
    private function createBusiness() {
        $user = getCurrentUser();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sectionId = $_POST['section_id'] ?? '';
            $shortDescription = trim($_POST['short_description'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $website = trim($_POST['website'] ?? '');
            
            if (empty($name) || empty($sectionId)) {
                $error = 'Nombre y sección son obligatorios';
            } else {
                try {
                    $slug = generateSlug($name);
                    
                    // Verificar que el slug sea único
                    $existing = $this->businessModel->findBySlug($slug);
                    if ($existing) {
                        $slug .= '-' . uniqid();
                    }
                    
                    $businessData = [
                        'name' => $name,
                        'slug' => $slug,
                        'section_id' => $sectionId,
                        'short_description' => $shortDescription,
                        'description' => $description,
                        'phone' => $phone,
                        'address' => $address,
                        'website' => $website,
                        'created_by' => $user['id']
                    ];
                    
                    $businessId = $this->businessModel->create($businessData);
                    
                    // Si el usuario no es admin, vincularlo al negocio
                    if ($user['role'] !== 'admin') {
                        $this->db->query(
                            "UPDATE users SET business_id = ? WHERE id = ?",
                            [$businessId, $user['id']]
                        );
                    }
                    
                    redirect(BASE_URL . 'dashboard');
                } catch (Exception $e) {
                    $error = 'Error al crear el negocio: ' . $e->getMessage();
                }
            }
        }
        
        $sections = $this->sectionModel->findAll();
        
        $data = [
            'title' => 'Crear Negocio',
            'sections' => $sections,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('dashboard/business-create', $data);
    }
    
    private function editBusiness($segments) {
        $businessId = $segments[2] ?? null;
        if (!$businessId) {
            redirect(BASE_URL . 'dashboard');
        }
        
        $user = getCurrentUser();
        $business = $this->businessModel->findById($businessId);
        
        if (!$business) {
            redirect(BASE_URL . 'dashboard');
        }
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            // Verificar que el negocio pertenece al usuario
            if (empty($user['business_id']) || $user['business_id'] != $businessId) {
                redirect(BASE_URL . 'dashboard');
            }
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sectionId = $_POST['section_id'] ?? '';
            $shortDescription = trim($_POST['short_description'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $advertisementText = trim($_POST['advertisement_text'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $website = trim($_POST['website'] ?? '');
            
            if (empty($name) || empty($sectionId)) {
                $error = 'Nombre y sección son obligatorios';
            } else {
                try {
                    $updateData = [
                        'name' => $name,
                        'section_id' => $sectionId,
                        'short_description' => $shortDescription,
                        'description' => $description,
                        'advertisement_text' => $advertisementText,
                        'phone' => $phone,
                        'address' => $address,
                        'website' => $website
                    ];
                    
                    $this->businessModel->update($businessId, $updateData);
                    $success = 'Negocio actualizado exitosamente';
                    $business = $this->businessModel->findById($businessId); // Recargar datos
                } catch (Exception $e) {
                    $error = 'Error al actualizar el negocio: ' . $e->getMessage();
                }
            }
        }
        
        $sections = $this->sectionModel->findAll();
        
        $data = [
            'title' => 'Editar Negocio',
            'business' => $business,
            'sections' => $sections,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('dashboard/business-edit', $data);
    }
    
    public function toggleBusinessStatus($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de negocio requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            // Para editores, verificar tanto business_id como created_by
            // Convert businessId to integer for proper comparison
            $businessIdInt = (int)$businessId;
            
            if (empty($user['business_id']) || (int)$user['business_id'] !== $businessIdInt) {
                // También verificar si el usuario creó el negocio
                $business = $this->db->fetch("SELECT created_by FROM businesses WHERE id = ?", [$businessIdInt]);
                
                if (!$business || (int)$business['created_by'] !== (int)$user['id']) {
                    header('HTTP/1.1 403 Forbidden');
                    echo json_encode([
                        'error' => 'No tienes permisos para modificar este negocio',
                        'debug_info' => [
                            'user_id' => $user['id'],
                            'user_role' => $user['role'],
                            'user_business_id' => $user['business_id'] ?? null,
                            'target_business_id' => $businessIdInt,
                            'business_created_by' => $business['created_by'] ?? null
                        ]
                    ]);
                    return;
                }
            }
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $isOpen = $data['is_open'] ?? null;
        $closedReason = $data['closed_reason'] ?? null;
        
        try {
            $this->db->query(
                "UPDATE businesses SET is_open = ?, closed_reason = ?, updated_at = NOW() WHERE id = ?",
                [$isOpen, $closedReason, $businessId]
            );
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'status' => $isOpen ? 'open' : 'closed',
                'message' => $isOpen ? 'Negocio marcado como abierto' : 'Negocio marcado como cerrado'
            ]);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }
    
    public function uploadImage($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de negocio requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            // Verificar que el negocio pertenece al usuario
            if (empty($user['business_id']) || $user['business_id'] != $businessId) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos para modificar este negocio']);
                return;
            }
        }
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'No se recibió ninguna imagen válida']);
            return;
        }
        
        try {
            $uploadPath = $this->uploadBusinessImage($_FILES['image']);
            $caption = $_POST['caption'] ?? '';
            
            // Insertar en business_images
            $this->db->query(
                "INSERT INTO business_images (business_id, image_path, caption, uploaded_by, uploaded_at) 
                 VALUES (?, ?, ?, ?, NOW())",
                [$businessId, $uploadPath, $caption, $user['id']]
            );
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Imagen subida exitosamente',
                'image_path' => UPLOAD_URL . $uploadPath
            ]);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al subir imagen: ' . $e->getMessage()]);
        }
    }
    
    private function uploadBusinessImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Use JPG, PNG o GIF.');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('El archivo es muy grande. Máximo 5MB.');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('business_') . '.' . $extension;
        $uploadPath = 'businesses/' . $filename;
        $fullPath = UPLOAD_PATH . $uploadPath;
        
        // Crear directorio si no existe
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception('Error al subir el archivo.');
        }
        
        return $uploadPath;
    }
    
    public function getImages($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de negocio requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            if (empty($user['business_id']) || $user['business_id'] != $businessId) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos']);
                return;
            }
        }
        
        $images = $this->db->fetchAll(
            "SELECT id, image_path, caption, is_featured, display_order 
             FROM business_images 
             WHERE business_id = ? 
             ORDER BY display_order ASC, created_at DESC",
            [$businessId]
        );
        
        $imagesData = array_map(function($image) {
            return [
                'id' => $image['id'],
                'url' => UPLOAD_URL . $image['image_path'],
                'caption' => $image['caption'],
                'is_featured' => (bool)$image['is_featured'],
                'display_order' => $image['display_order']
            ];
        }, $images);
        
        header('Content-Type: application/json');
        echo json_encode(['images' => $imagesData]);
    }
    
    public function addVideo($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de negocio requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            if (empty($user['business_id']) || $user['business_id'] != $businessId) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos']);
                return;
            }
        }
        
        $videoType = $_POST['video_type'] ?? 'youtube';
        $videoUrl = $_POST['video_url'] ?? null;
        $videoTitle = $_POST['video_title'] ?? '';
        $videoPath = null;
        
        if (empty($videoTitle)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Título del video es requerido']);
            return;
        }
        
        try {
            // Si es upload, manejar archivo
            if ($videoType === 'upload' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                $videoPath = $this->uploadVideoFile($_FILES['video_file']);
            }
            
            // Insertar video
            $this->db->query(
                "INSERT INTO business_videos (business_id, video_type, video_url, video_path, title, uploaded_by, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [$businessId, $videoType, $videoUrl, $videoPath, $videoTitle, $user['id']]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Video agregado exitosamente']);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al agregar video: ' . $e->getMessage()]);
        }
    }
    
    public function getVideos($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de negocio requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            if (empty($user['business_id']) || $user['business_id'] != $businessId) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos']);
                return;
            }
        }
        
        $videos = $this->db->fetchAll(
            "SELECT id, video_type, video_url, video_path, title, description, display_order 
             FROM business_videos 
             WHERE business_id = ? AND is_active = 1 
             ORDER BY display_order ASC, created_at DESC",
            [$businessId]
        );
        
        $videosData = array_map(function($video) {
            return [
                'id' => $video['id'],
                'type' => ucfirst($video['video_type']),
                'url' => $video['video_url'],
                'path' => $video['video_path'] ? UPLOAD_URL . $video['video_path'] : null,
                'title' => $video['title'],
                'description' => $video['description'],
                'display_order' => $video['display_order']
            ];
        }, $videos);
        
        header('Content-Type: application/json');
        echo json_encode(['videos' => $videosData]);
    }
    
    public function deleteImage($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $imageId = $segments[1] ?? null;
        if (!$imageId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de imagen requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Obtener información de la imagen
        $image = $this->db->fetch(
            "SELECT bi.*, b.created_by 
             FROM business_images bi 
             JOIN businesses b ON bi.business_id = b.id 
             WHERE bi.id = ?",
            [$imageId]
        );
        
        if (!$image) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Imagen no encontrada']);
            return;
        }
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            if (empty($user['business_id']) || $user['business_id'] != $image['business_id']) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos']);
                return;
            }
        }
        
        try {
            // Eliminar archivo físico
            $fullPath = UPLOAD_PATH . $image['image_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            // Eliminar registro
            $this->db->query("DELETE FROM business_images WHERE id = ?", [$imageId]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Imagen eliminada']);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al eliminar imagen: ' . $e->getMessage()]);
        }
    }
    
    public function deleteVideo($segments = []) {
        if (!isLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        
        $videoId = $segments[1] ?? null;
        if (!$videoId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'ID de video requerido']);
            return;
        }
        
        $user = getCurrentUser();
        
        // Obtener información del video
        $video = $this->db->fetch(
            "SELECT bv.*, b.created_by 
             FROM business_videos bv 
             JOIN businesses b ON bv.business_id = b.id 
             WHERE bv.id = ?",
            [$videoId]
        );
        
        if (!$video) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Video no encontrado']);
            return;
        }
        
        // Verificar permisos
        if ($user['role'] !== 'admin') {
            if (empty($user['business_id']) || $user['business_id'] != $video['business_id']) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos']);
                return;
            }
        }
        
        try {
            // Eliminar archivo físico si existe
            if ($video['video_path']) {
                $fullPath = UPLOAD_PATH . $video['video_path'];
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            // Eliminar registro
            $this->db->query("DELETE FROM business_videos WHERE id = ?", [$videoId]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Video eliminado']);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al eliminar video: ' . $e->getMessage()]);
        }
    }
    
    private function uploadVideoFile($file) {
        $allowedTypes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo'];
        $maxSize = 100 * 1024 * 1024; // 100MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Use MP4, MPEG, MOV o AVI.');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('El archivo es muy grande. Máximo 100MB.');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('video_') . '.' . $extension;
        $uploadPath = 'videos/' . $filename;
        $fullPath = UPLOAD_PATH . $uploadPath;
        
        // Crear directorio si no existe
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception('Error al subir el archivo.');
        }
        
        return $uploadPath;
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