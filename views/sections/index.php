<?php
$title = 'Todas las Secciones - ' . SITE_NAME;
?>

<!-- Eliminando CSS duplicado - usando clases del layout principal -->
<style>
    .section-card {
        background: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        border: none;
        box-shadow: 0 8px 30px rgba(30, 60, 114, 0.15);
        overflow: hidden;
        position: relative;
        height: 100%;
    }
    .section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, #1e3c72, #2a5298);
        z-index: 1;
    }
    .section-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(30, 60, 114, 0.25);
    }
    .section-card .card-body {
        padding: 2.5rem;
        position: relative;
        z-index: 2;
    }
    .section-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
    }
    .section-stats {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1rem;
        border-radius: 15px;
        margin-top: 1.5rem;
        border: 1px solid #cbd5e0;
    }
    .stats-item {
        text-align: center;
        padding: 0.5rem;
    }
    .stats-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3c72;
        display: block;
    }
    .stats-label {
        font-size: 0.9rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-icon">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">Todas las Secciones</h1>
                <p class="lead mb-4">Explora nuestras categorías de negocios organizadas por sectores</p>
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div class="text-center">
                        <div class="h3 fw-bold"><?= count($sections) ?></div>
                        <div class="small">Secciones Disponibles</div>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold"><?= array_sum(array_column($sections, 'business_count')) ?></div>
                        <div class="small">Negocios Registrados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($sections)): ?>
    <!-- Grid de Secciones -->
    <div class="row">
        <?php foreach ($sections as $index => $section): ?>
        <div class="col-lg-4 col-md-6 mb-5">
            <div class="section-card" style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both;">
                <div class="card-body text-center">
                    <div class="section-icon">
                        <i class="bi bi-shop"></i>
                    </div>
                    
                    <h4 class="card-title fw-bold mb-3" style="color: #1a1a2e;">
                        <?= htmlspecialchars($section['title']) ?>
                    </h4>
                    
                    <p class="card-text text-muted mb-4" style="line-height: 1.6;">
                        <?= htmlspecialchars($section['description']) ?>
                    </p>
                    
                    <div class="section-stats">
                        <div class="row">
                            <div class="col-12 stats-item">
                                <span class="stats-number"><?= $section['business_count'] ?></span>
                                <div class="stats-label"><?= $section['business_count'] == 1 ? 'Negocio' : 'Negocios' ?> Registrados</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="index.php?controller=section&action=show&slug=<?= $section['slug'] ?>" 
                           class="btn btn-lg" 
                           style="background: linear-gradient(45deg, #1e3c72, #2a5298); 
                                  color: white; 
                                  border: none; 
                                  border-radius: 25px; 
                                  padding: 0.8rem 2rem; 
                                  font-weight: 600; 
                                  box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(30, 60, 114, 0.4)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(30, 60, 114, 0.3)'">
                            <i class="bi bi-arrow-right-circle me-2"></i>Ver Negocios
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php else: ?>
    <!-- Estado vacío -->
    <div class="text-center py-5">
        <div style="width: 120px; height: 120px; background: linear-gradient(45deg, #e2e8f0, #cbd5e0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
            <i class="bi bi-grid-3x3-gap" style="font-size: 3rem; color: #64748b;"></i>
        </div>
        <h3 style="color: #1a1a2e;">No hay secciones disponibles</h3>
        <p class="text-muted mb-4">Aún no se han creado secciones de negocios.</p>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">
            <i class="bi bi-house me-2"></i>Volver al Inicio
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-3" style="color: #1a1a2e;">¿Tienes un negocio?</h3>
                <p class="lead text-muted mb-4">Únete a nuestro directorio y haz que más personas descubran tu negocio</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>register" class="btn btn-lg" style="background: linear-gradient(45deg, #10b981, #34d399); color: white; border-radius: 25px; padding: 1rem 2rem; font-weight: 600;">
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

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<!-- Sin include manual de main.php -->