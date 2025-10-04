<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Editar Negocio - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-pencil-square"></i> Editar Negocio
                    </h1>
                    <p class="text-muted">Actualizar información del negocio</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>admin/business-list" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Negocios
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
                        <i class="bi bi-building"></i> Información del Negocio
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

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-shop"></i> Nombre del Negocio *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           required value="<?= htmlspecialchars($business['name'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">
                                        <i class="bi bi-grid"></i> Sección *
                                    </label>
                                    <select class="form-select" id="section_id" name="section_id" required>
                                        <option value="">Seleccionar sección</option>
                                        <?php if (isset($sections)): ?>
                                            <?php foreach ($sections as $section): ?>
                                                <option value="<?= $section['id'] ?>" 
                                                        <?= ($business['section_id'] ?? '') == $section['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($section['title']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-text-paragraph"></i> Descripción *
                            </label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="4" required><?= htmlspecialchars($business['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">
                                <i class="bi bi-card-text"></i> Descripción Corta
                            </label>
                            <input type="text" class="form-control" id="short_description" name="short_description" 
                                   maxlength="255" value="<?= htmlspecialchars($business['short_description'] ?? '') ?>">
                            <div class="form-text">Resumen breve que aparecerá en las listas</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($business['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($business['email'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">
                                        <i class="bi bi-globe"></i> Sitio Web
                                    </label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           placeholder="https://" value="<?= htmlspecialchars($business['website'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="created_by" class="form-label">
                                        <i class="bi bi-person"></i> Propietario
                                    </label>
                                    <select class="form-select" id="created_by" name="created_by">
                                        <option value="">Sin propietario asignado</option>
                                        <?php if (isset($users)): ?>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?= $user['id'] ?>" 
                                                        <?= ($business['created_by'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($user['full_name'] . ' (' . $user['username'] . ')') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección
                            </label>
                            <textarea class="form-control" id="address" name="address" 
                                      rows="2"><?= htmlspecialchars($business['address'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image"></i> Logo del Negocio
                            </label>
                            <?php if (!empty($business['logo_path'])): ?>
                                <div class="mb-2">
                                    <img src="<?= BASE_URL . $business['logo_path'] ?>" 
                                         alt="Logo actual" class="img-thumbnail" style="max-width: 150px;">
                                    <p class="text-muted small">Logo actual</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="logo" name="logo" 
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-text">Dejar vacío para mantener el logo actual. Formatos: JPG, PNG, GIF, WEBP. Máx: 2MB</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Actualizar Negocio
                            </button>
                            <a href="<?= BASE_URL ?>admin/business-list" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>