<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? SITE_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        
        .business-card {
            transition: transform 0.3s ease;
        }
        
        .business-card:hover {
            transform: translateY(-5px);
        }
        
        .news-card {
            transition: transform 0.3s ease;
        }
        
        .news-card:hover {
            transform: translateY(-3px);
        }
        
        .section-card {
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
            color: white;
            transition: transform 0.3s ease;
        }
        
        .section-card:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .footer {
            background-color: #2c3e50;
            color: white;
            margin-top: 50px;
        }
        
        .business-logo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="bi bi-newspaper"></i> <?= SITE_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>news">
                            <i class="bi bi-newspaper"></i> Noticias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>section">
                            <i class="bi bi-grid"></i> Secciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>business">
                            <i class="bi bi-shop"></i> Negocios
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <!-- Buscador -->
                    <form class="d-flex me-3" action="<?= BASE_URL ?>search" method="GET">
                        <input class="form-control form-control-sm me-1" type="search" name="q" 
                               placeholder="Buscar..." value="<?= $_GET['q'] ?? '' ?>">
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="dropdown">
                            <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> <?= getCurrentUser()['full_name'] ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>dashboard">
                                    <i class="bi bi-speedometer2"></i> Panel
                                </a></li>
                                <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>admin">
                                    <i class="bi bi-gear"></i> Administración
                                </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>logout">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="btn-group">
                            <a href="<?= BASE_URL ?>login" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                            <a href="<?= BASE_URL ?>register" class="btn btn-light btn-sm">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php elseif (isset($viewFile) && is_string($viewFile)): ?>
            <?= $viewFile ?>
        <?php elseif (isset($viewFile) && file_exists($viewFile)): ?>
            <?php include $viewFile; ?>
        <?php else: ?>
            <div class="container py-5">
                <div class="alert alert-warning">Vista no encontrada</div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-newspaper"></i> <?= SITE_NAME ?></h5>
                    <p>Portal de noticias y directorio de negocios locales.</p>
                </div>
                <div class="col-md-3">
                    <h6>Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>" class="text-light">Inicio</a></li>
                        <li><a href="<?= BASE_URL ?>news" class="text-light">Noticias</a></li>
                        <li><a href="<?= BASE_URL ?>section" class="text-light">Secciones</a></li>
                        <li><a href="<?= BASE_URL ?>business" class="text-light">Negocios</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Cuenta</h6>
                    <ul class="list-unstyled">
                        <?php if (isLoggedIn()): ?>
                            <li><a href="<?= BASE_URL ?>dashboard" class="text-light">Mi Panel</a></li>
                            <li><a href="<?= BASE_URL ?>logout" class="text-light">Cerrar Sesión</a></li>
                        <?php else: ?>
                            <li><a href="<?= BASE_URL ?>login" class="text-light">Login</a></li>
                            <li><a href="<?= BASE_URL ?>register" class="text-light">Registrarse</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>