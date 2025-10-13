<script>
function openCreateNewsModal() {
    // Busca el modal en la página de noticias y lo abre si existe
    if (window.bootstrap && document.getElementById('createNewsModal')) {
        const modal = new bootstrap.Modal(document.getElementById('createNewsModal'));
        modal.show();
    } else {
        // Redirige de forma dinámica usando la variable BASE_URL generada por PHP
        window.location.href = "<?= BASE_URL ?>admin/news-list";
    }
}
</script>
<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Panel de Administración - ' . SITE_NAME;
ob_start();
?>

<div class="container-fluid py-4">
    <!-- Header del Admin -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">
                        <i class="bi bi-shield-check text-danger"></i> Panel de Administración
                    </h1>
                    <p class="text-muted mb-0">Control total del portal de noticias</p>
                </div>
                <div class="badge bg-danger fs-6 px-3 py-2">
                    <i class="bi bi-person-gear"></i> Administrador
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-3 p-3">
                                <i class="bi bi-people text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1"><?= $stats['total_users'] ?></h5>
                            <p class="card-text text-muted mb-0">Usuarios Totales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-3 p-3">
                                <i class="bi bi-shop text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1"><?= $stats['total_businesses'] ?></h5>
                            <p class="card-text text-muted mb-0">Negocios</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-3 p-3">
                                <i class="bi bi-newspaper text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1"><?= $stats['total_news'] ?></h5>
                            <p class="card-text text-muted mb-0">Noticias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-3 p-3">
                                <i class="bi bi-grid-3x3-gap text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1"><?= isset($stats['total_sections']) ? $stats['total_sections'] : '20' ?></h5>
                            <p class="card-text text-muted mb-0">Secciones</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Crear Usuario -->
                        <div class="col-lg-4 col-md-6">
                            <div class="d-grid">
                                <a href="<?= BASE_URL ?>admin/create-user" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Crear Usuario
                                </a>
                            </div>
                        </div>
                        
                        <!-- Crear Negocio -->
                        <div class="col-lg-4 col-md-6">
                            <div class="d-grid">
                                <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-success btn-lg">
                                    <i class="bi bi-shop me-2"></i>Crear Negocio
                                </a>
                            </div>
                        </div>
                        
                        <!-- Crear Noticia -->
                        <div class="col-lg-4 col-md-6">
                            <div class="d-grid">
                                <a href="<?= BASE_URL ?>admin/news-list" class="btn btn-warning btn-lg">
                                    <i class="bi bi-newspaper me-2"></i>Crear Noticia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestión Completa -->
    <div class="row">
        <!-- Gestión de Usuarios -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>Gestión de Usuarios
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= BASE_URL ?>admin/users" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-eye me-2 text-primary"></i>Ver Todos los Usuarios
                        </a>
                        <a href="<?= BASE_URL ?>admin/create-user" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-person-plus me-2 text-success"></i>Crear Nuevo Usuario
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="showUsersByRole()">
                            <i class="bi bi-funnel me-2 text-info"></i>Filtrar por Rol
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="exportUsers()">
                            <i class="bi bi-download me-2 text-warning"></i>Exportar Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Negocios -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-shop me-2"></i>Gestión de Negocios
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= BASE_URL ?>admin/business-list" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-list me-2 text-primary"></i>Ver Todos los Negocios
                        </a>
                        <button type="button" class="list-group-item list-group-item-action border-0" onclick="openCreateBusinessModal()">
                            <i class="bi bi-plus-circle me-2 text-success"></i>Crear Nuevo Negocio
                        </button>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="managePendingBusinesses()">
                            <i class="bi bi-clock me-2 text-warning"></i>Negocios Pendientes
                        </a>
                        <a href="<?= BASE_URL ?>section" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-grid-3x3-gap me-2 text-info"></i>Gestionar Secciones
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Noticias -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-newspaper me-2"></i>Gestión de Noticias
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= BASE_URL ?>news" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-eye me-2 text-primary"></i>Ver Todas las Noticias
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="openCreateNewsModal()">
                            <i class="bi bi-plus-circle me-2 text-success"></i>Crear Nueva Noticia
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="manageNewsImages()">
                            <i class="bi bi-image me-2 text-info"></i>Gestionar Imágenes
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0" onclick="manageNewsVideos()">
                            <i class="bi bi-play-circle me-2 text-danger"></i>Gestionar Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Herramientas Avanzadas -->
    <div class="row">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools me-2"></i>Herramientas de Administración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-outline-primary w-100" onclick="backupDatabase()">
                                <i class="bi bi-download me-2"></i>Respaldar BD
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-outline-info w-100" onclick="viewSystemLogs()">
                                <i class="bi bi-file-text me-2"></i>Ver Logs
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-outline-warning w-100" onclick="manageFiles()">
                                <i class="bi bi-folder me-2"></i>Archivos
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-outline-success w-100" onclick="generateReports()">
                                <i class="bi bi-graph-up me-2"></i>Reportes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para acciones avanzadas
