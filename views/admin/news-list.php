<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Gestionar Noticias - ' . SITE_NAME;
ob_start();
?>
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
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?controller=dashboard">
                <i class="fas fa-newspaper"></i> Admin - Noticias
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?controller=news&action=index">
                    <i class="fas fa-eye"></i> Ver Sitio
                </a>
                <a class="nav-link" href="index.php?controller=dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?controller=auth&action=logout">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

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
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Noticias</h6>
                                <h3 class="mb-0"><?php echo isset($totalNews) ? $totalNews : count($allNews ?? []); ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-newspaper fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-success text-white">
                    <div class="body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Publicadas</h6>
                                <h3 class="mb-0">
                                    <?php 
                                    $published = 0;
                                    if (isset($allNews)) {
                                        foreach ($allNews as $news) {
                                            if ($news['status'] === 'published') $published++;
                                        }
                                    }
                                    echo $published;
                                    ?>
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Borradores</h6>
                                <h3 class="mb-0">
                                    <?php 
                                    $drafts = 0;
                                    if (isset($allNews)) {
                                        foreach ($allNews as $news) {
                                            if ($news['status'] === 'draft') $drafts++;
                                        }
                                    }
                                    echo $drafts;
                                    ?>
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-edit fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Destacadas</h6>
                                <h3 class="mb-0">
                                    <?php 
                                    $featured = 0;
                                    if (isset($allNews)) {
                                        foreach ($allNews as $news) {
                                            if ($news['featured']) $featured++;
                                        }
                                    }
                                    echo $featured;
                                    ?>
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-star fa-2x opacity-75"></i>
                            </div>
                        </div>
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
                                <button type="button" class="btn btn-success" onclick="openCreateNewsModal()">
                                    <i class="fas fa-plus"></i> Nueva Noticia
                                </button>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?controller=news&action=admin&status=all">
                                    <i class="fas fa-list"></i> Todas
                                </a></li>
                                <li><a class="dropdown-item" href="?controller=news&action=admin&status=published">
                                    <i class="fas fa-check-circle text-success"></i> Publicadas
                                </a></li>
                                <li><a class="dropdown-item" href="?controller=news&action=admin&status=draft">
                                    <i class="fas fa-edit text-warning"></i> Borradores
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php if (!empty($allNews)): ?>
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
                            <?php foreach ($allNews as $noticia): ?>
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
                                        <?php if ($noticia['featured']): ?>
                                        <br><span class="featured-badge"><i class="fas fa-star"></i> Destacada</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($noticia['category']); ?>
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
                                               class="btn btn-outline-primary" 
                                               title="Ver noticia" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Editar -->
                                            <button class="btn btn-outline-warning" 
                                                    onclick="openEditNewsModal(<?= $noticia['id'] ?>)"
                                                    title="Editar noticia">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            <!-- Toggle Status -->
                                            <button class="btn btn-outline-<?php echo $noticia['status'] === 'published' ? 'secondary' : 'success'; ?> btn-toggle-status" 
                                                    data-id="<?php echo $noticia['id']; ?>"
                                                    data-status="<?php echo $noticia['status'] === 'published' ? 'draft' : 'published'; ?>"
                                                    title="<?php echo $noticia['status'] === 'published' ? 'Despublicar' : 'Publicar'; ?>">
                                                <i class="fas fa-<?php echo $noticia['status'] === 'published' ? 'eye-slash' : 'check'; ?>"></i>
                                            </button>
                                            
                                            <!-- Eliminar (solo admin) -->
                                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteNews(<?php echo $noticia['id']; ?>)"
                                                    title="Eliminar noticia">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center">
                        <?php 
                        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
                        
                        // Página anterior
                        if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=news&action=admin&status=<?php echo $status; ?>&page=<?php echo $currentPage - 1; ?>">
                                Anterior
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php 
                        // Páginas numeradas
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        
                        for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?controller=news&action=admin&status=<?php echo $status; ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php // Página siguiente
                        if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?controller=news&action=admin&status=<?php echo $status; ?>&page=<?php echo $currentPage + 1; ?>">
                                Siguiente
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

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
            </div>
        </div>
    </div>

    <!-- Modal de Creación de Noticia (ubicado al final del body para asegurar funcionalidad) -->
    </div>
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
        function openCreateNewsModal() {
            const modal = new bootstrap.Modal(document.getElementById('createNewsModal'));
            modal.show();
        }
        function submitCreateNewsForm(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            fetch('/admin/news-create', {
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
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_news_section" class="form-label">Sección</label>
                                    <select class="form-control" id="edit_news_section" name="section_id" required>
                                        <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
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
            document.getElementById('edit_news_section').value = news.section_id;
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?controller=news&action=delete';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = deleteNewsId;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?php echo generarTokenCSRF(); ?>';
                
                form.appendChild(idInput);
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>