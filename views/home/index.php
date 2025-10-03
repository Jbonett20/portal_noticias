<?php
$title = 'Inicio - ' . SITE_NAME;
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Bienvenido a <?= SITE_NAME ?>
                </h1>
                <p class="lead mb-4">
                    Descubre las últimas noticias y los mejores negocios de tu ciudad. 
                    Tu portal de información local.
                </p>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>news" class="btn btn-light btn-lg">
                        <i class="bi bi-newspaper"></i> Ver Noticias
                    </a>
                    <a href="<?= BASE_URL ?>business" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-shop"></i> Explorar Negocios
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-newspaper" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured News -->
<?php if (!empty($featuredNews)): ?>
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h3 fw-bold">
                    <i class="bi bi-star-fill text-warning"></i> Noticias Destacadas
                </h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($featuredNews as $news): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card news-card h-100 shadow-sm">
                    <?php if ($news['featured_image']): ?>
                    <img src="<?= UPLOAD_URL . $news['featured_image'] ?>" 
                         class="card-img-top" style="height: 200px; object-fit: cover;"
                         alt="<?= htmlspecialchars($news['title']) ?>">
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="<?= BASE_URL ?>news/<?= $news['slug'] ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($news['title']) ?>
                            </a>
                        </h5>
                        
                        <p class="card-text text-muted flex-grow-1">
                            <?= truncateText($news['summary'] ?? $news['content'], 120) ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> <?= $news['author_name'] ?? 'Anónimo' ?>
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> <?= formatDate($news['published_at']) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center">
            <a href="<?= BASE_URL ?>news" class="btn btn-primary">
                <i class="bi bi-newspaper"></i> Ver Todas las Noticias
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sections -->
<?php if (!empty($sections)): ?>
<section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="h2 fw-bold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-grid-3x3-gap" style="color: #2a5298;"></i> Explora por Secciones
                </h2>
                <p class="lead text-muted">Descubre negocios organizados por categorías</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($sections as $section): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="index.php?controller=section&action=show&slug=<?= $section['slug'] ?>" 
                   class="text-decoration-none">
                    <div class="card section-card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-shop" style="font-size: 3.5rem;"></i>
                            <h5 class="card-title mt-3"><?= htmlspecialchars($section['title']) ?></h5>
                            <p class="card-text">
                                <?= truncateText($section['description'], 100) ?>
                            </p>
                            <div class="badge">
                                <?= $section['business_count'] ?> negocios
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php?controller=section&action=index" 
               class="btn btn-lg" 
               style="background: linear-gradient(45deg, #1e3c72, #2a5298); 
                      color: white; 
                      border: none; 
                      border-radius: 30px; 
                      padding: 1rem 2.5rem; 
                      font-weight: 600; 
                      box-shadow: 0 6px 20px rgba(30, 60, 114, 0.3);
                      transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(30, 60, 114, 0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(30, 60, 114, 0.3)'">
                <i class="bi bi-grid"></i> Ver Todas las Secciones
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Businesses -->
<?php if (!empty($featuredBusinesses)): ?>
<section class="py-5" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="h2 fw-bold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-shop" style="color: #2a5298;"></i> Negocios Destacados
                </h2>
                <p class="lead text-muted">Descubre los mejores negocios de nuestra comunidad</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($featuredBusinesses as $business): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card business-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <?php if ($business['logo_path']): ?>
                            <img src="<?= UPLOAD_URL . $business['logo_path'] ?>" 
                                 class="business-logo me-3" 
                                 alt="<?= htmlspecialchars($business['name']) ?>">
                            <?php else: ?>
                            <div class="bg-secondary business-logo me-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-shop text-white fs-4"></i>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">
                                    <a href="index.php?controller=business&action=show&id=<?= $business['id'] ?>" 
                                       class="text-decoration-none">
                                        <?= htmlspecialchars($business['name']) ?>
                                    </a>
                                </h5>
                                <span class="badge" style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; border-radius: 15px; font-size: 0.75rem; padding: 0.3rem 0.8rem;">
                                    <i class="bi bi-tag"></i> <?= $business['section_title'] ?>
                                </span>
                            </div>
                        </div>
                        
                        <p class="card-text" style="color: #64748b; line-height: 1.6;">
                            <?= truncateText($business['short_description'], 120) ?>
                        </p>
                        
                        <?php if ($business['phone'] || $business['address']): ?>
                        <div class="mt-auto pt-3" style="border-top: 1px solid #e2e8f0;">
                            <?php if ($business['phone']): ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-telephone-fill me-2" style="color: #2a5298;"></i>
                                <small class="text-muted"><?= htmlspecialchars($business['phone']) ?></small>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($business['address']): ?>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill me-2" style="color: #2a5298;"></i>
                                <small class="text-muted"><?= htmlspecialchars(truncateText($business['address'], 50)) ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php?controller=business" 
               class="btn btn-lg" 
               style="background: linear-gradient(45deg, #1e3c72, #2a5298); 
                      color: white; 
                      border: none; 
                      border-radius: 30px; 
                      padding: 1rem 2.5rem; 
                      font-weight: 600; 
                      box-shadow: 0 6px 20px rgba(30, 60, 114, 0.3);
                      transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(30, 60, 114, 0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(30, 60, 114, 0.3)'">
                <i class="bi bi-shop"></i> Ver Todos los Negocios
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
?>