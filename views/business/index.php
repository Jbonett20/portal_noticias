<?php
$title = 'Directorio de Negocios - ' . SITE_NAME;
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">Directorio de Negocios</h1>
                <p class="lead mb-4">Descubre los mejores negocios de tu comunidad</p>
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div class="text-center">
                        <div class="h3 fw-bold"><?= count($businesses) ?></div>
                        <div class="small">Negocios Activos</div>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold"><?= count($sections) ?></div>
                        <div class="small">Categorías</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- Filtros -->
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h6 class="fw-bold mb-3 mb-md-0" style="color: #1a1a2e;">
                    <i class="bi bi-funnel me-2"></i>Filtrar por Categoría:
                </h6>
            </div>
            <div class="col-md-9">
                <div class="d-flex flex-wrap">
                    <a href="<?= BASE_URL ?>business" class="btn filter-btn <?= !$currentSection ? 'active' : '' ?>">
                        <i class="bi bi-grid-3x3-gap me-1"></i>Todas
                    </a>
                    <?php foreach ($sections as $section): ?>
                    <a href="<?= BASE_URL ?>business?section=<?= $section['id'] ?>" 
                       class="btn filter-btn <?= $currentSection == $section['id'] ? 'active' : '' ?>">
                        <i class="bi bi-tag me-1"></i><?= htmlspecialchars($section['title']) ?>
                        <span class="badge ms-1" style="background: rgba(255,255,255,0.3);"><?= $section['business_count'] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($businesses)): ?>
    <!-- Grid de Negocios -->
    <div class="row">
        <?php foreach ($businesses as $index => $business): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="business-card" style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both;">
                <div class="card-body p-4">
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
                            <h5 class="card-title mb-2">
                                <a href="index.php?controller=business&action=show&id=<?= $business['id'] ?>" 
                                   class="text-decoration-none" style="color: #1a1a2e; font-weight: 700;">
                                    <?= htmlspecialchars($business['name']) ?>
                                </a>
                            </h5>
                            <span class="badge" style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; border-radius: 15px; font-size: 0.75rem; padding: 0.3rem 0.8rem;">
                                <i class="bi bi-tag"></i> <?= $business['section_title'] ?>
                            </span>
                        </div>
                    </div>
                    
                    <p class="card-text text-muted mb-3" style="line-height: 1.6;">
                        <?= truncateText($business['short_description'], 120) ?>
                    </p>
                    
                    <?php if ($business['phone'] || $business['address']): ?>
                    <div class="contact-info">
                        <?php if ($business['phone']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <div class="contact-icon phone me-2">
                                <i class="bi bi-telephone text-white" style="font-size: 0.7rem;"></i>
                            </div>
                            <small class="text-muted fw-500"><?= htmlspecialchars($business['phone']) ?></small>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($business['address']): ?>
                        <div class="d-flex align-items-center">
                            <div class="contact-icon location me-2">
                                <i class="bi bi-geo-alt text-white" style="font-size: 0.7rem;"></i>
                            </div>
                            <small class="text-muted fw-500"><?= htmlspecialchars(truncateText($business['address'], 50)) ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-3">
                        <a href="index.php?controller=business&action=show&id=<?= $business['id'] ?>" 
                           class="btn btn-sm btn-primary-gradient">
                            <i class="bi bi-arrow-right me-1"></i>Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Paginación -->
    <?php if (isset($pagination) && !empty($pagination)): ?>
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Paginación de negocios">
            <ul class="pagination">
                <?php if (isset($pagination['prev'])): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pagination['prev'] ?>">
                        <i class="bi bi-chevron-left"></i> Anterior
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if (isset($pagination['pages'])): ?>
                    <?php foreach ($pagination['pages'] as $page): ?>
                    <li class="page-item <?= $page['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $page['url'] ?>"><?= $page['number'] ?></a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (isset($pagination['next'])): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pagination['next'] ?>">
                        Siguiente <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <!-- Estado vacío -->
    <div class="text-center py-5">
        <div class="empty-state-icon">
            <i class="bi bi-shop" style="font-size: 3rem; color: #64748b;"></i>
        </div>
        <h3 style="color: #1a1a2e;">No hay negocios disponibles</h3>
        <p class="text-muted mb-4">
            <?php if ($currentSection): ?>
                No se encontraron negocios en esta categoría.
            <?php else: ?>
                Aún no se han registrado negocios en el directorio.
            <?php endif; ?>
        </p>
        <div class="d-flex justify-content-center gap-3">
            <?php if ($currentSection): ?>
            <a href="<?= BASE_URL ?>business" class="btn btn-primary">
                <i class="bi bi-grid-3x3-gap me-2"></i>Ver Todos los Negocios
            </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>" class="btn btn-outline-primary">
                <i class="bi bi-house me-2"></i>Volver al Inicio
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Call to Action -->
<section class="py-5 cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-3" style="color: #1a1a2e;">¿Quieres registrar tu negocio?</h3>
                <p class="lead text-muted mb-4">Únete a nuestro directorio y llega a más clientes</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>register" class="btn btn-lg btn-success-gradient">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Negocio
                    </a>
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-primary btn-lg" style="border-radius: 25px; padding: 1rem 2rem; font-weight: 600;">
                        <i class="bi bi-house me-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sin include manual de main.php -->