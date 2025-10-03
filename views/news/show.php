<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($news['title']) ? htmlspecialchars($news['title']) : 'Noticia'; ?> - Portal de Noticias</title>
    <meta name="description" content="<?php echo isset($news['meta_description']) ? htmlspecialchars($news['meta_description']) : htmlspecialchars(substr($news['excerpt'], 0, 160)); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .news-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .news-content {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }
        .news-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1rem 0;
        }
        .news-content h1, .news-content h2, .news-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }
        .news-meta {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .category-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        .related-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .related-image {
            height: 150px;
            object-fit: cover;
            width: 100%;
            border-radius: 10px 10px 0 0;
        }
        .share-buttons .btn {
            border-radius: 50px;
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
        }
        .news-gallery {
            margin: 2rem 0;
        }
        .gallery-image {
            border-radius: 10px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .gallery-image:hover {
            transform: scale(1.02);
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
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'views/layout/navbar.php'; ?>

    <!-- Header de la Noticia -->
    <div class="news-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="category-badge"><?php echo htmlspecialchars($news['category']); ?></span>
                    <h1 class="display-4 mt-3 mb-4"><?php echo htmlspecialchars($news['title']); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($news['excerpt']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Contenido Principal -->
            <div class="col-lg-8">
                <!-- Botón Volver -->
                <div class="mb-4">
                    <a href="index.php?controller=news&action=index" class="back-button">
                        <i class="fas fa-arrow-left"></i> Volver a Noticias
                    </a>
                </div>

                <!-- Meta información -->
                <div class="news-meta">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-primary me-2"></i>
                                <span><strong>Autor:</strong> <?php echo htmlspecialchars($news['author_name'] ?? 'Autor'); ?></span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span><strong>Publicado:</strong> <?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <i class="fas fa-eye text-primary me-2"></i>
                                <span><strong><?php echo number_format($news['views']); ?></strong> visualizaciones</span>
                            </div>
                            <?php if (!empty($news['updated_at']) && $news['updated_at'] !== $news['created_at']): ?>
                            <div class="d-flex align-items-center justify-content-md-end mt-2">
                                <i class="fas fa-edit text-warning me-2"></i>
                                <span><strong>Actualizado:</strong> <?php echo date('d/m/Y H:i', strtotime($news['updated_at'])); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Imagen principal -->
                <?php if (!empty($news['featured_image'])): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($news['featured_image']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>"
                         style="width: 100%; max-height: 400px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <!-- Contenido de la noticia -->
                <div class="news-content">
                    <?php echo nl2br($news['content']); ?>
                </div>

                <!-- Galería de imágenes adicionales -->
                <?php if (!empty($images)): ?>
                <div class="news-gallery">
                    <h4 class="mb-3"><i class="fas fa-images"></i> Galería de Imágenes</h4>
                    <div class="row">
                        <?php foreach ($images as $image): ?>
                        <div class="col-md-4 mb-3">
                            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 class="img-fluid gallery-image shadow-sm" 
                                 alt="<?php echo htmlspecialchars($image['caption'] ?? 'Imagen de la noticia'); ?>"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#imageModal"
                                 data-bs-image="<?php echo htmlspecialchars($image['image_path']); ?>"
                                 data-bs-caption="<?php echo htmlspecialchars($image['caption'] ?? ''); ?>">
                            <?php if (!empty($image['caption'])): ?>
                            <p class="text-center text-muted mt-2 small"><?php echo htmlspecialchars($image['caption']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Botones de compartir -->
                <div class="share-buttons text-center my-4">
                    <h5 class="mb-3">Compartir esta noticia</h5>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       target="_blank" class="btn btn-primary">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($news['title']); ?>" 
                       target="_blank" class="btn btn-info">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <button class="btn btn-secondary" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i> Copiar enlace
                    </button>
                </div>

                <!-- Enlaces de administración (si está logueado) -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor')): ?>
                <div class="admin-controls bg-warning bg-opacity-10 p-3 rounded mt-4">
                    <h6><i class="fas fa-tools"></i> Controles de Administración</h6>
                    <div class="btn-group" role="group">
                        <a href="index.php?controller=news&action=edit&id=<?php echo $news['id']; ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="index.php?controller=news&action=admin" 
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-list"></i> Gestionar Noticias
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteNews(<?php echo $news['id']; ?>)">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Noticias Relacionadas -->
                <?php if (!empty($relatedNews)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-newspaper"></i> Noticias Relacionadas</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($relatedNews as $related): ?>
                        <div class="card related-card mb-3">
                            <?php if (!empty($related['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($related['featured_image']); ?>" 
                                 class="related-image" 
                                 alt="<?php echo htmlspecialchars($related['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($related['slug']); ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars(substr($related['title'], 0, 100)) . (strlen($related['title']) > 100 ? '...' : ''); ?>
                                    </a>
                                </h6>
                                <p class="card-text text-muted small">
                                    <?php echo htmlspecialchars(substr($related['excerpt'], 0, 80)) . '...'; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo date('d/m/Y', strtotime($related['created_at'])); ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye"></i> 
                                        <?php echo number_format($related['views']); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal para galería de imágenes -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Imagen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="">
                    <p id="modalCaption" class="mt-3 text-muted"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal de galería
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const imageSrc = button.getAttribute('data-bs-image');
                const caption = button.getAttribute('data-bs-caption');
                
                const modalImage = document.getElementById('modalImage');
                const modalCaption = document.getElementById('modalCaption');
                
                modalImage.src = imageSrc;
                modalCaption.textContent = caption || '';
            });
        }

        // Copiar enlace
        function copyToClipboard() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                alert('Enlace copiado al portapapeles');
            }, function(err) {
                console.error('Error al copiar: ', err);
            });
        }

        // Eliminar noticia (solo admin)
        function deleteNews(id) {
            if (confirm('¿Estás seguro de que quieres eliminar esta noticia? Esta acción no se puede deshacer.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?controller=news&action=delete';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?php echo generarTokenCSRF(); ?>';
                
                form.appendChild(idInput);
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>