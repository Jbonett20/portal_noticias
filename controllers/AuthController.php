<?php
require_once 'models/User.php';

class AuthController {
    private $db;
    private $userModel;
    
    public function __construct($database) {
        $this->db = $database;
        $this->userModel = new User($database);
    }
    
    public function login($segments = []) {
        if (isLoggedIn()) {
            redirect(BASE_URL . 'dashboard');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar token CSRF
            require_once __DIR__ . '/../seguridad.php';
            $token = $_POST['csrf_token'] ?? '';
            if (!verificarTokenCSRF($token)) {
                $error = 'Token de seguridad inválido. Por favor, intente nuevamente.';
            } else {
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $error = 'Por favor ingrese usuario y contraseña';
                } else {
                    $user = $this->userModel->authenticate($username, $password);
                    
                    if ($user) {
                        // Convertir rol numérico a string
                        $roleMap = [
                            1 => 'admin',
                            2 => 'editor'
                        ];
                        $roleString = $roleMap[$user['role']] ?? 'editor';
                        
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'email' => $user['email'],
                            'full_name' => $user['full_name'],
                            'role' => $roleString,
                            'business_id' => $user['business_id']
                        ];
                        
                        // Redirigir según el rol
                        if ($roleString === 'admin') {
                            redirect(BASE_URL . 'admin');
                        } else {
                            redirect(BASE_URL . 'dashboard');
                        }
                    } else {
                        $error = 'Usuario o contraseña incorrectos';
                    }
                }
            }
        }        $data = [
            'title' => 'Iniciar Sesión',
            'error' => $error
        ];
        
        $this->render('auth/login', $data);
    }
    
    public function register($segments = []) {
        if (isLoggedIn()) {
            redirect(BASE_URL . 'dashboard');
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullName = trim($_POST['full_name'] ?? '');
            
            // Validaciones
            if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
                $error = 'Todos los campos son obligatorios';
            } elseif ($password !== $confirmPassword) {
                $error = 'Las contraseñas no coinciden';
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
                        'role' => 'user' // Rol básico para registro público
                    ]);
                    
                    $success = 'Cuenta creada exitosamente. Puede iniciar sesión.';
                } catch (Exception $e) {
                    $error = 'Error al crear la cuenta: ' . $e->getMessage();
                }
            }
        }
        
        $data = [
            'title' => 'Registrarse',
            'error' => $error,
            'success' => $success
        ];
        
        $this->render('auth/register', $data);
    }
    
    public function logout($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        limpiarSesion();
        redirect(BASE_URL . 'login?logout=success');
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