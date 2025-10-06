<?php
require_once 'models/User.php';
require_once 'models/Business.php';
require_once 'models/News.php';
require_once 'models/Section.php';

class AdminController {
    private $db;
    private $userModel;
    private $businessModel;
    private $newsModel;
    private $sectionModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->userModel = new User($database);
        $this->businessModel = new Business($database);
        $this->newsModel = new News($database);
        $this->sectionModel = new Section($database);
    }
    
    public function index($segments = []) {
        // Verificar permisos de administrador
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        // Obtener estadísticas completas
        $stats = [
            'total_users' => $this->db->fetch("SELECT COUNT(*) as count FROM users")['count'],
            'total_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses")['count'],
            'total_news' => $this->db->fetch("SELECT COUNT(*) as count FROM news")['count'],
            'total_sections' => $this->db->fetch("SELECT COUNT(*) as count FROM sections")['count'],
            'users_by_role' => $this->db->fetchAll("SELECT role, COUNT(*) as count FROM users GROUP BY role"),
            'businesses_published' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_published = 1")['count'],
            'businesses_pending' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses WHERE is_published = 0")['count'],
            'news_published' => $this->db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 1")['count'],
            'news_draft' => $this->db->fetch("SELECT COUNT(*) as count FROM news WHERE is_published = 0")['count']
        ];
        
        // Obtener secciones para el modal de crear negocio
        $sections = $this->sectionModel->findAll();
        
        $data = [
            'title' => 'Panel de Administración',
            'stats' => $stats,
            'sections' => $sections
        ];
        
        $this->render('admin/index', $data);
    }
    
    public function users($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $users = $this->db->fetchAll("
            SELECT u.*, b.name as business_name 
            FROM users u 
            LEFT JOIN businesses b ON u.business_id = b.id 
            ORDER BY u.created_at DESC
        ");
        
        // Obtener negocios disponibles para el modal de edición
        $businesses = $this->businessModel->findAll();
        
        $data = [
            'title' => 'Gestionar Usuarios',
            'users' => $users,
            'businesses' => $businesses
        ];
        
        $this->render('admin/users', $data);
    }
    
    public function createUser($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $fullName = trim($_POST['full_name'] ?? '');
            $role = $_POST['role'] ?? 'user';
            $businessId = !empty($_POST['business_id']) ? $_POST['business_id'] : null;
            
            // Validaciones
            if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } elseif (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } elseif ($this->userModel->findByUsername($username)) {
                $error = 'El nombre de usuario ya existe';
            } elseif ($this->userModel->findByEmail($email)) {
                $error = 'El email ya está registrado';
            } else {
                try {
                    $userId = $this->userModel->create([
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'full_name' => $fullName,
                        'role' => $role,
                        'business_id' => $businessId
                    ]);
                    
                    $success = 'Usuario creado exitosamente.';
                } catch (Exception $e) {
                    $error = 'Error al crear el usuario: ' . $e->getMessage();
                }
            }
        }
        
        // Obtener negocios disponibles
        $businesses = $this->businessModel->findAll();
        
        $data = [
            'title' => 'Crear Usuario',
            'error' => $error,
            'success' => $success,
            'businesses' => $businesses
        ];
        
        $this->render('admin/create-user', $data);
    }
    
    public function editUser($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $userId = $segments[1] ?? null;
        if (!$userId) {
            redirect(BASE_URL . 'admin/users');
        }
        
        $user = $this->userModel->findById($userId);
        if (!$user) {
            redirect(BASE_URL . 'admin/users');
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $fullName = trim($_POST['full_name'] ?? '');
            $role = $_POST['role'] ?? 'user';
            $businessId = !empty($_POST['business_id']) ? $_POST['business_id'] : null;
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($fullName)) {
                $error = 'Email y nombre completo son obligatorios';
            } else {
                try {
                    $updateData = [
                        'email' => $email,
                        'full_name' => $fullName,
                        'role' => $role,
                        'business_id' => $businessId
                    ];
                    
                    // Solo actualizar contraseña si se proporcionó una nueva
                    if (!empty($password)) {
                        if (strlen($password) < 6) {
                            $error = 'La contraseña debe tener al menos 6 caracteres';
                        } else {
                            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
                        }
                    }
                    
                    if (empty($error)) {
                        $this->userModel->update($userId, $updateData);
                        $success = 'Usuario actualizado exitosamente.';
                        $user = $this->userModel->findById($userId); // Recargar datos
                    }
                } catch (Exception $e) {
                    $error = 'Error al actualizar el usuario: ' . $e->getMessage();
                }
            }
        }
        
        // Obtener negocios disponibles
        $businesses = $this->businessModel->findAll();
        
        $data = [
            'title' => 'Editar Usuario',
            'user' => $user,
            'error' => $error,
            'success' => $success,
            'businesses' => $businesses
        ];
        
        $this->render('admin/edit-user', $data);
    }
    
    public function deleteUser($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $userId = $segments[1] ?? null;
        if (!$userId) {
            redirect(BASE_URL . 'admin/users');
        }
        
        // No permitir que el admin se elimine a sí mismo
        $currentUser = getCurrentUser();
        if ($currentUser['id'] == $userId) {
            $_SESSION['error'] = 'No puedes eliminar tu propia cuenta';
            redirect(BASE_URL . 'admin/users');
        }
        
        try {
            $this->userModel->delete($userId);
            $_SESSION['success'] = 'Usuario eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el usuario: ' . $e->getMessage();
        }
        
        redirect(BASE_URL . 'admin/users');
    }
    
    public function updateUser($segments = []) {
        try {
            require_once __DIR__ . '/../seguridad.php';
            verificarAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $_SESSION['error'] = 'Método no permitido';
                redirect(BASE_URL . 'admin/users');
                return;
            }
            
            // Obtener userId del formulario
            $userId = $_POST['user_id'] ?? null;
            if (!$userId) {
                $_SESSION['error'] = 'ID de usuario no válido';
                redirect(BASE_URL . 'admin/users');
                return;
            }
            
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $roleString = $_POST['role'] ?? 'user';
            $isActive = isset($_POST['status']) && $_POST['status'] === 'active' ? 1 : 0;
            $businessId = !empty($_POST['business_id']) ? $_POST['business_id'] : null;
            
            // Convertir rol string a número
            $role = 3; // valor por defecto para usuario básico
            if ($roleString === 'admin') {
                $role = 1;
            } elseif ($roleString === 'editor') {
                $role = 2;
            } else {
                $role = 3; // usuario básico
            }
            
            // Validaciones
            if (empty($username) || empty($email)) {
                $_SESSION['error'] = 'Username y email son obligatorios';
                redirect(BASE_URL . 'admin/users');
                return;
            }
            
            // Obtener usuario actual para comparar cambios
            $currentUser = $this->userModel->findById($userId);
            if (!$currentUser) {
                $_SESSION['error'] = 'Usuario no encontrado';
                redirect(BASE_URL . 'admin/users');
                return;
            }
            
            // Solo validar username si cambió
            if ($username !== $currentUser['username']) {
                $existingUser = $this->userModel->findByUsername($username);
                if ($existingUser) {
                    $_SESSION['error'] = 'El nombre de usuario ya existe';
                    redirect(BASE_URL . 'admin/users');
                    return;
                }
            }
            
            // Solo validar email si cambió
            if ($email !== $currentUser['email']) {
                $existingEmail = $this->userModel->findByEmail($email);
                if ($existingEmail) {
                    $_SESSION['error'] = 'El email ya está registrado';
                    redirect(BASE_URL . 'admin/users');
                    return;
                }
            }
            
            $updateData = [
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'is_active' => $isActive,
                'business_id' => $businessId,
                'full_name' => $currentUser['full_name'] // Mantener el full_name actual
            ];
            
            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                    redirect(BASE_URL . 'admin/users');
                    return;
                }
                $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $result = $this->userModel->update($userId, $updateData);
            
            if ($result) {
                $_SESSION['success'] = 'Usuario actualizado correctamente';
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el usuario';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error del servidor: ' . $e->getMessage();
        } catch (Error $e) {
            $_SESSION['error'] = 'Error fatal: ' . $e->getMessage();
        }
        
        redirect(BASE_URL . 'admin/users');
    }
    
    // ===== GESTIÓN DE NOTICIAS =====
    
    public function newsList() {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        // Obtener todas las noticias con información del autor y negocio
        $news = $this->newsModel->getAllWithDetails();
        
        $data = [
            'title' => 'Gestionar Noticias',
            'news' => $news
        ];
        
        $this->render('admin/news-list', $data);
    }
    
    public function newsCreate() {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $summary = trim($_POST['summary'] ?? $_POST['excerpt'] ?? '');
            $status = $_POST['status'] ?? 'draft';
            
            if (empty($title) || empty($content)) {
                $error = 'Título y contenido son obligatorios';
            } else {
                try {
                    // Obtener usuario actual
                    $currentUser = getCurrentUser();
                    
                    $newsData = [
                        'title' => $title,
                        'content' => $content,
                        'summary' => $summary,
                        'author_id' => $currentUser['id'],
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Manejar imágenes si se subieron
                    if (!empty($_FILES['images']['name'][0])) {
                        $images = $this->handleImageUploads($_FILES['images']);
                        if ($images) {
                            $newsData['image_url'] = $images[0]; // Primera imagen como principal
                        }
                    }
                    
                    $newsId = $this->newsModel->create($newsData);
                    $success = 'Noticia creada exitosamente.';
                    
                    // Redirigir después de crear
                    $_SESSION['success'] = $success;
                    redirect(BASE_URL . 'admin/news-list');
                    
                } catch (Exception $e) {
                    $error = 'Error al crear la noticia: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Crear Noticia',
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('admin/news-create', $data);
    }
    
    public function newsEdit($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $newsId = $segments[1] ?? null;
        if (!$newsId) {
            redirect(BASE_URL . 'admin/news-list');
        }
        
        $news = $this->newsModel->findById($newsId);
        if (!$news) {
            redirect(BASE_URL . 'admin/news-list');
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $summary = trim($_POST['summary'] ?? $_POST['excerpt'] ?? '');
            $status = $_POST['status'] ?? 'draft';
            
            if (empty($title) || empty($content)) {
                $error = 'Título y contenido son obligatorios';
            } else {
                try {
                    $updateData = [
                        'title' => $title,
                        'content' => $content,
                        'summary' => $summary,
                        'status' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Manejar nuevas imágenes si se subieron
                    if (!empty($_FILES['images']['name'][0])) {
                        $images = $this->handleImageUploads($_FILES['images']);
                        if ($images) {
                            $updateData['image_url'] = $images[0];
                        }
                    }
                    
                    $this->newsModel->update($newsId, $updateData);
                    $success = 'Noticia actualizada exitosamente.';
                    $news = $this->newsModel->findById($newsId); // Recargar datos
                    
                } catch (Exception $e) {
                    $error = 'Error al actualizar la noticia: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Editar Noticia',
            'news' => $news,
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('admin/news-edit', $data);
    }
    
    public function newsUpdate($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        header('Content-Type: application/json');
        
        $newsId = $segments[1] ?? null;
        if (!$newsId) {
            echo json_encode(['success' => false, 'message' => 'ID de noticia no válido']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $sectionId = $_POST['section_id'] ?? null;
        $status = $_POST['status'] ?? 'draft';
        
        // Validaciones
        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Título y contenido son obligatorios']);
            return;
        }
        
        try {
            $updateData = [
                'title' => $title,
                'summary' => $summary,
                'content' => $content,
                'section_id' => $sectionId,
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Manejar imagen si se subió una nueva
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/news/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $updateData['image'] = 'uploads/news/' . $fileName;
                }
            }
            
            $this->newsModel->update($newsId, $updateData);
            echo json_encode(['success' => true, 'message' => 'Noticia actualizada correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }
    
    public function newsDelete($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $newsId = $segments[1] ?? null;
        if (!$newsId) {
            redirect(BASE_URL . 'admin/news-list');
        }
        
        try {
            // Obtener datos de la noticia para eliminar archivos
            $news = $this->newsModel->findById($newsId);
            
            // Eliminar archivos de imagen si existen
            if ($news && !empty($news['image_url'])) {
                $imagePath = __DIR__ . '/../uploads/news/' . basename($news['image_url']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->newsModel->delete($newsId);
            $_SESSION['success'] = 'Noticia eliminada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar la noticia: ' . $e->getMessage();
        }
        
        redirect(BASE_URL . 'admin/news-list');
    }
    
    // ===== GESTIÓN DE NEGOCIOS =====
    
    public function businessList() {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $businesses = $this->businessModel->getAllWithDetails();
        $sections = $this->sectionModel->findAll();
        
        $data = [
            'title' => 'Gestionar Negocios',
            'businesses' => $businesses,
            'sections' => $sections
        ];
        
        $this->render('admin/business-list', $data);
    }
    
    public function businessCreate() {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $shortDescription = trim($_POST['short_description'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $website = trim($_POST['website'] ?? '');
            $sectionId = $_POST['section_id'] ?? null;
            $createdBy = getCurrentUser()['id']; // Usar el usuario actual como creador
            
            if (empty($name) || empty($description) || empty($sectionId)) {
                $_SESSION['error'] = 'Nombre, descripción y sección son obligatorios';
            } else {
                try {
                    $businessData = [
                        'name' => $name,
                        'description' => $description,
                        'short_description' => $shortDescription,
                        'address' => $address,
                        'phone' => $phone,
                        'email' => $email,
                        'website' => $website,
                        'section_id' => $sectionId,
                        'created_by' => $createdBy,
                        'is_published' => 1,
                        'status' => 1
                    ];
                    
                    // Manejar logo si se subió
                    if (!empty($_FILES['logo']['name'])) {
                        $logo = $this->handleLogoUpload($_FILES['logo']);
                        if ($logo) {
                            $businessData['logo_path'] = $logo;
                        }
                    }
                    
                    $businessId = $this->businessModel->create($businessData);
                    $_SESSION['success'] = 'Negocio creado exitosamente';
                    
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Error al crear el negocio: ' . $e->getMessage();
                }
            }
            
            // Siempre redirigir a la lista de negocios después de POST
            redirect(BASE_URL . 'admin/business-list');
            return;
        }
        
        // Obtener secciones y usuarios para el formulario tradicional
        $sections = $this->sectionModel->findAll();
        $users = $this->userModel->findAll();
        
        $data = [
            'title' => 'Crear Negocio',
            'error' => $error,
            'success' => $success,
            'sections' => $sections,
            'users' => $users
        ];
        
        $this->render('admin/business-create', $data);
    }
    
    // Método auxiliar para manejar subida de imágenes
    private function handleImageUploads($files) {
        $uploadDir = __DIR__ . '/../uploads/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadedFiles = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            
            $fileType = $files['type'][$i];
            if (!in_array($fileType, $allowedTypes)) continue;
            
            $fileName = uniqid() . '_' . basename($files['name'][$i]);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                $uploadedFiles[] = 'uploads/news/' . $fileName;
            }
        }
        
        return $uploadedFiles;
    }
    
    // Método auxiliar para manejar subida de logos
    private function handleLogoUpload($file) {
        $uploadDir = __DIR__ . '/../uploads/logos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if (!in_array($file['type'], $allowedTypes)) return null;
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'logos/' . $fileName;
        }
        
        return null;
    }
    
    public function businessEdit($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            redirect(BASE_URL . 'admin/business-list');
        }
        
        $business = $this->businessModel->findById($businessId);
        if (!$business) {
            redirect(BASE_URL . 'admin/business-list');
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $shortDescription = trim($_POST['short_description'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $website = trim($_POST['website'] ?? '');
            $sectionId = $_POST['section_id'] ?? null;
            $createdBy = $_POST['created_by'] ?? null;
            
            if (empty($name) || empty($description) || empty($sectionId)) {
                $error = 'Nombre, descripción y sección son obligatorios';
            } else {
                try {
                    $updateData = [
                        'name' => $name,
                        'description' => $description,
                        'short_description' => $shortDescription,
                        'address' => $address,
                        'phone' => $phone,
                        'email' => $email,
                        'website' => $website,
                        'section_id' => $sectionId,
                        'created_by' => $createdBy
                    ];
                    
                    // Manejar nuevo logo si se subió
                    if (!empty($_FILES['logo']['name'])) {
                        $logo = $this->handleLogoUpload($_FILES['logo']);
                        if ($logo) {
                            $updateData['logo_path'] = $logo;
                        }
                    }
                    
                    $this->businessModel->update($businessId, $updateData);
                    $success = 'Negocio actualizado exitosamente.';
                    $business = $this->businessModel->findById($businessId); // Recargar datos
                    
                } catch (Exception $e) {
                    $error = 'Error al actualizar el negocio: ' . $e->getMessage();
                }
            }
        }
        
        // Obtener secciones y usuarios
        $sections = $this->sectionModel->findAll();
        $users = $this->userModel->findAll();
        
        $data = [
            'title' => 'Editar Negocio',
            'business' => $business,
            'error' => $error,
            'success' => $success,
            'sections' => $sections,
            'users' => $users
        ];
        
        $this->render('admin/business-edit', $data);
    }
    
    public function businessUpdate($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        header('Content-Type: application/json');
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            echo json_encode(['success' => false, 'message' => 'ID de negocio no válido']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $sectionId = $_POST['section_id'] ?? null;
        
        // Validaciones
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'El nombre del negocio es obligatorio']);
            return;
        }
        
        // Verificar si el nombre ya existe (excepto para este negocio)
        $existingBusiness = $this->businessModel->findByName($name);
        if ($existingBusiness && $existingBusiness['id'] != $businessId) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un negocio con ese nombre']);
            return;
        }
        
        try {
            $updateData = [
                'name' => $name,
                'description' => $description,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'website' => $website,
                'section_id' => $sectionId,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Manejar logo si se subió uno nuevo
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                    $updateData['logo_path'] = 'logos/' . $fileName;
                }
            }
            
            $this->businessModel->update($businessId, $updateData);
            echo json_encode(['success' => true, 'message' => 'Negocio actualizado correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }
    
    public function businessDelete($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        $businessId = $segments[1] ?? null;
        if (!$businessId) {
            redirect(BASE_URL . 'admin/business-list');
        }
        
        try {
            // Obtener datos del negocio para eliminar archivos
            $business = $this->businessModel->findById($businessId);
            
            // Eliminar archivo de logo si existe
            if ($business && !empty($business['logo_path'])) {
                $logoPath = __DIR__ . '/../' . $business['logo_path'];
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }
            
            $this->businessModel->delete($businessId);
            $_SESSION['success'] = 'Negocio eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el negocio: ' . $e->getMessage();
        }
        
        redirect(BASE_URL . 'admin/business-list');
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