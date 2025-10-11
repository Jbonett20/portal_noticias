<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Gestionar Noticias - ' . SITE_NAME;
ob_start();
?>
<style>
.status-published { background-color: #d4edda; color: #155724; }
.status-draft { background-color: #fff3cd; color: #856404; }
.status-archived { background-color: #f8d7da; color: #721c24; }
.featured-badge {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    color: #8b6914;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.7rem;
    font-weight: 600;
}
.news-image {
    width: 60px;
    height: 45px;
    object-fit: cover;
    border-radius: 0.375rem;
}
.table-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
.stats-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.15s ease-in-out;
}
.stats-card:hover {
    transform: translateY(-2px);
}
.btn-toggle-status {
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
}
</style>
</head>
<body class="bg-light">
    <!-- Header principal ya está en layout/main.php -->

    <div class="container-fluid mt-4">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row mb-4 justify-content-center">
            <?php 
                $total = count($news ?? []);
                $published = 0;
                $drafts = 0;
                $featured = 0;
                foreach ($news as $n) {
                    if ($n['status'] === 'published') $published++;
                    if ($n['status'] === 'draft') $drafts++;
                    if (!empty($n['featured']) && $n['featured']) $featured++;
                }
            ?>
            <div class="col-6 col-md-3 mb-2">
                <div class="card stats-card text-white bg-primary text-center">
                    <div class="card-body">
                        <h6 class="card-title">Total Noticias</h6>
                        <h3 class="mb-0"><?php echo $total; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card stats-card text-white bg-success text-center">
                    <div class="card-body">
                        <h6 class="card-title">Publicadas</h6>
                        <h3 class="mb-0"><?php echo $published; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card stats-card text-white bg-warning text-center">
                    <div class="card-body">
                        <h6 class="card-title">Borradores</h6>
                        <h3 class="mb-0"><?php echo $drafts; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card stats-card text-white bg-info text-center">
                    <div class="card-body">
                        <h6 class="card-title">Destacadas</h6>
                        <h3 class="mb-0"><?php echo $featured; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Gestión de Noticias
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning btn-sm rounded-pill px-2" style="background-color: #ff9800; border: none; color: #fff;" onclick="openCreateNewsModal()">
                                    <i class="fas fa-plus"></i> Nueva Noticia
                                </button>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterNews('all'); return false;">
                                    <i class="fas fa-list"></i> Todas
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNews('Publicada'); return false;">
                                    <i class="fas fa-check-circle text-success"></i> Publicadas
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNews('Borrador'); return false;">
                                    <i class="fas fa-edit text-warning"></i> Borradores
                                </a></li>
                            </ul>
<script>
// Filtrar noticias en la tabla sin recargar
function filterNews(status) {
    // Busca la tabla existente y oculta/muestra filas según el estado
    const table = document.querySelector('#newsTableContainer table');
    if (!table) return;
    const rows = table.querySelectorAll('tbody tr');
    let statusNormalized = status.toLowerCase();
    rows.forEach(row => {
        // Busca la celda de estado (5ta celda, índice 4)
        const statusCell = row.cells[4];
        if (!statusCell) return;
        // Extrae el texto visual del estado (ej: 'Publicada', 'Borrador', 'Archivada')
        let cellText = statusCell.textContent.trim().toLowerCase();
        // Si el filtro es 'all', mostrar todo
        if (statusNormalized === 'all') {
            row.style.display = '';
        } else if (
            (statusNormalized === 'publicada' && cellText.includes('publicada')) ||
            (statusNormalized === 'borrador' && cellText.includes('borrador')) ||
            (statusNormalized === 'archivada' && cellText.includes('archivada'))
        ) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div id="newsTableContainer">
                <?php
                // PAGINACIÓN PHP
                $allNews = $news ?? [];
                $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                $perPage = 10;
                $totalNews = count($allNews);
                $totalPages = ceil($totalNews / $perPage);
                $start = ($currentPage - 1) * $perPage;
                $newsPage = array_slice($allNews, $start, $perPage);
                ?>
                <?php if (!empty($newsPage)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Autor</th>
                                <th>Estado</th>
                                <th>Vistas</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($newsPage as $noticia): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($noticia['featured_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($noticia['featured_image']); ?>" 
                                         class="news-image" 
                                         alt="<?php echo htmlspecialchars($noticia['title']); ?>">
                                    <?php else: ?>
                                    <div class="news-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars(substr($noticia['title'], 0, 50)) . (strlen($noticia['title']) > 50 ? '...' : ''); ?></strong>
                                        <?php if (!empty($noticia['featured'])): ?>
                                        <br><span class="featured-badge"><i class="fas fa-star"></i> Destacada</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars(isset($noticia['category']) ? $noticia['category'] : 'General'); ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars($noticia['author_name'] ?? 'N/A'); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $noticia['status']; ?>">
                                        <?php 
                                        switch($noticia['status']) {
                                            case 'published': echo 'Publicada'; break;
                                            case 'draft': echo 'Borrador'; break;
                                            case 'archived': echo 'Archivada'; break;
                                            default: echo ucfirst($noticia['status']);
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted"></i>
                                    <?php echo number_format($noticia['views']); ?>
                                </td>
                                <td>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($noticia['created_at'])); ?>
                                        <br>
                                        <span class="text-muted"><?php echo date('H:i', strtotime($noticia['created_at'])); ?></span>
                                    </small>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- Ver -->
                                            <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" 
                                               class="btn btn-primary btn-xs rounded-pill px-2 py-0 me-1" 
                                               title="Ver noticia" 
                                               target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <!-- Editar -->
                                            <button class="btn btn-success btn-xs me-1 rounded-pill px-2 py-0" style="font-size:0.8rem;" onclick="openEditNewsModal(<?= $noticia['id'] ?>)" title="Editar noticia">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs rounded-pill px-2 py-0" style="font-size:0.8rem;" onclick="deleteNews(<?= $noticia['id'] ?>)" title="Eliminar noticia">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- PAGINADOR -->
                <nav aria-label="Paginador de noticias">
                  <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                      <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Anterior</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                      </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                      <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Siguiente</a></li>
                    <?php endif; ?>
                  </ul>
                </nav>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay noticias</h4>
                    <p class="text-muted">Comienza creando tu primera noticia.</p>
                    <button type="button" class="btn btn-success" onclick="openCreateNewsModal()">
                        <i class="fas fa-plus"></i> Crear Primera Noticia
                    </button>
                </div>
                <?php endif; ?>
                                <!-- Modal Crear Noticia -->
                                <div class="modal fade" id="createNewsModal" tabindex="-1" aria-labelledby="createNewsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="createNewsModalLabel">
                                                    <i class="bi bi-plus-circle me-2"></i>Crear Noticia
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="createNewsForm" enctype="multipart/form-data" onsubmit="submitCreateNewsForm(event)">
                                                <div id="createNewsMsg"></div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="mb-3">
                                                                <label for="create_news_title" class="form-label">Título</label>
                                                                <input type="text" class="form-control" id="create_news_title" name="title" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="create_news_summary" class="form-label">Resumen</label>
                                                                <textarea class="form-control" id="create_news_summary" name="summary" rows="3"></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="create_news_content" class="form-label">Contenido</label>
                                                                <textarea class="form-control" id="create_news_content" name="content" rows="8" required></textarea>
                                                            </div>
                                                            <input type="hidden" id="create_news_author" name="author" value="<?php echo $_SESSION['user']['username'] ?? 'admin'; ?>">
                                                            <input type="hidden" id="create_news_date" name="publication_date" value="<?php echo date('Y-m-d H:i:s'); ?>">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="hidden" id="create_news_section" name="section_id" value="noticias">
                                                            <div class="mb-3">
                                                                <label for="create_news_status" class="form-label">Estado</label>
                                                                <select class="form-control" id="create_news_status" name="status" required>
                                                                    <option value="draft">Borrador</option>
                                                                    <option value="published">Publicado</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="create_news_image" class="form-label">Imagen</label>
                                                                <input type="file" class="form-control" id="create_news_image" name="image" accept="image/*">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="create_news_video" class="form-label">Video</label>
                                                                <input type="file" class="form-control" id="create_news_video" name="video" accept="video/*">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-lg me-1"></i>Crear Noticia
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

    <!-- Bootstrap JS y scripts personalizados al final del body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function reloadNewsTable() {
    fetch('index.php?controller=admin&action=newsList&ajax=1')
        .then(response => response.json())
        .then(data => {
            const tableContainer = document.getElementById('newsTableContainer');
            if (!tableContainer) return;
            if (!data.news || data.news.length === 0) {
                tableContainer.innerHTML = `<div class='text-center py-5'><i class='fas fa-newspaper fa-4x text-muted mb-3'></i><h4 class='text-muted'>No hay noticias</h4><p class='text-muted'>Comienza creando tu primera noticia.</p><button type='button' class='btn btn-success' onclick='openCreateNewsModal()'><i class='fas fa-plus'></i> Crear Primera Noticia</button></div>`;
                return;
            }
            let html = `<div class='table-responsive'><table class='table table-hover'><thead class='table-dark'><tr><th>Imagen</th><th>Título</th><th>Categoría</th><th>Autor</th><th>Estado</th><th>Vistas</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody>`;
            data.news.forEach(n => {
                html += `<tr><td><img src='${n.image_url || ''}' class='news-image' alt=''></td><td>${n.title}</td><td>${n.category || ''}</td><td>${n.author_name || ''}</td><td>${n.status}</td><td>${n.views || 0}</td><td>${n.created_at || ''}</td><td><div class='table-actions'><button class='btn btn-outline-warning btn-xs rounded-pill px-2 py-0' onclick='openEditNewsModal(${n.id})' title='Editar noticia'><i class='bi bi-pencil'></i></button><button class='btn btn-danger btn-xs rounded-pill px-2 py-0' onclick='deleteNews(${n.id})' title='Eliminar noticia'><i class='bi bi-trash'></i></button></div></td></tr>`;
            });
            html += `</tbody></table></div>`;
            tableContainer.innerHTML = html;
        });
}
</script>
    <script>
        function openCreateNewsModal() {
            const modal = new bootstrap.Modal(document.getElementById('createNewsModal'));
            modal.show();
        }
        function submitCreateNewsForm(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            fetch('index.php?controller=admin&action=newsCreate', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const msgDiv = document.getElementById('createNewsMsg');
                if (data.success) {
                    msgDiv.innerHTML = `<div class='alert alert-success'><i class='fas fa-check-circle'></i> ${data.message}</div>`;
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createNewsModal'));
                        modal.hide();
                        location.reload();
                    }, 1200);
                } else {
                    msgDiv.innerHTML = `<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> ${data.message}</div>`;
                }
            })
            .catch(error => {
                const msgDiv = document.getElementById('createNewsMsg');
                msgDiv.innerHTML = `<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> Error de conexión</div>`;
            });
        }
    </script>
    <div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editNewsModalLabel">
                        <i class="bi bi-pencil me-2"></i>Editar Noticia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editNewsForm" onsubmit="submitEditNewsForm(event)">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="edit_news_title" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="edit_news_title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_news_summary" class="form-label">Resumen</label>
                                    <textarea class="form-control" id="edit_news_summary" name="summary" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_news_content" class="form-label">Contenido</label>
                                    <textarea class="form-control" id="edit_news_content" name="content" rows="8" required></textarea>
                                </div>
                                <!-- El campo autor NO se muestra ni se edita aquí -->
                            </div>
                            <div class="col-md-4">
                                <!-- Campo sección eliminado, todas son noticias -->
                                <div class="mb-3">
                                    <label for="edit_news_status" class="form-label">Estado</label>
                                    <select class="form-control" id="edit_news_status" name="status" required>
                                        <option value="draft">Borrador</option>
                                        <option value="published">Publicado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_news_image" class="form-label">Nueva Imagen (opcional)</label>
                                    <input type="file" class="form-control" id="edit_news_image" name="image" accept="image/*">
                                    <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_news_video" class="form-label">Nuevo Video (opcional)</label>
                                    <input type="file" class="form-control" id="edit_news_video" name="video" accept="video/*">
                                    <small class="text-muted">Dejar vacío para mantener el video actual</small>
                                </div>
                                <div class="mb-3" id="current_image_preview">
                                    <!-- Se mostrará la imagen actual aquí -->
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="edit_news_id" name="news_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar esta noticia? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
            function openCreateNewsModal() {
                const modal = new bootstrap.Modal(document.getElementById('createNewsModal'));
                modal.show();
            }

            function submitCreateNewsForm(event) {
                event.preventDefault();
                const formData = new FormData(event.target);
                fetch('/clone/portal_noticias/admin/news-create', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const msgDiv = document.getElementById('createNewsMsg');
                    if (data.success) {
                        msgDiv.innerHTML = `<div class='alert alert-success'><i class='fas fa-check-circle'></i> ${data.message}</div>`;
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('createNewsModal'));
                            modal.hide();
                            location.reload();
                        }, 1200);
                    } else {
                        msgDiv.innerHTML = `<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> ${data.message}</div>`;
                    }
                })
                .catch(error => {
                    const msgDiv = document.getElementById('createNewsMsg');
                    msgDiv.innerHTML = `<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> Error de conexión</div>`;
                });
            }
        // Datos de noticias para el modal
        const newsData = <?= json_encode($allNews ?? []) ?>;
        
        function openEditNewsModal(newsId) {
            const news = newsData.find(n => n.id == newsId);
            if (!news) {
                alert('Noticia no encontrada');
                return;
            }
            
            // Llenar el formulario con los datos de la noticia
            document.getElementById('edit_news_id').value = news.id;
            document.getElementById('edit_news_title').value = news.title;
            document.getElementById('edit_news_summary').value = news.summary || '';
            document.getElementById('edit_news_content').value = news.content;
            // Se eliminó el campo edit_news_section
            document.getElementById('edit_news_status').value = news.status;
            
            // Mostrar imagen actual si existe
            const imagePreview = document.getElementById('current_image_preview');
            if (news.image) {
                imagePreview.innerHTML = `
                    <label class="form-label">Imagen Actual:</label><br>
                    <img src="${news.image}" alt="Imagen actual" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                `;
            } else {
                imagePreview.innerHTML = '<small class="text-muted">Sin imagen actual</small>';
            }
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editNewsModal'));
            modal.show();
        }
        
        function submitEditNewsForm(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const newsId = formData.get('news_id');
            
            // Enviar datos via fetch
            fetch(`<?= BASE_URL ?>admin/news-update/${newsId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editNewsModal'));
                    modal.hide();
                    
                    // Mostrar mensaje de éxito
                    alert('Noticia actualizada correctamente');
                    
                    // Recargar la página
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo actualizar la noticia'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
            });
        }
        
        // Toggle status
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.btn-toggle-status');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const newStatus = this.dataset.status;
                    
                    fetch('index.php?controller=news&action=toggleStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}&status=${newStatus}&csrf_token=<?php echo generarTokenCSRF(); ?>`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cambiar el estado');
                    });
                });
            });
        });

        // Eliminar noticia
        let deleteNewsId = null;
        
        function deleteNews(id) {
            deleteNewsId = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteNewsId) {
                fetch('index.php?controller=admin&action=newsDelete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${deleteNewsId}&csrf_token=<?php echo generarTokenCSRF(); ?>`
                })
                .then(response => response.text())
                .then(data => {
                    // Cerrar el modal de confirmación
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    modal.hide();
                    let responseJson;
                    try {
                        responseJson = JSON.parse(data);
                    } catch (e) {
                        responseJson = { success: false, message: 'Respuesta inválida del servidor' };
                    }
                    if (responseJson.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Noticia eliminada',
                            text: responseJson.message || 'La noticia fue eliminada correctamente',
                            showConfirmButton: false,
                            timer: 100,
                            willClose: () => { reloadNewsTable(); }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: responseJson.message || 'No se pudo eliminar la noticia'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar la noticia'
                    });
                });
            }
        });
    </script>

<!-- Cierre de contenedores principales si faltan -->
</div> <!-- container-fluid -->

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
// Fin de archivo