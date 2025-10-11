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
                        <button type="button" class="btn btn-primary" onclick="openCreateBusinessModal()">
                            <i class="bi bi-plus-circle"></i> Crear Negocio
                        </button>
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
                                        <button class="btn btn-outline-secondary" title="Editar" onclick="openEditBusinessModal(<?= $business['id'] ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
<!-- Modal Edición Negocio (solo uno en el DOM) -->
<div class="modal fade" id="editBusinessModal" tabindex="-1" aria-labelledby="editBusinessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editBusinessModalLabel">
                    <i class="bi bi-pencil-square me-2"></i> Editar Negocio
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBusinessForm" enctype="multipart/form-data" onsubmit="submitEditBusinessForm(event)">
                <div class="modal-body">
                    <div id="editBusinessMsg"></div>
                    <input type="hidden" id="edit_business_id" name="business_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_section_id" class="form-label">Sección</label>
                                <select class="form-select" id="edit_business_section_id" name="section_id" required>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>" <?= (isset($business) && $business['section_id'] == $section['id']) ? 'selected' : '' ?>><?= htmlspecialchars($section['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="edit_business_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_short_description" class="form-label">Descripción corta</label>
                                <input type="text" class="form-control" id="edit_business_short_description" name="short_description">
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_description" class="form-label">Descripción completa</label>
                                <textarea class="form-control" id="edit_business_description" name="description" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_advertisement_text" class="form-label">Texto publicitario</label>
                                <textarea class="form-control" id="edit_business_advertisement_text" name="advertisement_text" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="edit_business_address" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_business_phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="edit_business_website" class="form-label">Sitio web</label>
                                <input type="url" class="form-control" id="edit_business_website" name="website">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado del negocio</label>
                                <select class="form-select" id="edit_business_is_open" name="is_open" onchange="toggleClosedReason()">
                                    <option value="1">Abierto</option>
                                    <option value="0">Cerrado</option>
                                </select>
                            </div>
                            <div class="mb-3" id="closedReasonContainer" style="display:none;">
                                <label for="edit_business_closed_reason" class="form-label">Motivo de cierre</label>
                                <input type="text" class="form-control" id="edit_business_closed_reason" name="closed_reason">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen actual</label>
                                <div id="edit_business_image_preview"></div>
                                <input type="file" class="form-control mt-2" id="edit_business_image" name="image" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video actual</label>
                                <div id="edit_business_video_preview"></div>
                                <input type="file" class="form-control mt-2" id="edit_business_video" name="video" accept="video/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
    transition: none;
}

.business-card:hover {
    transform: none;
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
// Abrir modal edición negocio y cargar datos vía AJAX
function openEditBusinessModal(businessId) {
    fetch('index.php?controller=dashboard&action=getBusiness&id=' + businessId)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('No se pudo cargar el negocio');
                return;
            }
            const b = data.business;
            document.getElementById('edit_business_id').value = b.id;
            document.getElementById('edit_business_name').value = b.name;
            // Seleccionar la sección actual en el select
            const sectionSelect = document.getElementById('edit_business_section_id');
            if (sectionSelect) {
                for (let i = 0; i < sectionSelect.options.length; i++) {
                    sectionSelect.options[i].selected = sectionSelect.options[i].value == b.section_id;
                }
            }
            document.getElementById('edit_business_short_description').value = b.short_description || '';
            document.getElementById('edit_business_description').value = b.description || '';
            document.getElementById('edit_business_advertisement_text').value = b.advertisement_text || '';
            document.getElementById('edit_business_address').value = b.address || '';
            document.getElementById('edit_business_phone').value = b.phone || '';
            document.getElementById('edit_business_website').value = b.website || '';
            document.getElementById('edit_business_is_open').value = b.is_open;
            document.getElementById('edit_business_closed_reason').value = b.closed_reason || '';
            toggleClosedReason();
            // Imagen actual
            document.getElementById('edit_business_image_preview').innerHTML = b.image_path ? `<img src='${b.image_path}' alt='Logo' style='max-width:120px;max-height:90px;' class='img-thumbnail'>` : '<small class="text-muted">Sin imagen</small>';
            // Video actual
            document.getElementById('edit_business_video_preview').innerHTML = b.video_url ? `<video src='${b.video_url}' controls style='max-width:180px;max-height:120px;'></video>` : '<small class="text-muted">Sin video</small>';
            const modal = new bootstrap.Modal(document.getElementById('editBusinessModal'));
            modal.show();
        });
}

