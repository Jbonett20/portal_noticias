<?php
// Verificar permisos de editor o superior
require_once __DIR__ . '/../../seguridad.php';
verificarEditor();

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
                    <p class="text-muted">Bienvenido, <?= htmlspecialchars($user['full_name']) ?> 
                        <span class="badge bg-<?= getRoleBadgeColor($user['role']) ?>"><?= ucfirst($user['role']) ?></span>
                    </p>
                </div>
                <div>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>admin" class="btn btn-outline-primary me-2">
                            <i class="bi bi-gear"></i> Administración
                        </a>
                        <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Negocio
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-shop" style="font-size: 2rem; color: #667eea;"></i>
                    <h3 class="mt-2"><?= $stats['total_businesses'] ?? $stats['my_businesses'] ?></h3>
                    <p class="text-muted mb-0">
                        <?= $user['role'] === 'admin' ? 'Total Negocios' : 'Mis Negocios' ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle" style="font-size: 2rem; color: #28a745;"></i>
                    <h3 class="mt-2"><?= $stats['open_businesses'] ?></h3>
                    <p class="text-muted mb-0">Abiertos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-x-circle" style="font-size: 2rem; color: #dc3545;"></i>
                    <h3 class="mt-2"><?= $stats['closed_businesses'] ?></h3>
                    <p class="text-muted mb-0">Cerrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-lightning" style="font-size: 2rem; color: #ffc107;"></i>
                    <h3 class="mt-2"><?= $stats['active_businesses'] ?></h3>
                    <p class="text-muted mb-0">Activos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Negocios -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-shop"></i> 
                <?= $user['role'] === 'admin' ? 'Todos los Negocios' : 'Mis Negocios' ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($businesses)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-shop" style="font-size: 3rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">No hay negocios</h4>
                    <p class="text-muted">
                        <?= $user['role'] === 'admin' ? 'No hay negocios registrados en el sistema.' : 'Aún no tienes negocios registrados.' ?>
                    </p>
                    <a href="<?= BASE_URL ?>dashboard/business/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear Primer Negocio
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($businesses as $business): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 business-card" data-business-id="<?= $business['id'] ?>">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <!-- Indicador de estado -->
                                    <div class="status-indicator me-2" title="<?= $business['is_open'] == 1 ? 'Abierto' : 'Cerrado' . ($business['closed_reason'] ? ': ' . $business['closed_reason'] : '') ?>">
                                        <span class="status-dot <?= $business['is_open'] == 1 ? 'status-open' : 'status-closed' ?>"></span>
                                    </div>
                                    <h6 class="mb-0"><?= htmlspecialchars($business['name']) ?></h6>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>dashboard/business/edit/<?= $business['id'] ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="toggleBusinessStatus(<?= $business['id'] ?>, <?= $business['is_open'] == 1 ? 'false' : 'true' ?>)">
                                            <i class="bi bi-<?= $business['is_open'] == 1 ? 'pause' : 'play' ?>-circle"></i> 
                                            <?= $business['is_open'] == 1 ? 'Cerrar' : 'Abrir' ?>
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="showImageUpload(<?= $business['id'] ?>)">
                                            <i class="bi bi-image"></i> Subir Imagen
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <?php if ($business['logo_path']): ?>
                                        <img src="<?= UPLOAD_URL . $business['logo_path'] ?>" 
                                             class="business-logo me-3" alt="Logo">
                                    <?php else: ?>
                                        <div class="business-logo-placeholder me-3">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-tag"></i> <?= htmlspecialchars($business['section_title'] ?? 'Sin sección') ?>
                                        </p>
                                        <?php if ($business['short_description']): ?>
                                            <p class="small text-muted mb-2"><?= htmlspecialchars($business['short_description']) ?></p>
                                        <?php endif; ?>
                                        <div class="status-info">
                                            <?php if ($business['is_open'] == 1): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Abierto
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Cerrado
                                                </span>
                                                <?php if ($business['closed_reason']): ?>
                                                    <small class="text-muted d-block mt-1">
                                                        <?= htmlspecialchars($business['closed_reason']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Estado: <span class="badge bg-<?= getStatusBadgeColor($business['status']) ?>">
                                            <?= ucfirst($business['status']) ?>
                                        </span>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>business/<?= $business['slug'] ?>" 
                                           class="btn btn-outline-primary" target="_blank" title="Ver público">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>dashboard/business/edit/<?= $business['id'] ?>" 
                                           class="btn btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="statusForm">
                    <div class="mb-3">
                        <label class="form-label">Estado:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_open" value="1" id="statusOpen">
                            <label class="form-check-label" for="statusOpen">
                                <i class="bi bi-check-circle text-success"></i> Abierto
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_open" value="0" id="statusClosed">
                            <label class="form-check-label" for="statusClosed">
                                <i class="bi bi-x-circle text-danger"></i> Cerrado
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="closedReasonGroup" style="display: none;">
                        <label for="closedReason" class="form-label">Razón del cierre:</label>
                        <input type="text" class="form-control" id="closedReason" placeholder="Ej: Renovaciones, Vacaciones, etc.">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveBusinessStatus()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir imagen -->
<div class="modal fade" id="imageUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="imageUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="imageFile" class="form-label">Seleccionar imagen:</label>
                        <input type="file" class="form-control" id="imageFile" name="image" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="imageCaption" class="form-label">Descripción (opcional):</label>
                        <input type="text" class="form-control" id="imageCaption" name="caption" placeholder="Descripción de la imagen">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="uploadImage()">Subir</button>
            </div>
        </div>
    </div>
</div>

<style>
.business-card {
    transition: transform 0.2s;
}

.business-card:hover {
    transform: translateY(-2px);
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.status-open {
    background-color: #28a745;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
}

.status-closed {
    background-color: #6c757d;
}

.business-logo-placeholder {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #6c757d;
}

.status-indicator {
    position: relative;
}

.status-indicator:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    white-space: nowrap;
    font-size: 12px;
    z-index: 1000;
}
</style>

<script>
let currentBusinessId = null;

function toggleBusinessStatus(businessId, isOpen) {
    currentBusinessId = businessId;
    document.getElementById('statusOpen').checked = isOpen;
    document.getElementById('statusClosed').checked = !isOpen;
    
    toggleClosedReasonField();
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

function toggleClosedReasonField() {
    const isClosed = document.getElementById('statusClosed').checked;
    const reasonGroup = document.getElementById('closedReasonGroup');
    reasonGroup.style.display = isClosed ? 'block' : 'none';
    
    if (!isClosed) {
        document.getElementById('closedReason').value = '';
    }
}

function saveBusinessStatus() {
    const isOpen = document.querySelector('input[name="is_open"]:checked').value;
    const closedReason = document.getElementById('closedReason').value;
    
    fetch(`<?= BASE_URL ?>dashboard/toggle-business-status/${currentBusinessId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            is_open: isOpen == '1',
            closed_reason: isOpen == '0' ? closedReason : null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error de conexión: ' + error);
    });
    
    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
}

function showImageUpload(businessId) {
    currentBusinessId = businessId;
    new bootstrap.Modal(document.getElementById('imageUploadModal')).show();
}

function uploadImage() {
    const formData = new FormData(document.getElementById('imageUploadForm'));
    
    fetch(`<?= BASE_URL ?>dashboard/upload-image/${currentBusinessId}`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Imagen subida exitosamente');
            bootstrap.Modal.getInstance(document.getElementById('imageUploadModal')).hide();
            document.getElementById('imageUploadForm').reset();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error de conexión: ' + error);
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('statusClosed').addEventListener('change', toggleClosedReasonField);
    document.getElementById('statusOpen').addEventListener('change', toggleClosedReasonField);
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';

function getRoleBadgeColor($role) {
    switch($role) {
        case 'admin': return 'danger';
        case 'author': return 'success';
        case 'editor': return 'warning';
        case 'user': return 'secondary';
        default: return 'light';
    }
}

function getStatusBadgeColor($status) {
    switch($status) {
        case 'active': return 'success';
        case 'inactive': return 'warning';
        case 'suspended': return 'danger';
        default: return 'secondary';
    }
}
?>