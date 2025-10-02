<?php
// Verificar permisos para el negocio específico
require_once __DIR__ . '/../../seguridad.php';
// La verificación específica del negocio se hace en el controlador
verificarEditor();

$title = 'Editar Negocio - ' . SITE_NAME;
ob_start();
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-pencil"></i> Editar Negocio
                    </h1>
                    <p class="text-muted">Actualiza la información de <?= htmlspecialchars($business['name']) ?></p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>business/<?= $business['slug'] ?>" class="btn btn-outline-info me-2" target="_blank">
                        <i class="bi bi-eye"></i> Ver Público
                    </a>
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
                                           required value="<?= htmlspecialchars($_POST['name'] ?? $business['name']) ?>">
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
                                                    <?= ($_POST['section_id'] ?? $business['section_id']) == $section['id'] ? 'selected' : '' ?>>
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
                                   maxlength="255" value="<?= htmlspecialchars($_POST['short_description'] ?? $business['short_description']) ?>">
                            <div class="form-text">Máximo 255 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-file-text"></i> Descripción Completa
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? $business['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="advertisement_text" class="form-label">
                                <i class="bi bi-megaphone"></i> Texto Publicitario / Promociones
                            </label>
                            <textarea class="form-control" id="advertisement_text" name="advertisement_text" rows="3" 
                                      placeholder="Ejemplo: ¡Oferta especial! 20% de descuento todos los martes. Delivery gratuito en pedidos mayores a $50."><?= htmlspecialchars($_POST['advertisement_text'] ?? $business['advertisement_text'] ?? '') ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Este texto aparecerá destacado en el perfil público de tu negocio para promocionar ofertas especiales, descuentos o servicios.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> Teléfono
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($_POST['phone'] ?? $business['phone']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">
                                        <i class="bi bi-globe"></i> Sitio Web
                                    </label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           value="<?= htmlspecialchars($_POST['website'] ?? $business['website']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección
                            </label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="<?= htmlspecialchars($_POST['address'] ?? $business['address']) ?>">
                        </div>

                        <!-- Estado del Negocio -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-clock"></i> Estado del Negocio
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_open" 
                                                   <?= $business['is_open'] ? 'checked' : '' ?> disabled>
                                            <label class="form-check-label" for="is_open">
                                                Negocio abierto
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            Estado actual: 
                                            <?php if ($business['is_open']): ?>
                                                <span class="badge bg-success">Abierto</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Cerrado</span>
                                                <?php if ($business['closed_reason']): ?>
                                                    - <?= htmlspecialchars($business['closed_reason']) ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm" 
                                                id="changeStatusBtn"
                                                data-business-id="<?= $business['id'] ?>"
                                                data-is-open="<?= $business['is_open'] ?>"
                                                data-closed-reason="<?= isset($business['closed_reason']) ? htmlspecialchars($business['closed_reason']) : '' ?>">
                                            <i class="bi bi-gear"></i>
                                            Cambiar Estado
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                            <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Gestión de Contenido Multimedia -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-images"></i> Contenido Multimedia
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Tabs para organizar contenido -->
                    <ul class="nav nav-tabs" id="contentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="images-tab" data-bs-toggle="tab" 
                                    data-bs-target="#images-panel" type="button" role="tab">
                                <i class="bi bi-image"></i> Imágenes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="videos-tab" data-bs-toggle="tab" 
                                    data-bs-target="#videos-panel" type="button" role="tab">
                                <i class="bi bi-play-circle"></i> Videos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" 
                                    data-bs-target="#gallery-panel" type="button" role="tab">
                                <i class="bi bi-grid"></i> Galería
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="contentTabsContent">
                        <!-- Panel de Imágenes -->
                        <div class="tab-pane fade show active" id="images-panel" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Subir Nueva Imagen</h6>
                                        </div>
                                        <div class="card-body">
                                            <form id="uploadImageForm" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="imageFile" class="form-label">Seleccionar imagen:</label>
                                                    <input type="file" class="form-control" id="imageFile" 
                                                           name="image" accept="image/*" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="imageCaption" class="form-label">Descripción:</label>
                                                    <input type="text" class="form-control" id="imageCaption" 
                                                           name="caption" placeholder="Descripción de la imagen">
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="featuredImage" name="is_featured">
                                                        <label class="form-check-label" for="featuredImage">
                                                            Imagen destacada
                                                        </label>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="uploadBusinessImage()">
                                                    <i class="bi bi-upload"></i> Subir Imagen
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Imágenes Actuales</h6>
                                        </div>
                                        <div class="card-body" id="current-images">
                                            <!-- Se cargarán vía AJAX -->
                                            <div class="text-center text-muted">
                                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Cargando imágenes...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Panel de Videos -->
                        <div class="tab-pane fade" id="videos-panel" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Agregar Video</h6>
                                        </div>
                                        <div class="card-body">
                                            <form id="addVideoForm">
                                                <div class="mb-3">
                                                    <label for="videoType" class="form-label">Tipo de video:</label>
                                                    <select class="form-select" id="videoType" name="video_type">
                                                        <option value="youtube">YouTube</option>
                                                        <option value="vimeo">Vimeo</option>
                                                        <option value="upload">Subir archivo</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3" id="videoUrlGroup">
                                                    <label for="videoUrl" class="form-label">URL del video:</label>
                                                    <input type="url" class="form-control" id="videoUrl" 
                                                           name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                                                </div>
                                                <div class="mb-3" id="videoFileGroup" style="display: none;">
                                                    <label for="videoFile" class="form-label">Archivo de video:</label>
                                                    <input type="file" class="form-control" id="videoFile" 
                                                           name="video_file" accept="video/*">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="videoTitle" class="form-label">Título del video:</label>
                                                    <input type="text" class="form-control" id="videoTitle" 
                                                           name="video_title" placeholder="Título descriptivo">
                                                </div>
                                                <button type="button" class="btn btn-success" onclick="addBusinessVideo()">
                                                    <i class="bi bi-plus-circle"></i> Agregar Video
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Videos Actuales</h6>
                                        </div>
                                        <div class="card-body" id="current-videos">
                                            <!-- Se cargarán vía AJAX -->
                                            <div class="text-center text-muted">
                                                <i class="bi bi-play-circle" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Cargando videos...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Panel de Galería -->
                        <div class="tab-pane fade" id="gallery-panel" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Vista previa de la galería</strong><br>
                                Aquí se muestra cómo se verá el contenido en la página pública del negocio.
                            </div>
                            <div id="gallery-preview">
                                <!-- Se generará dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado (reutilizado del dashboard) -->
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
                            <input class="form-check-input" type="radio" name="is_open" value="1" id="statusOpen" 
                                   <?= $business['is_open'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusOpen">
                                <i class="bi bi-check-circle text-success"></i> Abierto
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_open" value="0" id="statusClosed"
                                   <?= $business['is_open'] == 0 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusClosed">
                                <i class="bi bi-x-circle text-danger"></i> Cerrado
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="closedReasonGroup" style="display: <?= $business['is_open'] == 0 ? 'block' : 'none' ?>;">
                        <label for="closedReason" class="form-label">Razón del cierre:</label>
                        <input type="text" class="form-control" id="closedReason" 
                               placeholder="Ej: Renovaciones, Vacaciones, etc."
                               value="<?= isset($business['closed_reason']) ? htmlspecialchars($business['closed_reason']) : '' ?>">
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

<script>
let currentBusinessId = <?= $business['id'] ?>;

// Funciones para gestión de contenido multimedia
function uploadBusinessImage() {
    const formData = new FormData(document.getElementById('uploadImageForm'));
    
    fetch(`<?= BASE_URL ?>dashboard/upload-image/${currentBusinessId}`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Imagen subida exitosamente', 'success');
            document.getElementById('uploadImageForm').reset();
            loadCurrentImages();
        } else {
            showAlert('Error: ' + data.error, 'danger');
        }
    })
    .catch(error => {
        showAlert('Error de conexión: ' + error, 'danger');
    });
}

function addBusinessVideo() {
    const formData = new FormData(document.getElementById('addVideoForm'));
    
    fetch(`<?= BASE_URL ?>dashboard/add-video/${currentBusinessId}`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Video agregado exitosamente', 'success');
            document.getElementById('addVideoForm').reset();
            loadCurrentVideos();
        } else {
            showAlert('Error: ' + data.error, 'danger');
        }
    })
    .catch(error => {
        showAlert('Error de conexión: ' + error, 'danger');
    });
}

