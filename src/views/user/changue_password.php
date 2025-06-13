<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>Cambiar Contraseña</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">← Volver al Perfil</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
            </nav>
        </header>

        <?php showMessages(); ?>

        <main class="password-change-content">
            <div class="password-form-card">
                <div class="security-notice">
                    <div class="notice-icon">🔐</div>
                    <div class="notice-text">
                        <h3>Seguridad de tu Cuenta</h3>
                        <p>Para cambiar tu contraseña, necesitas confirmar tu contraseña actual por seguridad.</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo BASE_URL; ?>/profile/password" id="passwordForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label for="current_password">Contraseña actual:</label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   autocomplete="current-password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">Nueva contraseña:</label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" 
                                   required
                                   autocomplete="new-password">
                            <small>Mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirmar nueva contraseña:</label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" 
                                   required
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <div class="password-requirements">
                        <h4>Requisitos de la contraseña:</h4>
                        <ul>
                            <li id="length-req" class="requirement">
                                <span class="req-icon">❌</span>
                                Al menos <?php echo PASSWORD_MIN_LENGTH; ?> caracteres
                            </li>
                            <li id="match-req" class="requirement">
                                <span class="req-icon">❌</span>
                                Las contraseñas deben coincidir
                            </li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            🔒 Cambiar Contraseña
                        </button>
                        <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">
                            ❌ Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="security-tips">
                <h4>💡 Consejos de Seguridad</h4>
                <ul>
                    <li>Usa una contraseña única para esta cuenta</li>
                    <li>Incluye letras mayúsculas, minúsculas y números</li>
                    <li>Evita usar información personal obvia</li>
                    <li>No compartas tu contraseña con nadie</li>
                </ul>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            const lengthReq = document.getElementById('length-req');
            const matchReq = document.getElementById('match-req');
            const submitBtn = document.getElementById('submitBtn');

            function checkRequirements() {
                const minLength = <?php echo PASSWORD_MIN_LENGTH; ?>;
                const newPassValue = newPassword.value;
                const confirmPassValue = confirmPassword.value;

                // Check length
                if (newPassValue.length >= minLength) {
                    lengthReq.classList.remove('requirement');
                    lengthReq.querySelector('.req-icon').textContent = '✅';
                } else {
                    lengthReq.classList.add('requirement');
                    lengthReq.querySelector('.req-icon').textContent = '❌';
                        }
        
                        // Check match
                        if (newPassValue && newPassValue === confirmPassValue) {
                            matchReq.classList.remove('requirement');
                            matchReq.querySelector('.req-icon').textContent = '✅';
                        } else {
                            matchReq.classList.add('requirement');
                            matchReq.querySelector('.req-icon').textContent = '❌';
                        }
        
                        // Enable/disable submit button
                        submitBtn.disabled = !(newPassValue.length >= minLength && newPassValue === confirmPassValue);
                    }
        
                    newPassword.addEventListener('input', checkRequirements);
                    confirmPassword.addEventListener('input', checkRequirements);
        
                    // Initial check
                    checkRequirements();
                });
    </script>