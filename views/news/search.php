<?php
$title = 'Buscar Noticias - Portal de Noticias';
ob_start();
?>

<style>
        .search-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
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
        .news-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .news-image {
            height: 120px;
            object-fit: cover;
            width: 100%;
            border-radius: 10px 0 0 10px;
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
        .highlight {
            background-color: #fff3cd;
            padding: 0.1rem 0.2rem;
            border-radius: 3px;
            font-weight: bold;
        }
        .no-results {
            text-align: center;
            padding: 3rem 0;
        }
        .back-button {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        .back-button:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>

    <!-- Header de búsqueda -->
    <div class="search-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 mb-4">
                        <i class="fas fa-search"></i> Búsqueda de Noticias
                    </h1>
                    <?php if (!empty($query)): ?>
                    <p class="lead">
                        Resultados para: <strong>"<?php echo htmlspecialchars($query); ?>"</strong>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Botón Volver -->
        <div class="mb-4">
            <a href="index.php?controller=news&action=index" class="back-button">
                <i class="fas fa-arrow-left"></i> Volver a Noticias
            </a>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="controller" value="news">
                            <input type="hidden" name="action" value="search">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control search-box" 
                                       placeholder="Buscar por título, contenido o categoría..." 
                                       value="<?php echo htmlspecialchars($query); ?>"
                                       required>
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="mt-3 text-muted small">
                                <i class="fas fa-info-circle"></i> 
                                Tip: Utiliza palabras clave para encontrar noticias específicas
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados de búsqueda -->
        <?php if (!empty($query)): ?>
        <div class="row">
            <div class="col-12">
                <?php if (!empty($results)): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>
                        <i class="fas fa-list"></i> 
                        <?php echo count($results); ?> resultado(s) encontrado(s)
                    </h3>
                    <small class="text-muted">
                        Búsqueda realizada en <?php echo date('d/m/Y H:i'); ?>
                    </small>
                </div>

                <?php foreach ($results as $noticia): ?>
                <div class="card news-card">
                    <div class="row g-0">
                        <?php if (!empty($noticia['featured_image'])): ?>
                        <div class="col-md-3">
                            <img src="<?php echo htmlspecialchars($noticia['featured_image']); ?>" 
                                 class="news-image" 
                                 alt="<?php echo htmlspecialchars($noticia['title']); ?>">
                        </div>
                        <div class="col-md-9">
                        <?php else: ?>
                        <div class="col-12">
                        <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="category-badge">
                                        <?php echo htmlspecialchars($noticia['category']); ?>
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo date('d/m/Y', strtotime($noticia['created_at'])); ?>
                                    </small>
                                </div>
                                
                                <h5 class="card-title">
                                    <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" 
                                       class="text-decoration-none">
                                        <?php 
                                        $title = htmlspecialchars($noticia['title']);
                                        // Resaltar términos de búsqueda en el título
                                        if (!empty($query)) {
                                            $title = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $title);
                                        }
                                        echo $title;
                                        ?>
                                    </a>
                                </h5>
                                
                                <p class="card-text text-muted">
                                    <?php 
                                    $excerpt = htmlspecialchars(substr($noticia['excerpt'], 0, 200));
                                    // Resaltar términos de búsqueda en el extracto
                                    if (!empty($query)) {
                                        $excerpt = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $excerpt);
                                    }
                                    echo $excerpt . (strlen($noticia['excerpt']) > 200 ? '...' : '');
                                    ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center text-muted">
                                        <small class="me-3">
                                            <i class="fas fa-user"></i> 
                                            <?php echo htmlspecialchars($noticia['author_name'] ?? 'Autor'); ?>
                                        </small>
                                        <small class="me-3">
                                            <i class="fas fa-eye"></i> 
                                            <?php echo number_format($noticia['views']); ?> vistas
                                        </small>
                                        <?php if ($noticia['featured']): ?>
                                        <small class="text-warning">
                                            <i class="fas fa-star"></i> Destacada
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                    <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Leer más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron resultados</h4>
                    <p class="text-muted">
                        No se encontraron noticias que coincidan con "<strong><?php echo htmlspecialchars($query); ?></strong>"
                    </p>
                    <div class="mt-4">
                        <h6 class="text-muted">Sugerencias:</h6>
                        <ul class="list-unstyled text-muted">
                            <li><i class="fas fa-check text-success"></i> Verifica la ortografía de las palabras</li>
                            <li><i class="fas fa-check text-success"></i> Intenta con palabras clave más generales</li>
                            <li><i class="fas fa-check text-success"></i> Usa términos diferentes pero relacionados</li>
                            <li><i class="fas fa-check text-success"></i> Reduce el número de palabras en la búsqueda</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="index.php?controller=news&action=index" class="btn btn-primary">
                            <i class="fas fa-newspaper"></i> Ver todas las noticias
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <!-- Instrucciones de búsqueda -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-4"></i>
                        <h4 class="mb-3">Buscar Noticias</h4>
                        <p class="text-muted mb-4">
                            Utiliza el formulario de búsqueda para encontrar noticias específicas por título, contenido o categoría.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-newspaper text-primary mb-2"></i>
                                    <h6>Por Título</h6>
                                    <small class="text-muted">Busca noticias por su título</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-align-left text-success mb-2"></i>
                                    <h6>Por Contenido</h6>
                                    <small class="text-muted">Encuentra palabras en el contenido</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-tags text-warning mb-2"></i>
                                    <h6>Por Categoría</h6>
                                    <small class="text-muted">Filtra por categorías específicas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Enlaces de administración (si está logueado) -->
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor')): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="fas fa-tools"></i> Administración</h6>
                        <div class="btn-group" role="group">
                            <a href="index.php?controller=news&action=admin" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list"></i> Gestionar Noticias
                            </a>
                            <a href="index.php?controller=news&action=create" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-plus"></i> Nueva Noticia
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus en el campo de búsqueda
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });
    </script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>