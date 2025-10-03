<?php 
require_once 'seguridad.php';
verificarEditor();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-floating label {
            padding-left: 1rem;
        }
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 0.5rem;
            margin: 0.5rem;
            border: 2px solid #dee2e6;
        }
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .preview-item {
            position: relative;
            display: inline-block;
        }
        .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
        }
        .existing-image {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }
        .existing-image img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 0.5rem;
            border: 2px solid #28a745;
        }
        .file-drop-zone {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .file-drop-zone:hover, .file-drop-zone.dragover {
            border-color: #0d6efd;
            background: #e3f2fd;
        }
        .char-counter {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .char-counter.warning {
            color: #fd7e14;
        }
        .char-counter.danger {
            color: #dc3545;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?controller=news&action=admin">
                <i class="fas fa-arrow-left"></i> Editar Noticia
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" target="_blank">
                    <i class="fas fa-eye"></i> Ver Noticia
                </a>
                <a class="nav-link" href="index.php?controller=news&action=admin">
                    <i class="fas fa-list"></i> Lista de Noticias
                </a>
                <a class="nav-link" href="index.php?controller=dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="newsForm">
            <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>">
            
            <div class="row">
                <!-- Contenido Principal -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-edit"></i> Información de la Noticia
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Título -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Título de la noticia" required maxlength="200"
                                       value="<?php echo htmlspecialchars($noticia['title']); ?>"
                                       oninput="updateCharCounter('title', 200)">
                                <label for="title">Título de la Noticia *</label>
                                <div class="char-counter" id="title-counter"><?php echo strlen($noticia['title']); ?>/200 caracteres</div>
                            </div>

                            <!-- Extracto -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="excerpt" name="excerpt" 
                                          style="height: 100px" placeholder="Extracto de la noticia"
                                          maxlength="300" oninput="updateCharCounter('excerpt', 300)"><?php echo htmlspecialchars($noticia['excerpt']); ?></textarea>
                                <label for="excerpt">Extracto (resumen breve)</label>
                                <div class="form-text">Breve resumen que aparecerá en la lista de noticias</div>
                                <div class="char-counter" id="excerpt-counter"><?php echo strlen($noticia['excerpt']); ?>/300 caracteres</div>
                            </div>

                            <!-- Contenido -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Contenido de la Noticia *</label>
                                <textarea class="form-control" id="content" name="content" 
                                          rows="15" placeholder="Escribe aquí el contenido completo de la noticia..." 
                                          required><?php echo htmlspecialchars($noticia['content']); ?></textarea>
                                <div class="form-text">Contenido completo de la noticia. Puedes usar saltos de línea para formatear.</div>
                            </div>

                            <!-- Meta Description -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="meta_description" name="meta_description" 
                                          style="height: 80px" placeholder="Meta descripción para SEO"
                                          maxlength="160" oninput="updateCharCounter('meta_description', 160)"><?php echo htmlspecialchars($noticia['meta_description']); ?></textarea>
                                <label for="meta_description">Meta Descripción (SEO)</label>
                                <div class="form-text">Descripción que aparece en buscadores (opcional)</div>
                                <div class="char-counter" id="meta_description-counter"><?php echo strlen($noticia['meta_description']); ?>/160 caracteres</div>
                            </div>
                        </div>
                    </div>

                    <!-- Imágenes -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-images"></i> Imágenes y Multimedia
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Imágenes existentes -->
                            <?php if (!empty($images)): ?>
                            <div class="mb-4">
                                <h6>Imágenes actuales:</h6>
                                <div class="d-flex flex-wrap">
                                    <?php foreach ($images as $image): ?>
                                    <div class="existing-image">
                                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($image['caption'] ?? 'Imagen'); ?>">
                                        <button type="button" class="remove-image" 
                                                onclick="removeExistingImage(<?php echo $image['id']; ?>)"
                                                title="Eliminar imagen">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php if (!empty($image['caption'])): ?>
                                        <div class="text-center mt-1">
                                            <small class="text-muted"><?php echo htmlspecialchars($image['caption']); ?></small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Zona de drop para nuevas imágenes -->
                            <div class="file-drop-zone" id="dropZone">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Arrastra nuevas imágenes aquí o haz clic para seleccionar</h5>
                                <p class="text-muted mb-0">Soporta: JPG, PNG, GIF, WebP (máx. 5MB cada una)</p>
                                <input type="file" id="images" name="images[]" multiple 
                                       accept="image/jpeg,image/png,image/gif,image/webp" 
                                       style="display: none;">
                            </div>
                            
                            <!-- Preview de nuevas imágenes -->
                            <div class="image-preview-container" id="imagePreview"></div>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Las imágenes se agregarán a la galería existente. La primera imagen (actual o nueva) se usará como imagen principal.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Configuración -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cog"></i> Configuración
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Estado -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="draft" <?php echo $noticia['status'] === 'draft' ? 'selected' : ''; ?>>Borrador</option>
                                    <option value="published" <?php echo $noticia['status'] === 'published' ? 'selected' : ''; ?>>Publicada</option>
                                    <option value="archived" <?php echo $noticia['status'] === 'archived' ? 'selected' : ''; ?>>Archivada</option>
                                </select>
                            </div>

                            <!-- Categoría -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="general" <?php echo $noticia['category'] === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="politica" <?php echo $noticia['category'] === 'politica' ? 'selected' : ''; ?>>Política</option>
                                    <option value="economia" <?php echo $noticia['category'] === 'economia' ? 'selected' : ''; ?>>Economía</option>
                                    <option value="deportes" <?php echo $noticia['category'] === 'deportes' ? 'selected' : ''; ?>>Deportes</option>
                                    <option value="tecnologia" <?php echo $noticia['category'] === 'tecnologia' ? 'selected' : ''; ?>>Tecnología</option>
                                    <option value="salud" <?php echo $noticia['category'] === 'salud' ? 'selected' : ''; ?>>Salud</option>
                                    <option value="educacion" <?php echo $noticia['category'] === 'educacion' ? 'selected' : ''; ?>>Educación</option>
                                    <option value="cultura" <?php echo $noticia['category'] === 'cultura' ? 'selected' : ''; ?>>Cultura</option>
                                    <option value="entretenimiento" <?php echo $noticia['category'] === 'entretenimiento' ? 'selected' : ''; ?>>Entretenimiento</option>
                                    <option value="internacional" <?php echo $noticia['category'] === 'internacional' ? 'selected' : ''; ?>>Internacional</option>
                                    <option value="local" <?php echo $noticia['category'] === 'local' ? 'selected' : ''; ?>>Local</option>
                                </select>
                            </div>

                            <!-- Destacada -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                           <?php echo $noticia['featured'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="featured">
                                        <i class="fas fa-star text-warning"></i> Noticia Destacada
                                    </label>
                                </div>
                                <div class="form-text">Las noticias destacadas aparecen en la sección principal</div>
                            </div>

                            <!-- Información de la noticia -->
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-2">
                                    <i class="fas fa-info-circle"></i> Información
                                </h6>
                                <p class="mb-1 small">
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($noticia['author_name'] ?? 'N/A'); ?>
                                </p>
                                <p class="mb-1 small">
                                    <strong>Creada:</strong> <?php echo date('d/m/Y H:i', strtotime($noticia['created_at'])); ?>
                                </p>
                                <?php if ($noticia['updated_at'] && $noticia['updated_at'] !== $noticia['created_at']): ?>
                                <p class="mb-1 small">
                                    <strong>Actualizada:</strong> <?php echo date('d/m/Y H:i', strtotime($noticia['updated_at'])); ?>
                                </p>
                                <?php endif; ?>
                                <p class="mb-1 small">
                                    <strong>Vistas:</strong> <?php echo number_format($noticia['views']); ?>
                                </p>
                                <p class="mb-0 small">
                                    <strong>Slug:</strong> <code><?php echo htmlspecialchars($noticia['slug']); ?></code>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Actualizar Noticia
                                </button>
                                <button type="button" class="btn btn-warning" onclick="saveDraft()">
                                    <i class="fas fa-file-alt"></i> Guardar como Borrador
                                </button>
                                <a href="index.php?controller=news&action=show&slug=<?php echo htmlspecialchars($noticia['slug']); ?>" 
                                   class="btn btn-info" target="_blank">
                                    <i class="fas fa-eye"></i> Ver Noticia
                                </a>
                                <a href="index.php?controller=news&action=admin" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver a Lista
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-eye"></i> Vista Previa
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="titlePreview" class="fw-bold mb-2">
                                <?php echo htmlspecialchars($noticia['title']); ?>
                            </div>
                            <div id="excerptPreview" class="small text-muted">
                                <?php echo htmlspecialchars($noticia['excerpt']); ?>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-secondary" id="categoryPreview"><?php echo htmlspecialchars($noticia['category']); ?></span>
                                <span class="badge bg-warning ms-1" id="featuredPreview" style="<?php echo $noticia['featured'] ? '' : 'display: none;'; ?>">
                                    <i class="fas fa-star"></i> Destacada
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contador de caracteres
        function updateCharCounter(fieldId, maxLength) {
            const field = document.getElementById(fieldId);
            const counter = document.getElementById(fieldId + '-counter');
            const currentLength = field.value.length;
            
            counter.textContent = `${currentLength}/${maxLength} caracteres`;
            
            // Cambiar color según el límite
            if (currentLength > maxLength * 0.9) {
                counter.className = 'char-counter danger';
            } else if (currentLength > maxLength * 0.8) {
                counter.className = 'char-counter warning';
            } else {
                counter.className = 'char-counter';
            }
            
            // Actualizar preview
            updatePreview();
        }

        // Vista previa
        function updatePreview() {
            const title = document.getElementById('title').value || 'Título aparecerá aquí...';
            const excerpt = document.getElementById('excerpt').value || 'Extracto aparecerá aquí...';
            const category = document.getElementById('category').value;
            const featured = document.getElementById('featured').checked;
            
            document.getElementById('titlePreview').textContent = title;
            document.getElementById('excerptPreview').textContent = excerpt;
            document.getElementById('categoryPreview').textContent = category;
            document.getElementById('featuredPreview').style.display = featured ? 'inline' : 'none';
        }

        // Event listeners para preview
        document.getElementById('title').addEventListener('input', updatePreview);
        document.getElementById('excerpt').addEventListener('input', updatePreview);
        document.getElementById('category').addEventListener('change', updatePreview);
        document.getElementById('featured').addEventListener('change', updatePreview);

        // Manejo de archivos (nuevas imágenes)
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');
        let selectedFiles = [];

        // Click en la zona de drop
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // Selección de archivos
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            for (let file of files) {
                if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
                    selectedFiles.push(file);
                    previewImage(file);
                }
            }
            updateFileInput();
        }

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" class="image-preview" alt="Preview">
                    <button type="button" class="remove-image" onclick="removeImage(${selectedFiles.length - 1})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updateImagePreview();
            updateFileInput();
        }

        function updateImagePreview() {
            imagePreview.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                previewImage(file);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
        }

        // Eliminar imagen existente
        function removeExistingImage(imageId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
                // Aquí podrías implementar una llamada AJAX para eliminar la imagen
                // Por ahora solo ocultamos visualmente
                event.target.closest('.existing-image').style.display = 'none';
            }
        }

        // Guardar como borrador
        function saveDraft() {
            document.getElementById('status').value = 'draft';
            document.getElementById('newsForm').submit();
        }

        // Validación del formulario
        document.getElementById('newsForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title || !content) {
                e.preventDefault();
                alert('El título y contenido son obligatorios');
                return false;
            }
            
            if (title.length > 200) {
                e.preventDefault();
                alert('El título no puede exceder 200 caracteres');
                return false;
            }
        });

        // Auto-resize textarea
        const textarea = document.getElementById('content');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Inicializar contadores al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCounter('title', 200);
            updateCharCounter('excerpt', 300);
            updateCharCounter('meta_description', 160);
        });
    </script>
</body>
</html>