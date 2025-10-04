<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Crear Negocio - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-shop-window"></i> Crear Negocio
                    </h1>
                    <p class="text-muted">Registra un nuevo negocio en el portal</p>
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
                                           required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
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
                                                        <?= ($_POST['section_id'] ?? '') == $section['id'] ? 'selected' : '' ?>>
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
                                      rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">
                                <i class="bi bi-card-text"></i> Descripción Corta
                            </label>
                            <input type="text" class="form-control" id="short_description" name="short_description" 
                                   maxlength="255" value="<?= htmlspecialchars($_POST['short_description'] ?? '') ?>">
                            <div class="form-text">Resumen breve que aparecerá en las listas</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
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
                                           placeholder="https://" value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">
                                        <i class="bi bi-person"></i> Propietario
                                    </label>
                                    <select class="form-select" id="user_id" name="user_id">
                                        <option value="">Sin propietario asignado</option>
                                        <?php if (isset($users)): ?>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?= $user['id'] ?>" 
                                                        <?= ($_POST['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
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
                                      rows="2"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image"></i> Logo del Negocio
                            </label>
                            <input type="file" class="form-control" id="logo" name="logo" 
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-text">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Información:</strong> Una vez creado el negocio, aparecerá en el directorio público y podrá ser gestionado por el propietario asignado.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear Negocio
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