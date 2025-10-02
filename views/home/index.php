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
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h3 fw-bold">
                    <i class="bi bi-grid-3x3-gap"></i> Explora por Secciones
                </h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($sections as $section): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="<?= BASE_URL ?>section/<?= $section['slug'] ?>" 
                   class="text-decoration-none">
                    <div class="card section-card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-shop" style="font-size: 3rem; opacity: 0.8;"></i>
                            <h5 class="card-title mt-3"><?= htmlspecialchars($section['title']) ?></h5>
                            <p class="card-text">
                                <?= truncateText($section['description'], 100) ?>
                            </p>
                            <div class="badge bg-light text-dark">
                                <?= $section['business_count'] ?> negocios
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center">
            <a href="<?= BASE_URL ?>section" class="btn btn-primary">
                <i class="bi bi-grid"></i> Ver Todas las Secciones
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Businesses -->
<?php if (!empty($featuredBusinesses)): ?>
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="h3 fw-bold">
                    <i class="bi bi-shop"></i> Negocios Destacados
                </h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($featuredBusinesses as $business): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card business-card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <?php if ($business['logo_path']): ?>
                            <img src="<?= UPLOAD_URL . $business['logo_path'] ?>" 
                                 class="business-logo me-3" 
                                 alt="<?= htmlspecialchars($business['name']) ?>">
                            <?php else: ?>
                            <div class="bg-secondary business-logo me-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-shop text-white"></i>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">
                                    <a href="<?= BASE_URL ?>business/<?= $business['slug'] ?>" 
                                       class="text-decoration-none">
                                        <?= htmlspecialchars($business['name']) ?>
                                    </a>
                                </h5>
                                <small class="text-muted">
                                    <i class="bi bi-tag"></i> <?= $business['section_title'] ?>
                                </small>
                            </div>
                        </div>
                        
                        <p class="card-text text-muted">
                            <?= truncateText($business['short_description'], 100) ?>
                        </p>
                        
                        <?php if ($business['phone'] || $business['address']): ?>
                        <div class="mt-auto">
                            <?php if ($business['phone']): ?>
                            <small class="text-muted d-block">
                                <i class="bi bi-telephone"></i> <?= htmlspecialchars($business['phone']) ?>
                            </small>
                            <?php endif; ?>
                            
                            <?php if ($business['address']): ?>
                            <small class="text-muted d-block">
                                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($business['address']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center">
            <a href="<?= BASE_URL ?>business" class="btn btn-primary">
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