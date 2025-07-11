<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cabras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Gestión de Cabras</h1>  
               <!-- Barra de acciones -->

        </header>

        <main class="main-content">
            <!-- Mensajes de éxito o error -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

       

            <!-- Estadísticas rápidas -->
            <div class="stats-summary">
                <div class="stat-card">
                    <h3><?php echo isset($total) ? $total : 0; ?></h3>
                    <p>Total Cabras</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($currentPage) ? $currentPage : 1; ?></h3>
                    <p>Página Actual</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo isset($totalPages) ? $totalPages : 1; ?></h3>
                    <p>Total Páginas</p>
                </div>
            </div>

            <!-- Lista de cabras -->
            <div class="cabras-grid">
                <?php if (!empty($cabras) && is_array($cabras)): ?>
                    <?php foreach ($cabras as $cabra): ?>
                        <div class="cabra-card">
                            <div class="cabra-photo">
                                <?php if (!empty($cabra['foto'])): ?>
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($cabra['foto']); ?>"
                                        alt="<?php echo e($cabra['nombre']); ?>" class="cabra-image">
                                <?php else: ?>
                                    <div class="no-photo">🐐</div>
                                <?php endif; ?>
                            </div>

                            <div class="cabra-info">
                                <h3><?php echo e($cabra['nombre']); ?></h3>
                                <p class="cabra-details">
                                    <span class="sex-badge <?php echo strtolower($cabra['sexo']); ?>">
                                        <?php echo $cabra['sexo'] === 'MACHO' ? '♂' : '♀'; ?> <?php echo e($cabra['sexo']); ?>
                                    </span>
                                </p>

                                <?php if (!empty($cabra['raza_nombre'])): ?>
                                    <p><strong>Raza:</strong> <?php echo e($cabra['raza_nombre']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['color'])): ?>
                                    <p><strong>Color:</strong> <?php echo e($cabra['color']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['fecha_nacimiento'])): ?>
                                    <p><strong>Nacimiento:</strong> <?php echo date('d/m/Y', strtotime($cabra['fecha_nacimiento'])); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($cabra['propietario_nombre'])): ?>
                                    <p><strong>Propietario:</strong> <?php echo e($cabra['propietario_nombre']); ?></p>
                                <?php endif; ?>

                                <p class="status-badge <?php echo strtolower($cabra['estado']); ?>">
                                    <?php echo e($cabra['estado']); ?>
                                </p>
                            </div>

                            <div class="cabra-actions">
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>"
                                    class="btn btn-sm btn-info">Ver</a>
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit"
                                    class="btn btn-sm btn-warning"> Editar</a>

                                <!-- Formulario inline para eliminación con confirmación JS -->
                                <form method="POST" action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('¿Estás seguro de eliminar la cabra <?php echo e($cabra['nombre']); ?>? Esta acción la marcará como INACTIVA.')">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="id" value="<?php echo $cabra['id_cabra']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>🐐 No hay cabras registradas</h3>
                        <p>Comienza agregando tu primera cabra al sistema.</p>
                        <a href="<?php echo BASE_URL; ?>/cabras/create" class="btn btn-primary">
                            ➕ Registrar Primera Cabra
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $currentPage - 1; ?>"
                            class="btn btn-secondary">« Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="btn btn-primary current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $i; ?>"
                                class="btn btn-secondary"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $currentPage + 1; ?>"
                            class="btn btn-secondary">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>