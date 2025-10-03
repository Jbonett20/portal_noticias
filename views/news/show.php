<?php
$title = isset($news['title']) ? htmlspecialchars($news['title']) . ' - Portal de Noticias' : 'Noticia - Portal de Noticias';
ob_start();
?>

<style>
    .news-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1a1a2e 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    .news-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    .news-content {
        font-size: 1.2rem;
        line-height: 1.9;
        text-align: justify;
        background: white;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(30, 60, 114, 0.15);
        margin-bottom: 2rem;
        border: 1px solid #e8f0fe;
        position: relative;
    }
    .news-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, #1e3c72, #2a5298);
        border-radius: 20px 0 0 20px;
    }
    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin: 2rem 0;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.2);
        border: 3px solid #f8f9fa;
    }
    .news-content h1, .news-content h2, .news-content h3 {
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        color: #1a1a2e;
        font-weight: 700;
        position: relative;
    }
    .news-content h1::after, .news-content h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, #1e3c72, #2a5298);
        border-radius: 3px;
    }
    .news-meta {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 3rem;
        border: 1px solid #cbd5e0;
        box-shadow: 0 4px 20px rgba(30, 60, 114, 0.08);
    }
    .category-badge {
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 30px;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        border: 2px solid rgba(255,255,255,0.2);
    }
    .related-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        box-shadow: 0 8px 30px rgba(30, 60, 114, 0.12);
        border-radius: 20px;
        overflow: hidden;
        background: white;
        border: 1px solid #e2e8f0;
    }
    .related-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.25);
        border-color: #2a5298;
    }
    .related-image {
        height: 180px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.4s ease;
    }
    .related-card:hover .related-image {
        transform: scale(1.1);
    }
    .share-buttons {
        margin: 2rem 0;
        padding: 2rem;
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-radius: 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .share-buttons .btn {
        border-radius: 50px;
        margin: 0 0.5rem;
        padding: 0.8rem 1.5rem;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-facebook { 
        background: linear-gradient(45deg, #1877f2, #42a5f5); 
        color: white; 
    }
    .btn-twitter { 
        background: linear-gradient(45deg, #1da1f2, #64b5f6); 
        color: white; 
    }
    .btn-whatsapp { 
        background: linear-gradient(45deg, #25d366, #66bb6a); 
        color: white; 
    }
    .btn-linkedin { 
        background: linear-gradient(45deg, #0077b5, #1976d2); 
        color: white; 
    }
    .share-buttons .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .news-gallery {
        margin: 3rem 0;
        padding: 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(30, 60, 114, 0.1);
    }
    .gallery-image {
        border-radius: 20px;
        margin-bottom: 1.5rem;
        cursor: pointer;
        transition: all 0.4s ease;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.15);
        border: 3px solid #f8f9fa;
    }
    .gallery-image:hover {
        transform: scale(1.05) rotate(1deg);
        box-shadow: 0 15px 40px rgba(30, 60, 114, 0.3);
    }
    .back-button {
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        border: none;
        border-radius: 30px;
        padding: 1rem 2rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 2rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 6px 20px rgba(30, 60, 114, 0.3);
        border: 2px solid rgba(255,255,255,0.1);
    }
    .back-button:hover {
        transform: translateY(-3px);
        color: white;
        text-decoration: none;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.4);
        background: linear-gradient(45deg, #2a5298, #1e3c72);
    }
    .featured-image {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(30, 60, 114, 0.25);
        margin: 3rem 0;
        border: 4px solid white;
    }
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }
    .breadcrumb-item a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    .breadcrumb-item a:hover {
        color: white;
    }
    .breadcrumb-item.active {
        color: white;
        font-weight: 600;
    }
    .container {
        background: transparent;
    }
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }
    .news-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .news-subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
        font-weight: 400;
        line-height: 1.6;
    }
    .author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }
    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(45deg, #2a5298, #1e3c72);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .sidebar-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 30px rgba(30, 60, 114, 0.1);
        border: 1px solid #e2e8f0;
    }
    .news-content p {
        margin-bottom: 1.5rem;
        text-align: justify;
        color: #374151;
    }
    .news-content p:first-letter {
        font-size: 4rem;
        font-weight: 700;
        float: left;
        line-height: 3rem;
        margin: 0.5rem 0.8rem 0 0;
        color: #1e3c72;
        font-family: Georgia, serif;
    }
    .news-content blockquote {
        border-left: 5px solid #1e3c72;
        padding-left: 2rem;
        margin: 2rem 0;
        font-style: italic;
        background: #f8fafc;
        padding: 1.5rem 2rem;
        border-radius: 0 15px 15px 0;
        color: #64748b;
    }
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(to right, #1e3c72, #2a5298);
        z-index: 1000;
        transition: width 0.3s ease;
    }
    .floating-share {
        position: fixed;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 100;
        display: none;
    }
    .floating-share.show {
        display: block;
        animation: slideInLeft 0.5s ease-out;
    }
    @keyframes slideInLeft {
        from { 
            opacity: 0; 
            transform: translateX(-100%) translateY(-50%); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0) translateY(-50%); 
        }
    }
    .floating-share .btn {
        display: block;
        margin-bottom: 0.5rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    .floating-share .btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    .news-tags {
        margin: 2rem 0;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 20px;
        border: 1px solid #cbd5e0;
    }
    .tag-item {
        display: inline-block;
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        margin: 0.25rem;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .tag-item:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        border-color: rgba(255,255,255,0.3);
    }
    
    /* Estilos mejorados para sidebar */
    .related-news-container {
        position: relative;
    }
    .related-news-item {
        padding: 1rem;
        border-radius: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: transparent;
    }
    .related-news-item:hover {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(42, 82, 152, 0.05));
        transform: translateX(8px) !important;
        box-shadow: 0 4px 20px rgba(30, 60, 114, 0.1);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .sidebar-section {
        position: relative;
        overflow: hidden;
    }
    .sidebar-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #1e3c72, #2a5298, #1a1a2e);
        border-radius: 20px 20px 0 0;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .floating-share {
            display: none !important;
        }
        .news-title {
            font-size: 2rem;
        }
        .related-news-item:hover {
            transform: none !important;
        }
    }
