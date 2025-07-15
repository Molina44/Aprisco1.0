<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Crear Cuenta</h2>
            
            <?php showMessages(); ?>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/register">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="<?php echo getFormValue('nombre'); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo getFormValue('email'); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono (opcional):</label>
                    <input type="tel" 
                           id="telefono" 
                           name="telefono" 
                           value="<?php echo getFormValue('telefono'); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" 
                           required>
                    <small>Mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres</small>
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
            
                
            </form>
            <button type="submit"><a href="<?php echo BASE_URL; ?>/dashboard"  class="btn btn-primary">volver</a></button>
            
        </div>
    </div>
    
    <?php clearFormData(); ?>
</body>
</html>