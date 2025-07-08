<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Raza - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Detalles de Raza: <?php echo e($raza['nombre']); ?></h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/razas" class="btn btn-secondary">↩️ Volver</a>
            </nav>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h3>Información de la raza</h3>
                    <p><strong>ID:</strong> <?php echo $raza['id_raza']; ?></p>
                    <p><strong>Nombre:</strong> <?php echo e($raza['nombre']); ?></p>
                    <p><strong>Total de cabras:</strong> 
                        <span class="badge <?php echo $raza['total_cabras'] > 0 ? 'badge-success' : 'badge-secondary'; ?>">
                            <?php echo $raza['total_cabras']; ?> cabra<?php echo $raza['total_cabras'] != 1 ? 's' : ''; ?>
                        </span>
                    </p>
                </div>
                <div class="card-actions">
                    <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>/edit" class="btn btn-primary">✏️ Editar</a>
                    <?php if ($raza['total_cabras'] == 0): ?>
                        <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>/delete" 
                           class="btn btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar esta raza?')">🗑️ Eliminar</a>
                    <?php else: ?>
                        <button class="btn btn-secondary disabled" title="No se puede eliminar (tiene cabras asociadas)">🔒 Eliminar</button>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>