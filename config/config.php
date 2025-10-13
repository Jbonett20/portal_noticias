<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'portal_noticias');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la aplicación
// BASE_URL dinámica según ubicación y dominio
// BASE_URL dinámica y limpia, sin subcarpetas duplicadas
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$base_path = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$base_path = preg_replace('#/index\.php$#', '', $base_path); // Elimina index.php del final
$base_url .= rtrim($base_path, '/') . '/';
define('BASE_URL', $base_url);
define('SITE_NAME', 'Portal de Noticias');

// Configuración de uploads
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Configuración de sesiones
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 3600); // 1 hora
    session_start();
}

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Zona horaria
date_default_timezone_set('America/Bogota');

// Funciones auxiliares
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function isEditor() {
    $user = getCurrentUser();
    return $user && in_array($user['role'], ['admin', 'editor']);
}

function canEditBusiness($businessId) {
    $user = getCurrentUser();
    if (!$user) return false;
    if ($user['role'] === 'admin') return true;
    
    // Verificar si el usuario es dueño del negocio
    require_once __DIR__ . '/Database.php';
    $db = Database::getInstance();
    $business = $db->fetch("SELECT created_by FROM businesses WHERE id = ?", [$businessId]);
    
    return $business && $business['created_by'] == $user['id'];
}

function redirectToLogin() {
    header('Location: ' . BASE_URL . 'login');
    exit();
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function formatDate($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

function truncateText($text, $length = 150) {
    if (!is_string($text)) $text = (string)($text ?? '');
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function generateSlug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// Crear directorio de uploads si no existe
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
    mkdir(UPLOAD_PATH . 'news/', 0755, true);
    mkdir(UPLOAD_PATH . 'businesses/', 0755, true);
    mkdir(UPLOAD_PATH . 'logos/', 0755, true);
}
?>