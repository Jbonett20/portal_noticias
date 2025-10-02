<?php
require_once 'models/User.php';
require_once 'models/Business.php';

class AdminController {
    private $db;
    private $userModel;
    private $businessModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->userModel = new User($database);
        $this->businessModel = new Business($database);
    }
    
    public function index($segments = []) {
        // Verificar permisos de administrador
        require_once __DIR__ . '/../seguridad.php';
        verificarAdmin();
        
        // Obtener estadísticas
        $stats = [
            'total_users' => $this->db->fetch("SELECT COUNT(*) as count FROM users")['count'],
            'total_businesses' => $this->db->fetch("SELECT COUNT(*) as count FROM businesses")['count'],
            'total_news' => $this->db->fetch("SELECT COUNT(*) as count FROM news")['count'],
            'users_by_role' => $this->db->fetchAll("SELECT role, COUNT(*) as count FROM users GROUP BY role")
        ];
        
        $data = [
            'title' => 'Panel de Administración',
            'stats' => $stats
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
        
        $data = [
            'title' => 'Gestionar Usuarios',
            'users' => $users
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