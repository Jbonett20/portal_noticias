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
    
    public function toggleBusinessStatus($segments = []) {
        if (!isLoggedIn()) {
            redirectToLogin();
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
            $business = $this->db->fetchRow(
                "SELECT * FROM businesses WHERE id = ? AND created_by = ?",
                [$businessId, $user['id']]
            );
            if (!$business) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['error' => 'No tienes permisos para modificar este negocio']);
                return;
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
            redirectToLogin();
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
            $business = $this->db->fetchRow(
                "SELECT * FROM businesses WHERE id = ? AND created_by = ?",
                [$businessId, $user['id']]
            );
            if (!$business) {
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
            $uploadPath = $this->uploadBusinessImage($_FILES, 'image');
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
    
    public function index($segments = []) {
        if (!isLoggedIn()) {
            redirectToLogin();
        }
        
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
            // Para editores/autores, solo mostrar SUS negocios
            $businesses = $this->db->fetchAll(
                "SELECT b.*, s.title as section_title,
                        CASE WHEN b.is_open = 1 THEN 'open' ELSE 'closed' END as display_status,
                        b.closed_reason
                 FROM businesses b 
                 LEFT JOIN sections s ON b.section_id = s.id 
                 WHERE b.created_by = ? OR ? IN (
                     SELECT user_id FROM users WHERE business_id = b.id
                 )
                 ORDER BY b.status DESC, b.is_open DESC, b.created_at DESC",
                [$user['id'], $user['id']]
            );
        }
        
        // Obtener estadísticas
        $stats = [];
        if ($user['role'] === 'admin') {
            $stats = [
                'total_businesses' => $this->db->fetchRow("SELECT COUNT(*) as count FROM businesses")['count'],
                'open_businesses' => $this->db->fetchRow("SELECT COUNT(*) as count FROM businesses WHERE is_open = 1")['count'],
                'closed_businesses' => $this->db->fetchRow("SELECT COUNT(*) as count FROM businesses WHERE is_open = 0")['count'],
                'active_businesses' => $this->db->fetchRow("SELECT COUNT(*) as count FROM businesses WHERE status = 'active'")['count']
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
        
        $action = $segments[1] ?? 'list';
        $businessId = $segments[2] ?? null;
        
        switch ($action) {
            case 'create':
                $this->createBusiness();
                break;
            case 'edit':
                $this->editBusiness($businessId);
                break;
            case 'delete':
                $this->deleteBusiness($businessId);
                break;
            case 'images':
                $this->manageImages($businessId);
                break;
            default:
                $this->listBusinesses();
        }
    }
    
    private function createBusiness() {
        $user = getCurrentUser();
        $sections = $this->sectionModel->getAll();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sectionId = $_POST['section_id'] ?? '';
            $shortDescription = trim($_POST['short_description'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $mission = trim($_POST['mission'] ?? '');
            $vision = trim($_POST['vision'] ?? '');
            $website = trim($_POST['website'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            
            if (empty($name) || empty($sectionId)) {
                $error = 'El nombre y la sección son obligatorios';
            } else {
                try {
                    $logoPath = null;
                    
                    // Manejar upload del logo
                    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                        $logoPath = $this->uploadLogo($_FILES['logo']);
                    }
                    
                    $businessId = $this->businessModel->create([
                        'section_id' => $sectionId,
                        'name' => $name,
                        'short_description' => $shortDescription,
                        'description' => $description,
                        'mission' => $mission,
                        'vision' => $vision,
                        'logo_path' => $logoPath,
                        'website' => $website,
                        'phone' => $phone,
                        'address' => $address,
                        'is_published' => 1,
                        'created_by' => $user['id']
                    ]);
                    
                    redirect(BASE_URL . 'dashboard/business/edit/' . $businessId . '?success=created');
                } catch (Exception $e) {
                    $error = 'Error al crear el negocio: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Crear Negocio',
            'sections' => $sections,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('dashboard/business/create', $data);
    }
    
    private function editBusiness($businessId) {
        if (!$businessId) {
            redirect(BASE_URL . 'dashboard');
        }
        
        if (!canEditBusiness($businessId)) {
            die('No tienes permisos para editar este negocio');
        }
        
        $business = $this->businessModel->findById($businessId);
        if (!$business) {
            redirect(BASE_URL . 'dashboard');
        }
        
        $sections = $this->sectionModel->getAll();
        $error = '';
        $success = $_GET['success'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sectionId = $_POST['section_id'] ?? '';
            $shortDescription = trim($_POST['short_description'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $mission = trim($_POST['mission'] ?? '');
            $vision = trim($_POST['vision'] ?? '');
            $website = trim($_POST['website'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $isPublished = isset($_POST['is_published']) ? 1 : 0;
            
            if (empty($name) || empty($sectionId)) {
                $error = 'El nombre y la sección son obligatorios';
            } else {
                try {
                    $logoPath = $business['logo_path'];
                    
                    // Manejar upload del logo
                    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                        $logoPath = $this->uploadLogo($_FILES['logo']);
                        
                        // Eliminar logo anterior si existe
                        if ($business['logo_path'] && file_exists(UPLOAD_PATH . $business['logo_path'])) {
                            unlink(UPLOAD_PATH . $business['logo_path']);
                        }
                    }
                    
                    $this->businessModel->update($businessId, [
                        'section_id' => $sectionId,
                        'name' => $name,
                        'short_description' => $shortDescription,
                        'description' => $description,
                        'mission' => $mission,
                        'vision' => $vision,
                        'logo_path' => $logoPath,
                        'website' => $website,
                        'phone' => $phone,
                        'address' => $address,
                        'is_published' => $isPublished
                    ]);
                    
                    $success = 'Negocio actualizado exitosamente';
                    
                    // Recargar datos
                    $business = $this->businessModel->findById($businessId);
                } catch (Exception $e) {
                    $error = 'Error al actualizar el negocio: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Editar: ' . $business['name'],
            'business' => $business,
            'sections' => $sections,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('dashboard/business/edit', $data);
    }
    
    private function manageImages($businessId) {
        if (!$businessId) {
            redirect(BASE_URL . 'dashboard');
        }
        
        if (!canEditBusiness($businessId)) {
            die('No tienes permisos para editar este negocio');
        }
        
        $business = $this->businessModel->findById($businessId);
        if (!$business) {
            redirect(BASE_URL . 'dashboard');
        }
        
        $error = '';
        $success = '';
        
        // Manejar upload de nuevas imágenes
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
            $user = getCurrentUser();
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    try {
                        $imagePath = $this->uploadBusinessImage($_FILES['images'], $key);
                        $caption = $_POST['captions'][$key] ?? '';
                        
                        $this->businessModel->addImage($businessId, $imagePath, $caption, 0, $user['id']);
                    } catch (Exception $e) {
                        $error = 'Error al subir imagen: ' . $e->getMessage();
                    }
                }
            }
            
            if (!$error) {
                $success = 'Imágenes subidas exitosamente';
            }
        }
        
        // Eliminar imagen
        if (isset($_GET['delete_image'])) {
            $imageId = $_GET['delete_image'];
            try {
                $image = $this->db->fetch("SELECT * FROM business_images WHERE id = ? AND business_id = ?", [$imageId, $businessId]);
                if ($image) {
                    // Eliminar archivo
                    if (file_exists(UPLOAD_PATH . $image['image_path'])) {
                        unlink(UPLOAD_PATH . $image['image_path']);
                    }
                    
                    // Eliminar de BD
                    $this->db->delete('business_images', 'id = ?', $imageId);
                    $success = 'Imagen eliminada exitosamente';
                }
            } catch (Exception $e) {
                $error = 'Error al eliminar imagen: ' . $e->getMessage();
            }
        }
        
        $images = $this->businessModel->getImages($businessId);
        
        $data = [
            'title' => 'Gestionar Imágenes: ' . $business['name'],
            'business' => $business,
            'images' => $images,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('dashboard/business/images', $data);
    }
    
    private function uploadLogo($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Use JPG, PNG o GIF.');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('El archivo es demasiado grande. Máximo 2MB.');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . uniqid() . '.' . $extension;
        $relativePath = 'logos/' . $filename;
        $fullPath = UPLOAD_PATH . $relativePath;
        
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception('Error al subir el archivo');
        }
        
        return $relativePath;
    }
    
    private function uploadBusinessImage($files, $key) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($files['type'][$key], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Use JPG, PNG o GIF.');
        }
        
        if ($files['size'][$key] > $maxSize) {
            throw new Exception('El archivo es demasiado grande. Máximo 5MB.');
        }
        
        $extension = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
        $filename = 'business_' . uniqid() . '.' . $extension;
        $relativePath = 'businesses/' . $filename;
        $fullPath = UPLOAD_PATH . $relativePath;
        
        if (!move_uploaded_file($files['tmp_name'][$key], $fullPath)) {
            throw new Exception('Error al subir el archivo');
        }
        
        return $relativePath;
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