<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Raza - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Crear Nueva Raza</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/razas" class="btn btn-secondary">↩️ Volver</a>
            </nav>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>/razas/create" class="form">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <div class="form-group">
                    <label for="nombre">Nombre de la raza</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo e($_SESSION['form_data']['nombre'] ?? ''); ?>" 
                           required maxlength="100">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Registrar Raza</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>