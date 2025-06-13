<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>Editar Perfil</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">← Volver al Perfil</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
            </nav>
        </header>

        <?php showMessages(); ?>

        <main class="edit-profile-content">
            <div class="edit-form-card">
                <form method="POST" action="<?php echo BASE_URL; ?>/profile/edit">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-section">
                        <h3>Información Personal</h3>
                        
                        <div class="form-group">
                            <label for="nombre">Nombre completo:</label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?php echo getFormValue('nombre', $user->nombre); ?>" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo getFormValue('email', $user->email); ?>" 
                                   required>
                            <small>El email se usa para iniciar sesión</small>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="<?php echo getFormValue('telefono', $user->telefono); ?>"
                                   placeholder="Opcional">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Información de la Cuenta</h3>
                        <div class="info-display">
                            <p><strong>Fecha de registro:</strong> <?php echo date('d/m/Y H:i', strtotime($user->fecha_registro)); ?></p>
                            <p><strong>Estado:</strong> 
                                <span class="status <?php echo $user->activo ? 'active' : 'inactive'; ?>">
                                    <?php echo $user->activo ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            💾 Guardar Cambios
                        </button>
                        <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">
                            ❌ Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="additional-actions">
                <div class="action-card">
                    <h4>Cambiar Contraseña</h4>
                    <p>¿Necesitas actualizar tu contraseña? Puedes hacerlo de forma segura.</p>
                    <a href="<?php echo BASE_URL; ?>/profile/password" class="btn btn-secondary">
                        🔒 Cambiar Contraseña
                    </a>
                </div>
            </div>
        </main>
    </div>
    
    <?php clearFormData(); ?>
</body>
</html>