function loadCurrentImages() {
    fetch(`<?= BASE_URL ?>dashboard/get-images/${currentBusinessId}`, {
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('current-images');
        if (data.images && data.images.length > 0) {
            container.innerHTML = data.images.map(image => `
                <div class="mb-3 p-2 border rounded" data-image-id="${image.id}">
                    <img src="${image.url}" class="img-fluid mb-2" style="max-height: 100px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${image.caption || 'Sin descripción'}</small>
                        <div>
                            ${image.is_featured ? '<span class="badge bg-warning">Destacada</span>' : ''}
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteImage(${image.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-muted text-center">No hay imágenes todavía</p>';
        }
    });
}

function loadCurrentVideos() {
    fetch(`<?= BASE_URL ?>dashboard/get-videos/${currentBusinessId}`, {
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('current-videos');
        if (data.videos && data.videos.length > 0) {
            container.innerHTML = data.videos.map(video => `
                <div class="mb-3 p-2 border rounded" data-video-id="${video.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${video.title}</strong><br>
                            <small class="text-muted">${video.type}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteVideo(${video.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-muted text-center">No hay videos todavía</p>';
        }
    });
}

function deleteImage(imageId) {
    if (confirm('¿Estás seguro de eliminar esta imagen?')) {
        fetch(`<?= BASE_URL ?>dashboard/delete-image/${imageId}`, {
            method: 'DELETE',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Imagen eliminada', 'success');
                loadCurrentImages();
            } else {
                showAlert('Error al eliminar imagen', 'danger');
            }
        });
    }
}

function deleteVideo(videoId) {
    if (confirm('¿Estás seguro de eliminar este video?')) {
        fetch(`<?= BASE_URL ?>dashboard/delete-video/${videoId}`, {
            method: 'DELETE',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Video eliminado', 'success');
                loadCurrentVideos();
            } else {
                showAlert('Error al eliminar video', 'danger');
            }
        });
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Event listeners para cambio de tipo de video
document.getElementById('videoType').addEventListener('change', function() {
    const urlGroup = document.getElementById('videoUrlGroup');
    const fileGroup = document.getElementById('videoFileGroup');
    
    if (this.value === 'upload') {
        urlGroup.style.display = 'none';
        fileGroup.style.display = 'block';
    } else {
        urlGroup.style.display = 'block';
        fileGroup.style.display = 'none';
    }
});

function openStatusModalSimple() {
    console.log('=== SIMPLE MODAL DEBUG ===');
    console.log('BUSINESS_DATA:', BUSINESS_DATA);
    
    currentBusinessId = BUSINESS_DATA.id;
    const isOpen = BUSINESS_DATA.is_open == 1;
    
    console.log('Business ID:', currentBusinessId);
    console.log('is_open from DB:', BUSINESS_DATA.is_open);
    console.log('Calculated isOpen:', isOpen);
    
    // Establecer los radio buttons
    document.getElementById('statusOpen').checked = isOpen;
    document.getElementById('statusClosed').checked = !isOpen;
    
    console.log('After setting:');
    console.log('statusOpen.checked:', document.getElementById('statusOpen').checked);
    console.log('statusClosed.checked:', document.getElementById('statusClosed').checked);
    
    // Manejar razón de cierre
    if (!isOpen && BUSINESS_DATA.closed_reason) {
        document.getElementById('closedReason').value = BUSINESS_DATA.closed_reason;
    } else {
        document.getElementById('closedReason').value = '';
    }
    
    // Mostrar u ocultar el campo de razón de cierre
    toggleClosedReasonField();
    
    // Abrir el modal
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

function openStatusModal(businessId, currentIsOpen) {
    currentBusinessId = businessId;
    
    // Debug explícito
    console.log('=== MODAL DEBUG ===');
    console.log('businessId:', businessId);
    console.log('currentIsOpen:', currentIsOpen);
    console.log('typeof currentIsOpen:', typeof currentIsOpen);
    
    // Intentar múltiples formas de conversión
    let isOpen = false;
    
    if (currentIsOpen === 'true' || currentIsOpen === true) {
        isOpen = true;
        console.log('Detected as OPEN (true)');
    } else if (currentIsOpen === 'false' || currentIsOpen === false) {
        isOpen = false;
        console.log('Detected as CLOSED (false)');
    } else {
        console.log('UNKNOWN VALUE, defaulting to false');
        isOpen = false;
    }
    
    console.log('Final isOpen value:', isOpen);
    
    // Establecer el estado en el modal
    const openRadio = document.getElementById('statusOpen');
    const closedRadio = document.getElementById('statusClosed');
    
    if (openRadio && closedRadio) {
        openRadio.checked = isOpen;
        closedRadio.checked = !isOpen;
        
        console.log('statusOpen.checked:', openRadio.checked);
        console.log('statusClosed.checked:', closedRadio.checked);
    } else {
        console.error('Radio buttons not found!');
    }
    
    // Si está cerrado, cargar la razón actual
    if (!isOpen) {
        const currentReason = '<?= isset($business['closed_reason']) ? addslashes($business['closed_reason']) : '' ?>';
        document.getElementById('closedReason').value = currentReason;
    } else {
        document.getElementById('closedReason').value = '';
    }
    
    // Mostrar u ocultar el campo de razón de cierre
    toggleClosedReasonField();
    
    // Abrir el modal
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

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('statusClosed').addEventListener('change', toggleClosedReasonField);
    document.getElementById('statusOpen').addEventListener('change', toggleClosedReasonField);
    
    // Event listener para el botón de cambiar estado
    const changeStatusBtn = document.getElementById('changeStatusBtn');
    if (changeStatusBtn) {
        changeStatusBtn.addEventListener('click', function() {
            const businessId = this.getAttribute('data-business-id');
            currentBusinessId = businessId;
            
            console.log('Opening modal for business:', businessId);
            
            // Los valores ya están configurados en el HTML por defecto
            // Solo necesitamos abrir el modal
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        });
    }
    
    // Cargar contenido multimedia al cargar la página
    loadCurrentImages();
    loadCurrentVideos();
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>