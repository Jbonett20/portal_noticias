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
                                    <button class="btn btn-outline-primary" 
                                            onclick="openEditModal(<?= $user['id'] ?>)" 
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
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

<!-- Modal de Edición de Usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="index.php?controller=admin&action=updateUser" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Rol</label>
                                <select class="form-control" id="edit_role" name="role" required>
                                    <option value="user">Usuario</option>
                                    <option value="editor">Editor</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Estado</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="edit_password" name="password" 
                               placeholder="Dejar en blanco para mantener la actual">
                        <small class="text-muted">Dejar vacío para no cambiar la contraseña</small>
                    </div>
                    
                    <input type="hidden" id="edit_user_id" name="user_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Datos de usuarios para el modal
const usersData = <?= json_encode($users) ?>;

function openEditModal(userId) {
    const user = usersData.find(u => u.id == userId);
    if (!user) {
        alert('Usuario no encontrado');
        return;
    }
    
    // Llenar el formulario con los datos del usuario
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_email').value = user.email;
    
    // Convertir rol numérico a string
    let roleValue = 'user'; // valor por defecto
    if (user.role == 1 || user.role === 'admin') {
        roleValue = 'admin';
    } else if (user.role == 2 || user.role === 'editor') {
        roleValue = 'editor';
    }
    document.getElementById('edit_role').value = roleValue;
    
    document.getElementById('edit_status').value = user.is_active == 1 ? 'active' : 'inactive';
    document.getElementById('edit_password').value = '';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

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