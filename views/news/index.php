<?php
$title = 'Noticias';
ob_start();
?>

<style>
        .news-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .news-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .featured-news {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .featured-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        .sidebar-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .search-box {
            border-radius: 25px;
            border: 2px solid #e3f2fd;
            padding: 0.75rem 1.5rem;
        }
        .search-box:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .category-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        .pagination .page-link {
            border-radius: 25px;
            margin: 0 2px;
            border: none;
            color: #667eea;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
    </style>

    <div class="container mt-4">
        <!-- Noticias Destacadas -->
        <?php if (!empty($featuredNews)): ?>
        <div class="featured-news">
            <h2 class="mb-4"><i class="fas fa-star"></i> Noticias Destacadas</h2>
            <div class="row">
                <?php foreach ($featuredNews as $featured): ?>
                <div class="col-md-4 mb-3">
                    <div class="card featured-card h-100">
                        <?php if (!empty($featured['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($featured['featured_image']); ?>" 
                             class="card-img-top news-image" 
                             alt="<?php echo htmlspecialchars($featured['title']); ?>">
                        <?php endif; ?>
                        <div class="card-body text-white">
                            <span class="category-badge"><?php echo htmlspecialchars($featured['category']); ?></span>
                            <h5 class="card-title mt-2"><?php echo htmlspecialchars($featured['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($featured['excerpt'], 0, 100)) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small><i class="fas fa-eye"></i> <?php echo number_format($featured['views']); ?> vistas</small>
                                <small><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($featured['created_at'])); ?></small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($featured['slug']); ?>" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right"></i> Leer más
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Contenido Principal -->
            <div class="col-lg-8">
                <!-- Barra de búsqueda -->
                <div class="card sidebar-card">
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="controller" value="news">
                            <input type="hidden" name="action" value="search">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control search-box" 
                                       placeholder="Buscar noticias..." 
                                       value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de Noticias -->
                <h2 class="mb-4"><i class="fas fa-newspaper"></i> Últimas Noticias</h2>
                
                <?php if (!empty($news)): ?>
                <div class="row">
                    <?php foreach ($news as $noticia): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card news-card">
                            <?php if (!empty($noticia['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($noticia['featured_image']); ?>" 
                                 class="card-img-top news-image" 
                                 alt="<?php echo htmlspecialchars($noticia['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="category-badge"><?php echo isset($noticia['category']) ? htmlspecialchars($noticia['category']) : 'General'; ?></span>
                                <h5 class="card-title mt-2"><?php echo isset($noticia['title']) ? htmlspecialchars($noticia['title']) : 'Sin título'; ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo isset($noticia['excerpt']) ? htmlspecialchars(substr($noticia['excerpt'], 0, 120)) . '...' : 'Sin descripción disponible'; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center text-muted">
                                    <small>
                                        <i class="fas fa-user"></i> 
                                        <?php echo htmlspecialchars($noticia['author_name'] ?? 'Autor'); ?>
                                    </small>
                                    <small>
                                        <i class="fas fa-eye"></i> 
                                        <?php echo number_format($noticia['views']); ?>
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo date('d/m/Y H:i', strtotime($noticia['created_at'])); ?>
                                    </small>
                                    <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Leer más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if (isset($pagination) && $totalPages > 1): ?>
                <nav aria-label="Paginación de noticias">
                    <ul class="pagination justify-content-center">
                        <?php if (isset($pagination['prev'])): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $pagination['prev']; ?>">
                                <i class="fas fa-chevron-left"></i> Anterior
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isset($pagination['pages'])): ?>
                        <?php foreach ($pagination['pages'] as $page): ?>
                        <li class="page-item <?php echo $page['current'] ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $page['url']; ?>">
                                <?php echo $page['number']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if (isset($pagination['next'])): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $pagination['next']; ?>">
                                Siguiente <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay noticias disponibles</h4>
                    <p class="text-muted">Vuelve pronto para ver las últimas noticias.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Noticias Recientes -->
                <?php if (!empty($latestNews)): ?>
                <div class="card sidebar-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clock"></i> Noticias Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($latestNews as $recent): ?>
                        <div class="d-flex mb-3">
                            <?php if (!empty($recent['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($recent['featured_image']); ?>" 
                                 class="me-3 rounded" 
                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                 alt="<?php echo htmlspecialchars($recent['title']); ?>">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($recent['slug']); ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars(substr($recent['title'], 0, 80)) . (strlen($recent['title']) > 80 ? '...' : ''); ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('d/m/Y', strtotime($recent['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        <?php if (!array_key_last($latestNews) === array_search($recent, $latestNews)): ?>
                        <hr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Enlaces de Administración (si está logueado) -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor')): ?>
                <div class="card sidebar-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Administración</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?controller=news&action=admin" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Gestionar Noticias
                            </a>
                            <a href="index.php?controller=news&action=create" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> Nueva Noticia
                            </a>
                            <a href="index.php?controller=dashboard" class="btn btn-outline-info">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>