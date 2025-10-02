<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Gestionar Usuarios - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-people"></i> Gestionar Usuarios
                    </h1>
                    <p class="text-muted">Administra todos los usuarios del portal</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>admin/create-user" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Crear Usuario
                    </a>
                    <a href="<?= BASE_URL ?>admin" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list"></i> Lista de Usuarios
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Negocio</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?= getRoleBadgeColor($user['role']) ?>">
                                    <?= getRoleName($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['business_name']): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($user['business_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Sin negocio</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>admin/edit-user/<?= $user['id'] ?>" 
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user['role'] != 1 && $user['role'] !== 'admin'): ?>
                                    <button class="btn btn-outline-danger" 
                                            onclick="confirmDelete(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen por Roles -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-danger">Administradores</h5>
                    <h3><?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-success">Propietarios</h5>
                    <h3><?= count(array_filter($users, fn($u) => $u['role'] === 'author')) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-secondary">Usuarios Básicos</h5>
                    <h3><?= count(array_filter($users, fn($u) => $u['role'] === 'user')) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId, username) {
    if (confirm(`¿Estás seguro de que quieres eliminar al usuario "${username}"?\n\nEsta acción no se puede deshacer.`)) {
        window.location.href = `<?= BASE_URL ?>admin/delete-user/${userId}`;
    }
}
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';

function getRoleBadgeColor($role) {
    switch($role) {
        case 1:
        case 'admin': 
            return 'danger';
        case 2:
        case 'editor': 
            return 'success';
        default: 
            return 'secondary';
    }
}

function getRoleName($role) {
    switch($role) {
        case 1:
        case 'admin': 
            return 'Administrador';
        case 2:
        case 'editor': 
            return 'Editor';
        default: 
            return 'Usuario';
    }
}
?>