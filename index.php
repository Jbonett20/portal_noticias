<?php
require_once 'config/config.php';
require_once 'config/Database.php';

class Router {
    private $routes = [];
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function route($uri, $controller, $action) {
        $this->routes[$uri] = ['controller' => $controller, 'action' => $action];
    }
    
    public function dispatch() {
        // Verificar si se usan parámetros GET para el enrutamiento
        if (isset($_GET['controller'])) {
            $controller = ucfirst($_GET['controller']) . 'Controller';
            $action = $_GET['action'] ?? 'index';
            $segments = [];
            $this->callAction($controller, $action, $segments);
            return;
        }
        
        $uri = $this->getUri();
        
        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];
            $segments = [];
        } else {
            // Rutas dinámicas
            $segments = explode('/', trim($uri, '/'));
            
            if (empty($segments[0])) {
                $controller = 'HomeController';
                $action = 'index';
            } elseif ($segments[0] === 'login') {
                $controller = 'AuthController';
                $action = 'login';
            } elseif ($segments[0] === 'register') {
                $controller = 'AuthController';
                $action = 'register';
            } elseif ($segments[0] === 'logout') {
                $controller = 'AuthController';
                $action = 'logout';
            } elseif ($segments[0] === 'admin') {
                $controller = 'AdminController';
                
                // Determinar la acción basada en los segmentos de la URL
                if (!isset($segments[1])) {
                    $action = 'index';
                } elseif ($segments[1] === 'users') {
                    $action = 'users';
                } elseif ($segments[1] === 'create-user') {
                    $action = 'createUser';
                } elseif ($segments[1] === 'edit-user' && isset($segments[2])) {
                    $action = 'editUser';
                } elseif ($segments[1] === 'update-user' && isset($segments[2])) {
                    $action = 'updateUser';
                } elseif ($segments[1] === 'delete-user' && isset($segments[2])) {
                    $action = 'deleteUser';
                } elseif ($segments[1] === 'news-list') {
                    $action = 'newsList';
                } elseif ($segments[1] === 'news-create') {
                    $action = 'newsCreate';
                } elseif ($segments[1] === 'news-edit' && isset($segments[2])) {
                    $action = 'newsEdit';
                } elseif ($segments[1] === 'news-update' && isset($segments[2])) {
                    $action = 'newsUpdate';
                } elseif ($segments[1] === 'news-delete' && isset($segments[2])) {
                    $action = 'newsDelete';
                } elseif ($segments[1] === 'business-list') {
                    $action = 'businessList';
                } elseif ($segments[1] === 'business-create') {
                    $action = 'businessCreate';
                } elseif ($segments[1] === 'business-edit' && isset($segments[2])) {
                    $action = 'businessEdit';
                } elseif ($segments[1] === 'business-update' && isset($segments[2])) {
                    $action = 'businessUpdate';
                } elseif ($segments[1] === 'business-delete' && isset($segments[2])) {
                    $action = 'businessDelete';
                } else {
                    $action = 'index';
                }
            } elseif ($segments[0] === 'dashboard') {
                $controller = 'DashboardController';
                if (isset($segments[1])) {
                    if ($segments[1] === 'toggle-business-status') {
                        $action = 'toggleBusinessStatus';
                    } elseif ($segments[1] === 'upload-image') {
                        $action = 'uploadImage';
                    } elseif ($segments[1] === 'get-images') {
                        $action = 'getImages';
                    } elseif ($segments[1] === 'add-video') {
                        $action = 'addVideo';
                    } elseif ($segments[1] === 'get-videos') {
                        $action = 'getVideos';
                    } elseif ($segments[1] === 'delete-image') {
                        $action = 'deleteImage';
                    } elseif ($segments[1] === 'delete-video') {
                        $action = 'deleteVideo';
                    } else {
                        $action = $segments[1];
                    }
                } else {
                    $action = 'index';
                }
            } elseif ($segments[0] === 'news') {
                $controller = 'NewsController';
                $action = isset($segments[1]) ? 'show' : 'index';
            } elseif ($segments[0] === 'business') {
                $controller = 'BusinessController';
                $action = isset($segments[1]) ? 'show' : 'index';
            } elseif ($segments[0] === 'section') {
                $controller = 'SectionController';
                $action = isset($segments[1]) ? 'show' : 'index';
            } elseif ($segments[0] === 'search') {
                $controller = 'HomeController';
                $action = 'search';
            } else {
                $this->showError('Página no encontrada', 404);
                return;
            }
        }
        
        $this->callAction($controller, $action, $segments);
    }
    
    private function callAction($controller, $action, $segments = []) {
        $controllerFile = "controllers/{$controller}.php";
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller($this->db);
                
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action($segments);
                } else {
                    $this->showError('Acción no encontrada', 404);
                }
            } else {
                $this->showError('Controlador no encontrado', 500);
            }
        } else {
            $this->showError('Archivo del controlador no encontrado', 500);
        }
    }
    
    private function getUri() {
        $uri = $_SERVER['REQUEST_URI'];
        $basePath = '/clone/portal_noticias';
        $uri = str_replace($basePath, '', $uri);
        return parse_url($uri, PHP_URL_PATH);
    }
    
    private function showError($message, $code = 500) {
        http_response_code($code);
        
        $viewFile = __DIR__ . '/views/errors/content.php';
        ob_start();
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                            <h1 class="h2 mt-3">Error <?= $code ?></h1>
                            <p class="text-muted"><?= htmlspecialchars($message) ?></p>
                            <a href="<?= BASE_URL ?>" class="btn btn-primary">
                                <i class="bi bi-house"></i> Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        file_put_contents($viewFile, $content);
        
        $title = "Error {$code}";
        include 'views/layout/main.php';
    }
}

// Inicializar router
$router = new Router();

// Definir rutas específicas
$router->route('/login', 'AuthController', 'login');
$router->route('/register', 'AuthController', 'register');
$router->route('/logout', 'AuthController', 'logout');

// Ejecutar router
$router->dispatch();
?>