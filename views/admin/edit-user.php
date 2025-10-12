<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Editar Usuario - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-person-gear"></i> Editar Usuario
                    </h1>
                    <p class="text-muted">Modifica la información del usuario: <?= htmlspecialchars($user['username']) ?></p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-lines-fill"></i> Información del Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <!-- Token CSRF para seguridad -->
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="bi bi-person"></i> Nombre Completo *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="<?= htmlspecialchars($user['full_name']) ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class="bi bi-at"></i> Nombre de Usuario
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           value="<?= htmlspecialchars($user['username']) ?>" 
                                           disabled>
                                    <div class="form-text">El nombre de usuario no se puede modificar</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email *
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?= htmlspecialchars($user['email']) ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-key"></i> Nueva Contraseña
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Dejar vacío para mantener la actual">
                                    <div class="form-text">Mínimo 6 caracteres si se cambia</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        <i class="bi bi-shield"></i> Rol *
                                    </label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                        <option value="editor" <?= $user['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                                        <option value="redactor" <?= $user['role'] == 'redactor' ? 'selected' : '' ?>>Redactor</option>
                                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Usuario Básico</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="business_id" class="form-label">
                                        <i class="bi bi-shop"></i> Negocio Asignado
                                    </label>
                                    <select class="form-select" id="business_id" name="business_id">
                                        <option value="">Sin negocio asignado</option>
                                        <?php foreach ($businesses as $business): ?>
                                            <option value="<?= $business['id'] ?>" 
                                                    <?= $user['business_id'] == $business['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($business['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Solo necesario para editores</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    Usuario creado: <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                                </small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Actualizar Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
?>