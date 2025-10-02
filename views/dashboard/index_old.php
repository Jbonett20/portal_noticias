<?php
$title = 'Panel de Control - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-speedometer2"></i> Panel de Control
                    </h1>
                    <p class="text-muted">Bienvenido, <?= htmlspecialchars($user['full_name']) ?></p>
                </div>
                <div>
                    <span class="badge bg-primary"><?= ucfirst($user['role']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Mis Negocios</h5>
                            <h2><?= count($businesses) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-shop" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Publicados</h5>
                            <h2><?= count(array_filter($businesses, function($b) { return $b['is_published']; })) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Borradores</h5>
                            <h2><?= count(array_filter($businesses, function($b) { return !$b['is_published']; })) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-file-earmark" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle"></i> Crear Nuevo Negocio
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>business" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Ver Portal Público
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de negocios -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-shop"></i> Mis Negocios
                    </h5>
                    <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Nuevo
                    </a>
                </div>
                
                <div class="card-body">
                    <?php if (empty($businesses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">No tienes negocios registrados</h4>
                            <p class="text-muted">Comienza creando tu primer negocio</p>
                            <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear Primer Negocio
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Negocio</th>
                                        <th>Sección</th>
                                        <th>Estado</th>
                                        <th>Creado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($businesses as $business): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($business['logo_path']): ?>
                                                <img src="<?= UPLOAD_URL . $business['logo_path'] ?>" 
                                                     class="rounded me-2" width="40" height="40" 
                                                     style="object-fit: cover;">
                                                <?php else: ?>
                                                <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-shop text-white"></i>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <div>
                                                    <strong><?= htmlspecialchars($business['name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= truncateText($business['short_description'], 50) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($business['section_title']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($business['is_published']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Publicado
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock"></i> Borrador
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= formatDate($business['created_at'], 'd/m/Y') ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>business/<?= $business['slug'] ?>" 
                                                   class="btn btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>dashboard/business/edit/<?= $business['id'] ?>" 
                                                   class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>dashboard/business/images/<?= $business['id'] ?>" 
                                                   class="btn btn-outline-info">
                                                    <i class="bi bi-images"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>