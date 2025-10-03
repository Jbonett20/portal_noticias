<?php
$title = 'Contacto para Registro - ' . SITE_NAME;
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; backdrop-filter: blur(10px);">
                        <i class="bi bi-person-plus" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="h3 mb-2 fw-bold">Registro de Usuarios</h2>
                    <p class="mb-0 opacity-90">Contáctanos para crear tu cuenta</p>
                </div>
                
                <div class="card-body p-5">
                    <!-- Aviso Principal -->
                    <div class="alert" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; border-radius: 15px; margin-bottom: 2rem;">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width: 50px; height: 50px; background: linear-gradient(45deg, #f59e0b, #fbbf24); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="bi bi-info-circle-fill text-white fs-5"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color: #92400e;">Registro por Contacto</h5>
                        </div>
                        <p class="mb-0" style="color: #92400e; line-height: 1.6;">
                            Para crear tu cuenta de usuario y acceder a todas las funcionalidades del portal, 
                            comunícate con nosotros a través de los siguientes medios:
                        </p>
                    </div>

                    <!-- Opciones de Contacto -->
                    <div class="row g-4 mb-4">
                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <a href="tel:3044204601" class="text-decoration-none">
                                <div class="contact-card h-100 p-4 text-center" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); border-radius: 20px; color: white; transition: all 0.3s ease; cursor: pointer;" 
                                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 35px rgba(16, 185, 129, 0.4)'"
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.3)'">
                                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; backdrop-filter: blur(10px);">
                                        <i class="bi bi-telephone-fill" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Llamar Ahora</h5>
                                    <p class="mb-2" style="font-size: 1.1rem; font-weight: 600;">304-420-4601</p>
                                    <small class="opacity-90">Toca para llamar</small>
                                </div>
                            </a>
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-md-6">
                            <a href="https://wa.me/573044204601?text=Hola%2C%20me%20gustar%C3%ADa%20registrarme%20en%20el%20portal%20de%20noticias" target="_blank" class="text-decoration-none">
                                <div class="contact-card h-100 p-4 text-center" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); border-radius: 20px; color: white; transition: all 0.3s ease; cursor: pointer;"
                                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 35px rgba(37, 211, 102, 0.4)'"
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(37, 211, 102, 0.3)'">
                                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; backdrop-filter: blur(10px);">
                                        <i class="bi bi-whatsapp" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">WhatsApp</h5>
                                    <p class="mb-2" style="font-size: 1.1rem; font-weight: 600;">304-420-4601</p>
                                    <small class="opacity-90">Enviar mensaje</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="alert" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 2px solid #3b82f6; border-radius: 15px;">
                        <div class="d-flex align-items-start">
                            <div style="width: 40px; height: 40px; background: linear-gradient(45deg, #3b82f6, #2563eb); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem; flex-shrink: 0;">
                                <i class="bi bi-clock-fill text-white"></i>
                            </div>
                            <div style="color: #1e40af;">
                                <h6 class="fw-bold mb-2">Horarios de Atención</h6>
                                <ul class="mb-0" style="list-style: none; padding: 0;">
                                    <li class="mb-1">📞 <strong>Llamadas:</strong> Lunes a Viernes 8:00 AM - 6:00 PM</li>
                                    <li>💬 <strong>WhatsApp:</strong> Disponible 24/7</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Navegación -->
                    <div class="text-center mt-4">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="<?= BASE_URL ?>login" class="btn btn-outline-primary" style="border-radius: 25px; padding: 0.8rem 2rem; font-weight: 600;">
                                <i class="bi bi-box-arrow-in-right me-2"></i>¿Ya tienes cuenta? Inicia Sesión
                            </a>
                            
                            <a href="<?= BASE_URL ?>" class="btn" style="background: linear-gradient(45deg, #6b7280, #9ca3af); color: white; border-radius: 25px; padding: 0.8rem 2rem; font-weight: 600;">
                                <i class="bi bi-house me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .contact-card {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border: 2px solid rgba(255,255,255,0.2);
    }
    
    @media (max-width: 768px) {
        .contact-card:hover {
            transform: none !important;
        }
    }
</style>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout/main.php';
?>