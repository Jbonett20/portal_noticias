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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1a1a2e 100%);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-icon {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(10px);
        }
        
        .hero-icon i {
            font-size: 4rem;
        }
        
        .filter-section {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(30, 60, 114, 0.08);
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }
        
        .filter-btn {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #cbd5e0;
            color: #64748b;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0.25rem;
            font-weight: 600;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            border-color: #2a5298;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }
        
        .business-logo {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .business-card:hover .business-logo {
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.25);
            border-color: #2a5298;
            transform: scale(1.05);
        }
        
        .business-logo.bg-secondary {
            background: linear-gradient(45deg, #1e3c72, #2a5298) !important;
        }
        
        .business-stats {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 1rem;
            border-radius: 15px;
            margin-top: 1rem;
            border: 1px solid #3b82f6;
        }
        
        .contact-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            border-left: 4px solid #2a5298;
        }
        
        .btn-primary-gradient {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
            color: white;
        }
        
        .btn-success-gradient {
            background: linear-gradient(45deg, #10b981, #34d399);
            color: white;
            border-radius: 25px;
            padding: 1rem 2rem;
            font-weight: 600;
            border: none;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #e2e8f0, #cbd5e0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        
        .contact-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .contact-icon.phone {
            background: linear-gradient(45deg, #10b981, #34d399);
        }
        
        .contact-icon.location {
            background: linear-gradient(45deg, #f59e0b, #fbbf24);
        }
        
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
        
        .business-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(30, 60, 114, 0.12);
            background: white;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            position: relative;
        }
        
        .business-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #1e3c72, #2a5298);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .business-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(30, 60, 114, 0.25);
            border-color: #2a5298;
        }
        
        .business-card:hover::before {
            transform: scaleY(1);
        }
        
        .business-card .card-body {
            padding: 2rem;
        }
        
        .business-card .card-title a {
            color: #1a1a2e;
            font-weight: 700;
            transition: color 0.3s ease;
        }
        
        .business-card .card-title a:hover {
            color: #2a5298;
        }
        
        .news-card {
            transition: transform 0.3s ease;
        }
        
        .news-card:hover {
            transform: translateY(-3px);
        }
        
        .section-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1a1a2e 100%);
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(30, 60, 114, 0.2);
            overflow: hidden;
            position: relative;
        }
        
        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            z-index: 1;
        }
        
        .section-card .card-body {
            position: relative;
            z-index: 2;
            padding: 2.5rem 2rem;
        }
        
        .section-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 50px rgba(30, 60, 114, 0.35);
        }
        
        .section-card i {
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .section-card .card-title {
            color: white;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .section-card .card-text {
            color: rgba(255,255,255,0.9);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .section-card .badge {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
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
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .business-card:hover .business-logo {
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.25);
            border-color: #2a5298;
        }
        
        .business-logo.bg-secondary {
            background: linear-gradient(45deg, #1e3c72, #2a5298) !important;
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