function showUsersByRole() {
    alert('Funcionalidad: Filtrar usuarios por rol (Admin, Author, User)');
    // Aquí implementarías la lógica para filtrar usuarios
}

function exportUsers() {
    alert('Funcionalidad: Exportar lista de usuarios a CSV/Excel');
    // Aquí implementarías la exportación
}

function managePendingBusinesses() {
    alert('Funcionalidad: Gestionar negocios pendientes de aprobación');
    // Aquí implementarías la gestión de negocios pendientes
}

function manageNewsImages() {
    alert('Funcionalidad: Gestionar imágenes de noticias');
    // Aquí implementarías la gestión de imágenes
}

function manageNewsVideos() {
    alert('Funcionalidad: Gestionar videos de noticias');
    // Aquí implementarías la gestión de videos
}

function backupDatabase() {
    if(confirm('¿Estás seguro de que quieres crear un respaldo de la base de datos?')) {
        alert('Funcionalidad: Crear respaldo de base de datos');
        // Aquí implementarías el respaldo
    }
}

function viewSystemLogs() {
    alert('Funcionalidad: Ver logs del sistema');
    // Aquí implementarías la visualización de logs
}

function manageFiles() {
    alert('Funcionalidad: Gestionar archivos subidos');
    // Aquí implementarías la gestión de archivos
}

function generateReports() {
    alert('Funcionalidad: Generar reportes estadísticos');
    // Aquí implementarías la generación de reportes
}
</script>

<!-- Modal de Crear Negocio -->
<div class="modal fade" id="createBusinessModal" tabindex="-1" aria-labelledby="createBusinessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createBusinessModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Negocio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBusinessForm" action="index.php?controller=admin&action=businessCreate" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_name" class="form-label">
                                    <i class="bi bi-shop"></i> Nombre del Negocio *
                                </label>
                                <input type="text" class="form-control" id="create_business_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_section" class="form-label">
                                    <i class="bi bi-tags"></i> Sección *
                                </label>
                                <select class="form-control" id="create_business_section" name="section_id" required>
                                    <option value="">Seleccionar sección</option>
                                    <?php if (isset($sections) && !empty($sections)): ?>
                                        <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['title']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay secciones disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_description" class="form-label">
                            <i class="bi bi-file-text"></i> Descripción *
                        </label>
                        <textarea class="form-control" id="create_business_description" name="description" rows="3" required 
                                  placeholder="Describe el negocio y sus servicios"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_short_description" class="form-label">
                            <i class="bi bi-file-text"></i> Descripción Corta
                        </label>
                        <textarea class="form-control" id="create_business_short_description" name="short_description" rows="2" 
                                  placeholder="Resumen breve para las tarjetas"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_address" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Dirección
                                </label>
                                <input type="text" class="form-control" id="create_business_address" name="address" 
                                       placeholder="Dirección completa">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_phone" class="form-label">
                                    <i class="bi bi-telephone"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="create_business_phone" name="phone" 
                                       placeholder="Número de contacto">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control" id="create_business_email" name="email" 
                                       placeholder="correo@negocio.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_website" class="form-label">
                                    <i class="bi bi-globe"></i> Sitio Web
                                </label>
                                <input type="url" class="form-control" id="create_business_website" name="website" 
                                       placeholder="https://sitio-web.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_logo" class="form-label">
                            <i class="bi bi-image"></i> Logo del Negocio
                        </label>
                        <input type="file" class="form-control" id="create_business_logo" name="logo" accept="image/*">
                        <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Información:</strong> Los campos marcados con * son obligatorios. El negocio se creará como publicado por defecto.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Crear Negocio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para abrir el modal de crear negocio
