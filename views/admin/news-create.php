<?php
// Verificar permisos de administrador
require_once __DIR__ . '/../../seguridad.php';
verificarAdmin();

$title = 'Crear Noticia - ' . SITE_NAME;
ob_start();
?>
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
                <i class="fas fa-arrow-left"></i> Crear Noticia
            </a>
            <div class="navbar-nav ms-auto">
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
                                       oninput="updateCharCounter('title', 200)">
                                <label for="title">Título de la Noticia *</label>
                                <div class="char-counter" id="title-counter">0/200 caracteres</div>
                            </div>

                            <!-- Extracto -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="excerpt" name="excerpt" 
                                          style="height: 100px" placeholder="Extracto de la noticia"
                                          maxlength="300" oninput="updateCharCounter('excerpt', 300)"></textarea>
                                <label for="excerpt">Extracto (resumen breve)</label>
                                <div class="form-text">Breve resumen que aparecerá en la lista de noticias</div>
                                <div class="char-counter" id="excerpt-counter">0/300 caracteres</div>
                            </div>

                            <!-- Contenido -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Contenido de la Noticia *</label>
                                <textarea class="form-control" id="content" name="content" 
                                          rows="15" placeholder="Escribe aquí el contenido completo de la noticia..." 
                                          required></textarea>
                                <div class="form-text">Contenido completo de la noticia. Puedes usar saltos de línea para formatear.</div>
                            </div>

                            <!-- Meta Description -->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="meta_description" name="meta_description" 
                                          style="height: 80px" placeholder="Meta descripción para SEO"
                                          maxlength="160" oninput="updateCharCounter('meta_description', 160)"></textarea>
                                <label for="meta_description">Meta Descripción (SEO)</label>
                                <div class="form-text">Descripción que aparece en buscadores (opcional)</div>
                                <div class="char-counter" id="meta_description-counter">0/160 caracteres</div>
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
                            <!-- Zona de drop para imágenes -->
                            <div class="file-drop-zone" id="dropZone">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Arrastra imágenes aquí o haz clic para seleccionar</h5>
                                <p class="text-muted mb-0">Soporta: JPG, PNG, GIF, WebP (máx. 5MB cada una)</p>
                                <input type="file" id="images" name="images[]" multiple 
                                       accept="image/jpeg,image/png,image/gif,image/webp" 
                                       style="display: none;">
                            </div>
                            
                            <!-- Preview de imágenes -->
                            <div class="image-preview-container" id="imagePreview"></div>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    La primera imagen se usará como imagen principal de la noticia.
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
                                    <option value="draft">Borrador</option>
                                    <option value="published">Publicada</option>
                                </select>
                            </div>

                            <!-- Categoría -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="general">General</option>
                                    <option value="politica">Política</option>
                                    <option value="economia">Economía</option>
                                    <option value="deportes">Deportes</option>
                                    <option value="tecnologia">Tecnología</option>
                                    <option value="salud">Salud</option>
                                    <option value="educacion">Educación</option>
                                    <option value="cultura">Cultura</option>
                                    <option value="entretenimiento">Entretenimiento</option>
                                    <option value="internacional">Internacional</option>
                                    <option value="local">Local</option>
                                </select>
                            </div>

                            <!-- Destacada -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured">
                                    <label class="form-check-label" for="featured">
                                        <i class="fas fa-star text-warning"></i> Noticia Destacada
                                    </label>
                                </div>
                                <div class="form-text">Las noticias destacadas aparecen en la sección principal</div>
                            </div>

                            <!-- Información del autor -->
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-2">
                                    <i class="fas fa-user"></i> Información del Autor
                                </h6>
                                <p class="mb-0 small">
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?><br>
                                    <strong>Rol:</strong> <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Usuario'; ?><br>
                                    <strong>Fecha:</strong> <?php echo date('d/m/Y H:i'); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Crear Noticia
                                </button>
                                <button type="button" class="btn btn-warning" onclick="saveDraft()">
                                    <i class="fas fa-file-alt"></i> Guardar como Borrador
                                </button>
                                <a href="index.php?controller=news&action=admin" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
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
                            <div id="titlePreview" class="fw-bold mb-2 text-muted">
                                Título aparecerá aquí...
                            </div>
                            <div id="excerptPreview" class="small text-muted">
                                Extracto aparecerá aquí...
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-secondary" id="categoryPreview">general</span>
                                <span class="badge bg-warning ms-1" id="featuredPreview" style="display: none;">
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

        // Manejo de archivos
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
    </script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>