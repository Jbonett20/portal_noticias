<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Gestionar Negocios - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-shop"></i> Gestionar Negocios
                    </h1>
                    <p class="text-muted">Administra todos los negocios del portal</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>admin/business-create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear Negocio
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
                <i class="bi bi-list"></i> Lista de Negocios
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Sección</th>
                            <th>Propietario</th>
                            <th>Noticias</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($businesses)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-shop fs-3 d-block mb-2"></i>
                                No hay negocios registrados
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($businesses as $business): ?>
                        <tr>
                            <td><?= $business['id'] ?></td>
                            <td>
                                <div>
                                    <strong><?= htmlspecialchars($business['name']) ?></strong>
                                    <?php if (!empty($business['logo_path'])): ?>
                                        <br><small class="text-success"><i class="bi bi-image"></i> Con logo</small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= htmlspecialchars($business['section_name'] ?? 'Sin sección') ?></span>
                            </td>
                            <td>
                                <?php if ($business['owner_name']): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($business['owner_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Sin propietario</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $business['news_count'] ?? 0 ?> noticias</span>
                            </td>
                            <td>
                                <span class="badge bg-<?= ($business['is_published'] ?? 0) ? 'success' : 'warning' ?>">
                                    <?= ($business['is_published'] ?? 0) ? 'Publicado' : 'Borrador' ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($business['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>business/show/<?= $business['id'] ?>" 
                                       class="btn btn-outline-info" title="Ver" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/business-edit/<?= $business['id'] ?>" 
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" 
                                            onclick="confirmDelete(<?= $business['id'] ?>, '<?= htmlspecialchars($business['name']) ?>')"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen de Negocios -->
    <?php if (!empty($businesses)): ?>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-success">Publicados</h5>
                    <h3><?= count(array_filter($businesses, fn($b) => $b['is_published'] ?? 0)) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-warning">Borradores</h5>
                    <h3><?= count(array_filter($businesses, fn($b) => !($b['is_published'] ?? 0))) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-info">Con Propietario</h5>
                    <h3><?= count(array_filter($businesses, fn($b) => !empty($b['owner_name']))) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-primary">Total</h5>
                    <h3><?= count($businesses) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(businessId, name) {
    if (confirm(`¿Estás seguro de que quieres eliminar el negocio "${name}"?\n\nEsta acción no se puede deshacer y eliminará todas las noticias asociadas.`)) {
        window.location.href = `<?= BASE_URL ?>admin/business-delete/${businessId}`;
    }
}
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>