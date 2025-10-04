<?php
// Generar token CSRF para el formulario
require_once __DIR__ . '/../../seguridad.php';

$title = 'Login - ' . SITE_NAME;
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-box-arrow-in-right" style="font-size: 3rem; color: #667eea;"></i>
                        <h2 class="h4 mt-3">Login</h2>
                        <p class="text-muted">Accede a tu cuenta para gestionar tu negocio</p>
                    
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                                <form method="POST" class="needs-validation" novalidate>
                <!-- Token CSRF para seguridad -->
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Contraseña
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-0">¿No tienes cuenta?</p>
                        <a href="<?= BASE_URL ?>register" class="btn btn-outline-primary">
                            <i class="bi bi-person-plus"></i> Registrarse
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>" class="text-muted">
                            <i class="bi bi-arrow-left"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sin include manual de main.php -->