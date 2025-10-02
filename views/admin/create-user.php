<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Crear Usuario - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-person-plus"></i> Crear Usuario
                    </h1>
                    <p class="text-muted">Crea un nuevo usuario y asigna su negocio</p>
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
                        <i class="bi bi-person-gear"></i> Información del Usuario
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
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class="bi bi-at"></i> Usuario *
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                    <div class="form-text">Único en el sistema</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock"></i> Contraseña *
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="form-text">Mínimo 6 caracteres</div>
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
                                        <option value="user" <?= ($_POST['role'] ?? '') === 'user' ? 'selected' : '' ?>>
                                            Usuario Básico (Solo lectura)
                                        </option>
                                        <option value="author" <?= ($_POST['role'] ?? '') === 'author' ? 'selected' : '' ?>>
                                            Propietario de Negocio
                                        </option>
                                        <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                            Administrador
                                        </option>
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
                                                    <?= ($_POST['business_id'] ?? '') == $business['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($business['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Solo para propietarios de negocio</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Información sobre roles:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Usuario Básico:</strong> Solo puede ver contenido público</li>
                                <li><strong>Propietario:</strong> Puede gestionar su negocio y publicar noticias</li>
                                <li><strong>Administrador:</strong> Control total del sistema</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Crear Usuario
                            </button>
                            <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campo de negocio según el rol seleccionado
document.getElementById('role').addEventListener('change', function() {
    const businessField = document.getElementById('business_id').closest('.mb-3');
    if (this.value === 'author') {
        businessField.style.display = 'block';
    } else {
        businessField.style.display = 'none';
        document.getElementById('business_id').value = '';
    }
});

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('role').dispatchEvent(new Event('change'));
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>