<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticia no encontrada - Portal de Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .error-content {
            text-align: center;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 0;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        .btn-home {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: white;
            color: #667eea;
        }
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        .floating-icon {
            position: absolute;
            color: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="floating-icons">
            <i class="fas fa-newspaper floating-icon" style="top: 10%; left: 10%; font-size: 3rem; animation-delay: 0s;"></i>
            <i class="fas fa-search floating-icon" style="top: 20%; right: 15%; font-size: 2rem; animation-delay: 1s;"></i>
            <i class="fas fa-file-alt floating-icon" style="bottom: 30%; left: 20%; font-size: 2.5rem; animation-delay: 2s;"></i>
            <i class="fas fa-exclamation-triangle floating-icon" style="bottom: 15%; right: 25%; font-size: 2rem; animation-delay: 3s;"></i>
            <i class="fas fa-home floating-icon" style="top: 40%; left: 5%; font-size: 1.5rem; animation-delay: 4s;"></i>
            <i class="fas fa-arrow-left floating-icon" style="top: 60%; right: 10%; font-size: 2rem; animation-delay: 5s;"></i>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="error-content">
                        <div class="error-code">404</div>
                        <h1 class="error-message">Noticia no encontrada</h1>
                        <p class="lead mb-4">
                            Lo sentimos, la noticia que buscas no existe o ha sido eliminada.
                        </p>
                        
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                            <a href="<?= BASE_URL ?>news" class="btn-home">
                                <i class="fas fa-newspaper"></i> Ver todas las noticias
                            </a>
                            <a href="<?= BASE_URL ?>" class="btn-home">
                                <i class="fas fa-home"></i> Ir al inicio
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <p class="mb-2">¿Necesitas ayuda? Prueba con:</p>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-search"></i> Buscar noticias
                                </span>
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-clock"></i> Noticias recientes
                                </span>
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-star"></i> Noticias destacadas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>