function toggleClosedReason() {
    const isOpen = document.getElementById('edit_business_is_open').value;
    document.getElementById('closedReasonContainer').style.display = (isOpen == '0') ? '' : 'none';
}

function submitEditBusinessForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('index.php?controller=dashboard&action=updateBusiness', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msgDiv = document.getElementById('editBusinessMsg');
        if (data.success) {
            msgDiv.innerHTML = `<div class='alert alert-success'><i class='bi bi-check-circle'></i> ${data.message}</div>`;
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editBusinessModal'));
                modal.hide();
                location.reload();
            }, 1200);
        } else {
            msgDiv.innerHTML = `<div class='alert alert-danger'><i class='bi bi-exclamation-circle'></i> ${data.message}</div>`;
        }
    })
    .catch(error => {
        const msgDiv = document.getElementById('editBusinessMsg');
        msgDiv.innerHTML = `<div class='alert alert-danger'><i class='bi bi-exclamation-circle'></i> Error de conexión</div>`;
    });
}
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

<!-- Modal de Crear Negocio -->
<div class="modal fade" id="createBusinessModal" tabindex="-1" aria-labelledby="createBusinessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createBusinessModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Negocio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBusinessForm" action="index.php?controller=admin&action=businessCreate" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_name" class="form-label">
                                    <i class="bi bi-shop"></i> Nombre del Negocio *
                                </label>
                                <input type="text" class="form-control" id="create_business_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_section" class="form-label">
                                    <i class="bi bi-tags"></i> Sección *
                                </label>
                                <select class="form-control" id="create_business_section" name="section_id" required>
                                    <option value="">Seleccionar sección</option>
                                    <?php if (isset($sections) && !empty($sections)): ?>
                                        <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['title']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay secciones disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_description" class="form-label">
                            <i class="bi bi-file-text"></i> Descripción *
                        </label>
                        <textarea class="form-control" id="create_business_description" name="description" rows="3" required 
                                  placeholder="Describe el negocio y sus servicios"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_short_description" class="form-label">
                            <i class="bi bi-file-text"></i> Descripción Corta
                        </label>
                        <textarea class="form-control" id="create_business_short_description" name="short_description" rows="2" 
                                  placeholder="Resumen breve para las tarjetas"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_address" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Dirección
                                </label>
                                <input type="text" class="form-control" id="create_business_address" name="address" 
                                       placeholder="Dirección completa">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_phone" class="form-label">
                                    <i class="bi bi-telephone"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="create_business_phone" name="phone" 
                                       placeholder="Número de contacto">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control" id="create_business_email" name="email" 
                                       placeholder="correo@negocio.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_business_website" class="form-label">
                                    <i class="bi bi-globe"></i> Sitio Web
                                </label>
                                <input type="url" class="form-control" id="create_business_website" name="website" 
                                       placeholder="https://sitio-web.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_business_logo" class="form-label">
                            <i class="bi bi-image"></i> Logo del Negocio
                        </label>
                        <input type="file" class="form-control" id="create_business_logo" name="logo" accept="image/*">
                        <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Información:</strong> Los campos marcados con * son obligatorios. El negocio se creará como publicado por defecto.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Crear Negocio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para abrir el modal de crear negocio
function openCreateBusinessModal() {
    console.log('Abriendo modal de crear negocio desde dashboard');
    
    const modalElement = document.getElementById('createBusinessModal');
    if (!modalElement) {
        console.error('Modal de crear no encontrado');
        alert('Error: Modal no encontrado');
        return;
    }
    
    try {
        // Limpiar el formulario
        const form = document.getElementById('createBusinessForm');
        if (form) {
            form.reset();
        }
        
        // Abrir el modal
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        console.log('Modal abierto exitosamente');
        
    } catch (error) {
        console.error('Error al abrir modal:', error);
        alert('Error al abrir el modal: ' + error.message);
    }
}
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