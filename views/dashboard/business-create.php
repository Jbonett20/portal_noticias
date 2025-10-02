<?php
// Verificar permisos de editor o superior
require_once __DIR__ . '/../../seguridad.php';
verificarEditor();

$title = 'Crear Negocio - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-plus-circle"></i> Crear Negocio
                    </h1>
                    <p class="text-muted">Registra tu negocio en el portal</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
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
                        <i class="bi bi-shop"></i> Información del Negocio
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
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-shop"></i> Nombre del Negocio *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="section_id" class="form-label">
                                        <i class="bi bi-tag"></i> Sección *
                                    </label>
                                    <select class="form-select" id="section_id" name="section_id" required>
                                        <option value="">Seleccionar sección</option>
                                        <?php foreach ($sections as $section): ?>
                                            <option value="<?= $section['id'] ?>" 
                                                    <?= ($_POST['section_id'] ?? '') == $section['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($section['title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">
                                <i class="bi bi-text-paragraph"></i> Descripción Corta
                            </label>
                            <input type="text" class="form-control" id="short_description" name="short_description" 
                                   maxlength="255" value="<?= htmlspecialchars($_POST['short_description'] ?? '') ?>">
                            <div class="form-text">Máximo 255 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-file-text"></i> Descripción Completa
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> Teléfono
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">
                                        <i class="bi bi-globe"></i> Sitio Web
                                    </label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección
                            </label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Crear Negocio
                            </button>
                            <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline-secondary">
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