function openCreateBusinessModal() {
    console.log('Abriendo modal de crear negocio desde admin');
    
    const modalElement = document.getElementById('createBusinessModal');
    if (!modalElement) {
        console.error('Modal de crear no encontrado');
        alert('Error: Modal no encontrado');
        return;
    }
    
    try {
        // Limpiar el formulario
        const form = document.getElementById('createBusinessForm');
        if (form) {
            form.reset();
        }
        
        // Abrir el modal
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        console.log('Modal abierto exitosamente');
        
    } catch (error) {
        console.error('Error al abrir modal:', error);
        alert('Error al abrir el modal: ' + error.message);
    }
}
</script>
                <div class="card-body">
                    <i class="bi bi-shield-check" style="font-size: 2rem; color: #dc3545;"></i>
                    <h3 class="mt-2">Admin</h3>
                    <p class="text-muted mb-0">Panel Activo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución de Usuarios por Rol -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart"></i> Usuarios por Rol
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($stats['users_by_role'] as $roleData): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?= getRoleBadgeColor($roleData['role']) ?>">
                                <?= ucfirst($roleData['role']) ?>
                            </span>
                            <strong><?= $roleData['count'] ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>admin/create-user" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Crear Usuario con Negocio
                        </a>
                        <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Gestionar Usuarios
                        </a>
                        <a href="<?= BASE_URL ?>business" class="btn btn-outline-success">
                            <i class="bi bi-shop"></i> Ver Negocios
                        </a>
                        <a href="<?= BASE_URL ?>news" class="btn btn-outline-warning">
                            <i class="bi bi-newspaper"></i> Ver Noticias
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces de Navegación -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list"></i> Gestión del Portal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="bi bi-people"></i> Usuarios</h6>
                            <ul class="list-unstyled">
                                <li><a href="<?= BASE_URL ?>admin/users" class="text-decoration-none">Ver todos los usuarios</a></li>
                                <li><a href="<?= BASE_URL ?>admin/create-user" class="text-decoration-none">Crear nuevo usuario</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-shop"></i> Negocios</h6>
                            <ul class="list-unstyled">
                                <li><a href="<?= BASE_URL ?>business" class="text-decoration-none">Ver todos los negocios</a></li>
                                <li><a href="<?= BASE_URL ?>dashboard/business/create" class="text-decoration-none">Crear nuevo negocio</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-newspaper"></i> Noticias</h6>
                            <ul class="list-unstyled">
                                <li><a href="index.php?controller=news&action=admin" class="text-decoration-none">Gestionar noticias</a></li>
                                <li><a href="index.php?controller=news&action=create" class="text-decoration-none">Crear nueva noticia</a></li>
                                <li><a href="index.php?controller=news&action=index" class="text-decoration-none">Ver sitio público</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';

function getRoleBadgeColor($role) {
    switch($role) {
        case 'admin': return 'danger';
        case 'author': return 'success';
        case 'user': return 'secondary';
        default: return 'light';
    }
}
?>