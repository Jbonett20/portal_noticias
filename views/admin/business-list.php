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
                                    <button class="btn btn-outline-primary" 
                                            onclick="openEditBusinessModal(<?= $business['id'] ?>)" 
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
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

<!-- Modal de Edición de Negocio -->
<div class="modal fade" id="editBusinessModal" tabindex="-1" aria-labelledby="editBusinessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editBusinessModalLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Negocio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBusinessForm" onsubmit="submitEditBusinessForm(event)" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_name" class="form-label">Nombre del Negocio</label>
                                <input type="text" class="form-control" id="edit_business_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_section" class="form-label">Sección</label>
                                <select class="form-control" id="edit_business_section" name="section_id" required>
                                    <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_business_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_business_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="edit_business_address" name="address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_business_phone" name="phone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_business_email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_business_website" class="form-label">Sitio Web</label>
                                <input type="url" class="form-control" id="edit_business_website" name="website">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_business_logo" class="form-label">Nuevo Logo (opcional)</label>
                        <input type="file" class="form-control" id="edit_business_logo" name="logo" accept="image/*">
                        <small class="text-muted">Dejar vacío para mantener el logo actual</small>
                    </div>
                    
                    <div class="mb-3" id="current_logo_preview">
                        <!-- Se mostrará el logo actual aquí -->
                    </div>
                    
                    <input type="hidden" id="edit_business_id" name="business_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Datos de negocios para el modal
const businessesData = <?= json_encode($businesses) ?>;

function openEditBusinessModal(businessId) {
    const business = businessesData.find(b => b.id == businessId);
    if (!business) {
        alert('Negocio no encontrado');
        return;
    }
    
    // Llenar el formulario con los datos del negocio
    document.getElementById('edit_business_id').value = business.id;
    document.getElementById('edit_business_name').value = business.name;
    document.getElementById('edit_business_section').value = business.section_id;
    document.getElementById('edit_business_description').value = business.description || '';
    document.getElementById('edit_business_address').value = business.address || '';
    document.getElementById('edit_business_phone').value = business.phone || '';
    document.getElementById('edit_business_email').value = business.email || '';
    document.getElementById('edit_business_website').value = business.website || '';
    
    // Mostrar logo actual si existe
    const logoPreview = document.getElementById('current_logo_preview');
    if (business.logo_path) {
        logoPreview.innerHTML = `
            <label class="form-label">Logo Actual:</label><br>
            <img src="${business.logo_path}" alt="Logo actual" style="max-width: 150px; max-height: 100px;" class="img-thumbnail">
        `;
    } else {
        logoPreview.innerHTML = '<small class="text-muted">Sin logo actual</small>';
    }
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('editBusinessModal'));
    modal.show();
}

function submitEditBusinessForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const businessId = formData.get('business_id');
    
    // Enviar datos via fetch
    fetch(`<?= BASE_URL ?>admin/business-update/${businessId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editBusinessModal'));
            modal.hide();
            
            // Mostrar mensaje de éxito
            alert('Negocio actualizado correctamente');
            
            // Recargar la página
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'No se pudo actualizar el negocio'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}

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