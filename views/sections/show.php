<?php
$title = $section['title'] . ' - Negocios';
ob_start();
?>

<style>
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }
    .business-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .business-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .business-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .business-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-approved {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
    }
    .status-pending {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: white;
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
    .back-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }
    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.4);
    }
</style>

<!-- Header de la Sección -->
<div class="section-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb text-white-50">
                        <li class="breadcrumb-item">
                            <a href="index.php" class="text-white-50 text-decoration-none">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="breadcrumb-item text-white" aria-current="page">
                            <?php echo htmlspecialchars($section['title']); ?>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-store"></i> 
                    <?php echo htmlspecialchars($section['title']); ?>
                </h1>
                
                <?php if (!empty($section['description'])): ?>
                <p class="lead mb-0 opacity-90">
                    <?php echo htmlspecialchars($section['description']); ?>
                </p>
                <?php endif; ?>
                
                <div class="mt-3">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-building"></i> 
                        <?php echo count($businesses); ?> negocios encontrados
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php" class="btn btn-light back-btn">
                    <i class="fas fa-arrow-left"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if (!empty($businesses)): ?>
    <!-- Grid de Negocios -->
    <div class="row">
        <?php foreach ($businesses as $business): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card business-card shadow-sm">
                <div class="position-relative">
                    <?php if (!empty($business['logo'])): ?>
                    <img src="<?php echo htmlspecialchars($business['logo']); ?>" 
                         class="business-image" 
                         alt="<?php echo htmlspecialchars($business['name']); ?>">
                    <?php else: ?>
                    <div class="business-image d-flex align-items-center justify-content-center bg-light">
                        <i class="fas fa-store fa-3x text-muted"></i>
                    </div>
                    <?php endif; ?>
                    
                    <span class="business-status status-<?php echo $business['status']; ?>">
                        <?php if ($business['status'] === 'approved'): ?>
                            <i class="fas fa-check"></i> Verificado
                        <?php else: ?>
                            <i class="fas fa-clock"></i> Pendiente
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title fw-bold">
                        <?php echo htmlspecialchars($business['name']); ?>
                    </h5>
                    
                    <p class="card-text text-muted">
                        <?php echo htmlspecialchars(substr($business['description'], 0, 100)); ?>
                        <?php if (strlen($business['description']) > 100): ?>...<?php endif; ?>
                    </p>
                    
                    <div class="d-flex align-items-center text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <small><?php echo htmlspecialchars($business['address']); ?></small>
                    </div>
                    
                    <?php if (!empty($business['phone'])): ?>
                    <div class="d-flex align-items-center text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <small><?php echo htmlspecialchars($business['phone']); ?></small>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($business['email'])): ?>
                    <div class="d-flex align-items-center text-muted mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <small><?php echo htmlspecialchars($business['email']); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> 
                            Desde <?php echo date('Y', strtotime($business['created_at'])); ?>
                        </small>
                        <a href="index.php?controller=business&action=show&id=<?php echo $business['id']; ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Paginación -->
    <?php if (isset($pagination) && $totalPages > 1): ?>
    <nav aria-label="Paginación de negocios" class="mt-5">
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
    <!-- Estado vacío -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-store fa-4x text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">No hay negocios en esta sección</h3>
        <p class="text-muted mb-4">
            Aún no se han registrado negocios en la categoría "<?php echo htmlspecialchars($section['title']); ?>".
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Volver al Inicio
            </a>
            <a href="index.php?controller=business" class="btn btn-outline-primary">
                <i class="fas fa-store"></i> Ver Todos los Negocios
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>