<?php
// Verificar que las variables de sesión existan antes de usarlas
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Obtener la página actual para marcar el enlace activo
$current_controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-newspaper"></i> Portal de Noticias
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_controller === 'home') ? 'active' : ''; ?>" 
                       href="index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_controller === 'news') ? 'active' : ''; ?>" 
                       href="index.php?controller=news&action=index">
                        <i class="fas fa-newspaper"></i> Noticias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_controller === 'business') ? 'active' : ''; ?>" 
                       href="index.php?controller=business">
                        <i class="fas fa-building"></i> Negocios
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                <!-- Usuario logueado -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> 
                        <?php echo !empty($user_name) ? htmlspecialchars($user_name) : 'Usuario'; ?>
                        <?php if (!empty($user_role)): ?>
                        <span class="badge bg-light text-dark ms-1">
                            <?php echo ucfirst($user_role); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($user_role === 'admin' || $user_role === 'editor'): ?>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=news&action=admin">
                                <i class="fas fa-cog"></i> Gestionar Noticias
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=news&action=create">
                                <i class="fas fa-plus"></i> Nueva Noticia
                            </a>
                        </li>
                        <?php if ($user_role === 'admin'): ?>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=admin">
                                <i class="fas fa-users-cog"></i> Administración
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=dashboard">
                                <i class="fas fa-user-circle"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?controller=auth&action=logout">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <!-- Usuario no logueado -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=auth&action=login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=auth&action=register">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>