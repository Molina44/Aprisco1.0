
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

  <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/img/logo.png" type="image/x-icon">

</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Iniciar Sesión</h2>
            
           <?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo e($error); ?></div>
<?php endif; ?>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/login">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>