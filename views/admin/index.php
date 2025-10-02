<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Panel de Administración - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="bi bi-gear"></i> Panel de Administración
            </h1>
            <p class="text-muted">Gestiona usuarios, negocios y contenido del portal</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-people" style="font-size: 2rem; color: #667eea;"></i>
                    <h3 class="mt-2"><?= $stats['total_users'] ?></h3>
                    <p class="text-muted mb-0">Usuarios Totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-shop" style="font-size: 2rem; color: #28a745;"></i>
                    <h3 class="mt-2"><?= $stats['total_businesses'] ?></h3>
                    <p class="text-muted mb-0">Negocios</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-newspaper" style="font-size: 2rem; color: #ffc107;"></i>
                    <h3 class="mt-2"><?= $stats['total_news'] ?></h3>
                    <p class="text-muted mb-0">Noticias</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
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
                            <h6><i class="bi bi-newspaper"></i> Contenido</h6>
                            <ul class="list-unstyled">
                                <li><a href="<?= BASE_URL ?>news" class="text-decoration-none">Ver todas las noticias</a></li>
                                <li><a href="<?= BASE_URL ?>section" class="text-decoration-none">Gestionar secciones</a></li>
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