<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Raza - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Eliminar Raza: <?php echo e($raza['nombre']); ?></h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>" class="btn btn-secondary">↩️ Volver</a>
            </nav>
        </header>

        <main class="main-content">
            <div class="alert alert-warning">
                <h3>⚠️ ¿Está seguro de eliminar esta raza?</h3>
                <p>Esta acción es irreversible. Una vez eliminada, no podrá recuperar la información de esta raza.</p>
            </div>

            <div class="card">
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo $raza['id_raza']; ?></p>
                    <p><strong>Nombre:</strong> <?php echo e($raza['nombre']); ?></p>
                    <p><strong>Total de cabras:</strong> 
                        <span class="badge <?php echo $raza['total_cabras'] > 0 ? 'badge-success' : 'badge-secondary'; ?>">
                            <?php echo $raza['total_cabras']; ?> cabra<?php echo $raza['total_cabras'] != 1 ? 's' : ''; ?>
                        </span>
                    </p>
                </div>
            </div>

            <?php if ($raza['total_cabras'] > 0): ?>
                <div class="alert alert-error">
                    <p>No se puede eliminar esta raza porque tiene cabras asociadas.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>" class="btn btn-primary">Volver a detalles</a>
            <?php else: ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>/delete" class="form">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="id" value="<?php echo $raza['id_raza']; ?>">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">🗑️ Confirmar Eliminación</button>
                        <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>