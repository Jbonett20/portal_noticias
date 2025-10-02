<?php
/**
 * Archivo de Seguridad - Verificación de Sesiones
 * Este archivo debe ser incluido en todas las vistas que requieren autenticación
 */

// Asegurar que las sesiones estén iniciadas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuración si no está cargada
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/config/config.php';
}

/**
 * Verificar si el usuario está autenticado
 */
function verificarSesion() {
    if (!isLoggedIn()) {
        // Limpiar cualquier sesión corrupta
        session_destroy();
        session_start();
        
        // Redirigir al login
        header('Location: ' . BASE_URL . 'login');
        exit('Acceso denegado. Debe iniciar sesión.');
    }
}

/**
 * Verificar permisos de administrador
 */
function verificarAdmin() {
    verificarSesion(); // Primero verificar que esté logueado
    
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . 'dashboard');
        exit('Acceso denegado. Se requieren permisos de administrador.');
    }
}

/**
 * Verificar permisos de editor o superior
 */
function verificarEditor() {
    verificarSesion(); // Primero verificar que esté logueado
    
    if (!isEditor()) {
        header('Location: ' . BASE_URL . 'login');
        exit('Acceso denegado. Se requieren permisos de editor.');
    }
}

/**
 * Verificar que el usuario tenga acceso a un negocio específico
 */
function verificarAccesoNegocio($businessId) {
    verificarEditor(); // Verificar que sea al menos editor
    
    $user = getCurrentUser();
    
    // Los administradores tienen acceso a todo
    if ($user['role'] === 'admin') {
        return true;
    }
    
    // Para editores, verificar business_id o created_by
    if (empty($user['business_id']) || (int)$user['business_id'] !== (int)$businessId) {
        // Verificar si creó el negocio
        require_once __DIR__ . '/config/Database.php';
        $db = Database::getInstance();
        $business = $db->fetch("SELECT created_by FROM businesses WHERE id = ?", [$businessId]);
        
        if (!$business || (int)$business['created_by'] !== (int)$user['id']) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit('Acceso denegado. No tiene permisos para este negocio.');
        }
    }
    
    return true;
}

/**
 * Refrescar tiempo de sesión (para mantener sesión activa)
 */
function refrescarSesion() {
    if (isLoggedIn()) {
        // Actualizar tiempo de última actividad
        $_SESSION['last_activity'] = time();
        
        // Verificar timeout de sesión (opcional - 2 horas)
        $timeout = 7200; // 2 horas en segundos
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            session_destroy();
            header('Location: ' . BASE_URL . 'login?timeout=1');
            exit('Sesión expirada. Por favor, inicie sesión nuevamente.');
        }
    }
}

/**
 * Limpiar sesión completamente
 */
function limpiarSesion() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}

/**
 * Verificar y refrescar sesión automáticamente
 * Esta función se ejecuta en cada carga de página
 */
function mantenimientoSesion() {
    // Refrescar tiempo de sesión si está logueado
    if (isLoggedIn()) {
        refrescarSesion();
        
        // Verificar integridad de datos de sesión
        $user = getCurrentUser();
        if (empty($user['id']) || empty($user['username'])) {
            limpiarSesion();
            header('Location: ' . BASE_URL . 'login?error=session_corrupted');
            exit('Sesión corrupta. Por favor, inicie sesión nuevamente.');
        }
    }
}

// Ejecutar mantenimiento automático de sesión
mantenimientoSesion();

/**
 * Tokens CSRF para formularios (seguridad adicional)
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generar token CSRF para uso en formularios
$csrf_token = generarTokenCSRF();
?>