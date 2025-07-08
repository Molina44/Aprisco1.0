<?php // src/views/layout/base.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <?= $styles ?? '' ?>
</head>
<body>
    <!-- Botón de menú móvil -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar reutilizable -->
   
    
    <div class="main-content">
        <div class="container">
            <?= $content ?>
        </div>
    </div>
    
    <!-- Scripts del sidebar -->
   <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    
    <?= $scripts ?? '' ?>
</body>
</html>