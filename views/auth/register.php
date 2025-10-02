<?php
$title = 'Registrarse - ' . SITE_NAME;
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus" style="font-size: 3rem; color: #667eea;"></i>
                        <h2 class="h4 mt-3">Crear Cuenta</h2>
                        <p class="text-muted">Regístrate como usuario básico</p>
                        <div class="alert alert-warning">
                            <small><i class="bi bi-info-circle"></i> Cuenta básica: Solo lectura. Para crear negocios contacta al administrador.</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong><i class="bi bi-info-circle"></i> ¿Necesitas ayuda?</strong><br>
                            Comunícate al <strong>304-420-4601</strong><br>
                            Por llamada o WhatsApp
                        </div>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                <i class="bi bi-person"></i> Nombre Completo
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-at"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            <div class="form-text">Será usado para iniciar sesión</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Contraseña
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Mínimo 6 caracteres</div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-lock"></i> Confirmar Contraseña
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Crear Cuenta
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-0">¿Ya tienes cuenta?</p>
                        <a href="<?= BASE_URL ?>login" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
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

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>