</style>

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
                        <i class="bi bi-arrow-left-circle-fill me-2"></i> Volver a Noticias
                    </a>
                </div>

                <!-- Meta información -->
                <div class="news-meta">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Autor</div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($news['author_name'] ?? 'Portal de Noticias'); ?></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(45deg, #10b981, #34d399); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-calendar-event text-white"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Publicado</div>
                                    <div class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3 justify-content-md-end">
                                <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(45deg, #f59e0b, #fbbf24); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-fill text-white"></i>
                                </div>
                                <div class="text-md-end">
                                    <div class="text-muted small">Visualizaciones</div>
                                    <div class="fw-bold"><?php echo number_format($news['views']); ?></div>
                                </div>
                            </div>
                            <?php if (!empty($news['updated_at']) && $news['updated_at'] !== $news['created_at']): ?>
                            <div class="d-flex align-items-center justify-content-md-end">
                                <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(45deg, #8b5cf6, #a78bfa); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-pencil-square text-white"></i>
                                </div>
                                <div class="text-md-end">
                                    <div class="text-muted small">Actualizado</div>
                                    <div class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($news['updated_at'])); ?></div>
                                </div>
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
                    
                    <!-- Tags de la noticia -->
                    <div class="news-tags mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width: 40px; height: 40px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="bi bi-tags-fill text-white"></i>
                            </div>
                            <h6 class="mb-0 fw-bold" style="color: #1a1a2e;">Etiquetas</h6>
                        </div>
                        <div>
                            <a href="#" class="tag-item">
                                <i class="bi bi-tag me-1"></i> <?= htmlspecialchars($news['category']) ?>
                            </a>
                            <?php if (isset($news['section_title'])): ?>
                            <a href="index.php?controller=section&action=show&slug=<?= htmlspecialchars($news['section_slug'] ?? '') ?>" class="tag-item">
                                <i class="bi bi-bookmark me-1"></i> <?= htmlspecialchars($news['section_title']) ?>
                            </a>
                            <?php endif; ?>
                            <a href="#" class="tag-item">
                                <i class="bi bi-calendar me-1"></i> <?= date('F Y', strtotime($news['created_at'])) ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Galería de imágenes adicionales -->
                <?php if (!empty($images)): ?>
                <div class="news-gallery">
                    <h4 class="mb-4" style="color: #1a1a2e; display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-images text-white fs-5"></i>
                        </div>
                        Galería de Imágenes
                    </h4>
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
                <div class="share-buttons text-center my-5">
                    <div class="mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="bi bi-share-fill text-white fs-4"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #1a1a2e;">Compartir esta noticia</h5>
                        <p class="text-muted">Ayúdanos a difundir esta información</p>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="btn btn-facebook">
                            <i class="bi bi-facebook me-2"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($news['title']); ?>" 
                           target="_blank" class="btn btn-twitter">
                            <i class="bi bi-twitter me-2"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="btn btn-whatsapp">
                            <i class="bi bi-whatsapp me-2"></i> WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="btn btn-linkedin">
                            <i class="bi bi-linkedin me-2"></i> LinkedIn
                        </a>
                        <button class="btn" style="background: linear-gradient(45deg, #6b7280, #9ca3af); color: white;" onclick="copyToClipboard()">
                            <i class="bi bi-clipboard me-2"></i> Copiar enlace
                        </button>
                    </div>
                </div>

                <!-- Enlaces de administración (si está logueado) -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor')): ?>
                <div class="admin-controls p-4 rounded-4 mt-5" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b;">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(45deg, #f59e0b, #fbbf24); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="bi bi-gear-fill text-white"></i>
                        </div>
                        <h6 class="mb-0 fw-bold" style="color: #92400e;">Controles de Administración</h6>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="index.php?controller=news&action=edit&id=<?php echo $news['id']; ?>" 
                           class="btn btn-sm" style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; border-radius: 20px;">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </a>
                        <a href="index.php?controller=news&action=admin" 
                           class="btn btn-sm" style="background: linear-gradient(45deg, #10b981, #34d399); color: white; border-radius: 20px;">
                            <i class="bi bi-list-ul me-1"></i> Gestionar Noticias
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <button class="btn btn-sm" style="background: linear-gradient(45deg, #ef4444, #f87171); color: white; border-radius: 20px;" onclick="deleteNews(<?php echo $news['id']; ?>)">
                            <i class="bi bi-trash3 me-1"></i> Eliminar
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
                <div class="sidebar-section">
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1a1a2e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3); border: 4px solid rgba(255,255,255,0.1);">
                            <i class="bi bi-newspaper text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: #1a1a2e;">Noticias Relacionadas</h4>
                        <p class="text-muted small">Descubre más contenido interesante</p>
                    </div>
                    
                    <div class="related-news-container">
                        <?php foreach ($relatedNews as $index => $related): ?>
                        <div class="related-news-item mb-4" style="opacity: 0; transform: translateY(20px); animation: fadeInUp 0.6s ease-out forwards; animation-delay: <?= $index * 0.1 ?>s;">
                            <div class="row g-0 align-items-center">
                                <?php if (!empty($related['featured_image'])): ?>
                                <div class="col-4">
                                    <div style="position: relative; overflow: hidden; border-radius: 15px; height: 80px;">
                                        <img src="<?php echo htmlspecialchars($related['featured_image']); ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                             alt="<?php echo htmlspecialchars($related['title']); ?>"
                                             onmouseover="this.style.transform='scale(1.1)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(30, 60, 114, 0.1), rgba(42, 82, 152, 0.1));"></div>
                                    </div>
                                </div>
                                <div class="col-8 ps-3">
                                <?php else: ?>
                                <div class="col-3">
                                    <div style="width: 60px; height: 60px; background: linear-gradient(45deg, #e2e8f0, #cbd5e0); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-newspaper" style="color: #64748b; font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="col-9 ps-3">
                                <?php endif; ?>
                                    <h6 class="mb-2" style="line-height: 1.3;">
                                        <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($related['slug']); ?>" 
                                           class="text-decoration-none" 
                                           style="color: #1a1a2e; font-weight: 600; font-size: 0.95rem; transition: color 0.3s ease;"
                                           onmouseover="this.style.color='#2a5298'"
                                           onmouseout="this.style.color='#1a1a2e'">
                                            <?php echo htmlspecialchars(substr($related['title'], 0, 60)) . (strlen($related['title']) > 60 ? '...' : ''); ?>
                                        </a>
                                    </h6>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 24px; height: 24px; background: linear-gradient(45deg, #10b981, #34d399); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.5rem;">
                                                <i class="bi bi-calendar3 text-white" style="font-size: 0.7rem;"></i>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem; font-weight: 500;">
                                                <?php echo date('d/m/Y', strtotime($related['created_at'])); ?>
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex align-items-center">
                                            <div style="width: 24px; height: 24px; background: linear-gradient(45deg, #f59e0b, #fbbf24); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.5rem;">
                                                <i class="bi bi-eye text-white" style="font-size: 0.7rem;"></i>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem; font-weight: 500;">
                                                <?php echo number_format($related['views']); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($index < count($relatedNews) - 1): ?>
                            <hr style="margin: 1.5rem 0; border: none; height: 1px; background: linear-gradient(to right, transparent, #e2e8f0, transparent);">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php?controller=news&action=index" 
                           class="btn" 
                           style="background: linear-gradient(45deg, #1e3c72, #2a5298); 
                                  color: white; 
                                  border: none; 
                                  border-radius: 25px; 
                                  padding: 0.8rem 2rem; 
                                  font-weight: 600; 
                                  font-size: 0.9rem;
                                  box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
                                  transition: all 0.3s ease;
                                  text-transform: uppercase;
                                  letter-spacing: 0.5px;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(30, 60, 114, 0.4)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(30, 60, 114, 0.3)'">
                            <i class="bi bi-arrow-right-circle me-2"></i>Ver Todas las Noticias
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
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

        // Barra de progreso de lectura
        function updateReadingProgress() {
            const article = document.querySelector('.news-content');
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight - windowHeight;
            const scrollTop = window.pageYOffset;
            const progress = (scrollTop / documentHeight) * 100;
            
            document.getElementById('reading-progress').style.width = progress + '%';
            
            // Mostrar botones flotantes después del 20% de scroll
            const floatingShare = document.getElementById('floating-share');
            if (progress > 20) {
                floatingShare.classList.add('show');
            } else {
                floatingShare.classList.remove('show');
            }
        }

        // Event listeners
        window.addEventListener('scroll', updateReadingProgress);
        window.addEventListener('resize', updateReadingProgress);
        
        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animación de entrada para elementos
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Aplicar animaciones a elementos
        document.querySelectorAll('.news-meta, .news-gallery, .share-buttons, .sidebar-section').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>

    <!-- Barra de progreso de lectura -->
    <div class="reading-progress" id="reading-progress"></div>

    <!-- Botones flotantes de compartir -->
    <div class="floating-share" id="floating-share">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
           target="_blank" class="btn" style="background: #1877f2; color: white;" title="Compartir en Facebook">
            <i class="bi bi-facebook"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($news['title']); ?>" 
           target="_blank" class="btn" style="background: #1da1f2; color: white;" title="Compartir en Twitter">
            <i class="bi bi-twitter"></i>
        </a>
        <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
           target="_blank" class="btn" style="background: #25d366; color: white;" title="Compartir en WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>
        <button class="btn" style="background: #6b7280; color: white;" onclick="copyToClipboard()" title="Copiar enlace">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>
    </script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>