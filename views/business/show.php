<?php
// Función para obtener el embed de video
function getVideoEmbed($video) {
    if ($video['video_type'] === 'youtube') {
        $videoId = '';
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video['video_url'], $matches)) {
            $videoId = $matches[1];
        }
        return "<iframe width='100%' height='315' src='https://www.youtube.com/embed/{$videoId}' frameborder='0' allowfullscreen></iframe>";
    } elseif ($video['video_type'] === 'vimeo') {
        $videoId = '';
        if (preg_match('/vimeo\.com\/(\d+)/', $video['video_url'], $matches)) {
            $videoId = $matches[1];
        }
        return "<iframe width='100%' height='315' src='https://player.vimeo.com/video/{$videoId}' frameborder='0' allowfullscreen></iframe>";
    } elseif ($video['video_type'] === 'upload' && $video['video_path']) {
        return "<video width='100%' height='315' controls><source src='" . UPLOAD_URL . $video['video_path'] . "' type='video/mp4'>Tu navegador no soporta video HTML5.</video>";
    }
    return '';
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Información principal del negocio -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-4">
                        <?php if ($business['logo_path']): ?>
                            <img src="<?= UPLOAD_URL . $business['logo_path'] ?>" 
                                 alt="<?= htmlspecialchars($business['name']) ?>" 
                                 class="me-3 rounded" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <h1 class="h2 mb-2"><?= htmlspecialchars($business['name']) ?></h1>
                            <p class="text-muted mb-1">
                                <i class="bi bi-geo-alt"></i> 
                                <?= htmlspecialchars($business['address']) ?>
                            </p>
                            <?php if (isset($business['phone']) && $business['phone']): ?>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-telephone"></i> 
                                    <a href="tel:<?= htmlspecialchars($business['phone']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($business['phone']) ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            <?php if (isset($business['email']) && $business['email']): ?>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-envelope"></i> 
                                    <a href="mailto:<?= htmlspecialchars($business['email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($business['email']) ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            <div class="mt-2">
                                <span class="badge bg-<?= $business['is_open'] == 1 ? 'success' : 'danger' ?>">
                                    <i class="bi bi-<?= $business['is_open'] == 1 ? 'check-circle' : 'x-circle' ?>"></i>
                                    <?= $business['is_open'] == 1 ? 'Abierto' : 'Cerrado' ?>
                                </span>
                                <?php if (isset($business['closed_reason']) && $business['is_open'] == 0 && $business['closed_reason']): ?>
                                    <small class="text-muted ms-2">
                                        <?= htmlspecialchars($business['closed_reason']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($business['description']): ?>
                        <div class="mb-4">
                            <h5>Descripción</h5>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($business['description'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($business['advertisement_text']) && !empty($business['advertisement_text'])): ?>
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-megaphone"></i> Ofertas Especiales
                                </h5>
                            </div>
                            <div class="card-body bg-light">
                                <p class="card-text mb-0 fw-bold text-primary"><?= nl2br(htmlspecialchars($business['advertisement_text'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Galería multimedia -->
            <?php if (!empty($images) || !empty($videos)): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-images"></i> Galería Multimedia
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="multimediaTab" role="tablist">
                            <?php if (!empty($images)): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="images-tab" data-bs-toggle="tab" 
                                            data-bs-target="#images" type="button" role="tab" 
                                            aria-controls="images" aria-selected="true">
                                        <i class="bi bi-image"></i> Imágenes (<?= count($images) ?>)
                                    </button>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($videos)): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= empty($images) ? 'active' : '' ?>" 
                                            id="videos-tab" data-bs-toggle="tab" 
                                            data-bs-target="#videos" type="button" role="tab" 
                                            aria-controls="videos" aria-selected="<?= empty($images) ? 'true' : 'false' ?>">
                                        <i class="bi bi-play-circle"></i> Videos (<?= count($videos) ?>)
                                    </button>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-3" id="multimediaTabContent">
                            <!-- Imágenes -->
                            <?php if (!empty($images)): ?>
                                <div class="tab-pane fade show active" id="images" role="tabpanel" aria-labelledby="images-tab">
                                    <div class="row g-3">
                                        <?php foreach ($images as $image): ?>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <img src="<?= UPLOAD_URL . $image['image_path'] ?>" 
                                                         class="card-img-top" 
                                                         alt="<?= htmlspecialchars($image['caption']) ?>"
                                                         style="height: 200px; object-fit: cover;"
                                                         data-bs-toggle="modal" 
                                                         data-bs-target="#imageModal"
                                                         data-bs-image="<?= UPLOAD_URL . $image['image_path'] ?>"
                                                         data-bs-caption="<?= htmlspecialchars($image['caption']) ?>"
                                                         role="button">
                                                    <?php if ($image['caption']): ?>
                                                        <div class="card-body p-2">
                                                            <p class="card-text small text-muted mb-0">
                                                                <?= htmlspecialchars($image['caption']) ?>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($image['is_featured']): ?>
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <span class="badge bg-primary">
                                                                <i class="bi bi-star-fill"></i> Destacada
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Videos -->
                            <?php if (!empty($videos)): ?>
                                <div class="tab-pane fade <?= empty($images) ? 'show active' : '' ?>" 
                                     id="videos" role="tabpanel" aria-labelledby="videos-tab">
                                    <div class="row g-3">
                                        <?php foreach ($videos as $video): ?>
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-body p-0">
                                                        <?= getVideoEmbed($video) ?>
                                                    </div>
                                                    <?php if ($video['title'] || $video['description']): ?>
                                                        <div class="card-body">
                                                            <?php if ($video['title']): ?>
                                                                <h6 class="card-title mb-1">
                                                                    <?= htmlspecialchars($video['title']) ?>
                                                                </h6>
                                                            <?php endif; ?>
                                                            <?php if ($video['description']): ?>
                                                                <p class="card-text small text-muted">
                                                                    <?= htmlspecialchars($video['description']) ?>
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php if (!empty($relatedBusinesses)): ?>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-shop"></i> Otros Negocios
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($relatedBusinesses as $related): ?>
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($related['logo_path']): ?>
                                    <img src="<?= UPLOAD_URL . $related['logo_path'] ?>" 
                                         alt="<?= htmlspecialchars($related['name']) ?>" 
                                         class="me-3 rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="<?= BASE_URL ?>business/<?= $related['slug'] ?>" 
                                           class="text-decoration-none">
                                            <?= htmlspecialchars($related['name']) ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(substr($related['description'], 0, 80)) ?>...
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Información de contacto -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Información de Contacto
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($business['address']) && $business['address']): ?>
                        <p class="mb-2">
                            <strong><i class="bi bi-geo-alt"></i> Dirección:</strong><br>
                            <?= htmlspecialchars($business['address']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (isset($business['phone']) && $business['phone']): ?>
                        <p class="mb-2">
                            <strong><i class="bi bi-telephone"></i> Teléfono:</strong><br>
                            <a href="tel:<?= htmlspecialchars($business['phone']) ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($business['phone']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (isset($business['email']) && $business['email']): ?>
                        <p class="mb-2">
                            <strong><i class="bi bi-envelope"></i> Email:</strong><br>
                            <a href="mailto:<?= htmlspecialchars($business['email']) ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($business['email']) ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <div class="mt-3">
                        <strong>Estado:</strong>
                        <span class="badge bg-<?= $business['is_open'] == 1 ? 'success' : 'danger' ?> ms-2">
                            <?= $business['is_open'] == 1 ? 'Abierto' : 'Cerrado' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver imágenes -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="">
                <p id="modalCaption" class="mt-3 text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script>
// Script para el modal de imágenes
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-bs-image');
            const imageCaption = button.getAttribute('data-bs-caption');
            
            const modalImage = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');
            
            modalImage.src = imageSrc;
            modalCaption.textContent = imageCaption || '';
        });
    }
});
</script>