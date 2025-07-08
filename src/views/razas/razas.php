<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Razas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Gestión de Razas</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/razas/create" class="btn btn-primary">➕ Nueva Raza</a>
                <a href="<?php echo BASE_URL; ?>/razas/stats" class="btn btn-info">📊 Estadísticas</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">🏠 Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">🚪 Salir</a>
            </nav>
        </header>

        <main class="main-content">
            <!-- Mensajes -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Búsqueda -->
            <div class="search-section">
                <form method="GET" action="<?php echo BASE_URL; ?>/razas/search" class="search-form">
                    <input type="text" name="term" placeholder="Buscar razas..." 
                           value="<?php echo isset($searchTerm) ? e($searchTerm) : ''; ?>" required>
                    <button type="submit" class="btn btn-primary">🔍 Buscar</button>
                    <?php if (isset($searchTerm)): ?>
                        <a href="<?php echo BASE_URL; ?>/razas" class="btn btn-secondary">❌ Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Información de resultados -->
            <div class="results-info">
                <?php if (isset($searchTerm)): ?>
                    <p>Resultados de búsqueda para "<strong><?php echo e($searchTerm); ?></strong>": 
                       <?php echo $total; ?> raza<?php echo $total != 1 ? 's' : ''; ?> encontrada<?php echo $total != 1 ? 's' : ''; ?></p>
                <?php else: ?>
                    <p>Total: <?php echo $total; ?> raza<?php echo $total != 1 ? 's' : ''; ?> registrada<?php echo $total != 1 ? 's' : ''; ?></p>
                <?php endif; ?>
            </div>

            <!-- Tabla de razas -->
            <?php if (!empty($razas)): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Total de Cabras</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($razas as $raza): ?>
                                <tr>
                                    <td><?php echo $raza['id_raza']; ?></td>
                                    <td class="nombre-raza">
                                        <strong><?php echo e($raza['nombre']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $raza['total_cabras'] > 0 ? 'badge-success' : 'badge-secondary'; ?>">
                                            <?php echo $raza['total_cabras']; ?> cabra<?php echo $raza['total_cabras'] != 1 ? 's' : ''; ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>" 
                                           class="btn btn-sm btn-info" title="Ver detalles">👁️</a>
                                        <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>/edit" 
                                           class="btn btn-sm btn-primary" title="Editar">✏️</a>
                                        <?php if ($raza['total_cabras'] == 0): ?>
                                            <a href="<?php echo BASE_URL; ?>/razas/<?php echo $raza['id_raza']; ?>/delete" 
                                               class="btn btn-sm btn-danger" title="Eliminar"
                                               onclick="return confirm('¿Está seguro de eliminar esta raza?')">🗑️</a>
                                        <?php else: ?>
                                            <span class="btn btn-sm btn-secondary disabled" title="No se puede eliminar (tiene cabras asociadas)">🔒</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                 
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?php echo BASE_URL; ?>/razas?page=<?php echo $currentPage - 1; ?>" class="btn btn-secondary">← Anterior</a>
                        <?php endif; ?>

                        <span class="page-info">
                            Página <?php echo $currentPage; ?> de <?php echo $totalPages; ?>
                        </span>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?php echo BASE_URL; ?>/razas?page=<?php echo $currentPage + 1; ?>" class="btn btn-secondary">Siguiente →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <h3>🔍 No se encontraron razas</h3>
                    <?php if (isset($searchTerm)): ?>
                        <p>No hay razas que coincidan con "<?php echo e($searchTerm); ?>"</p>
                        <a href="<?php echo BASE_URL; ?>/razas" class="btn btn-primary">Ver todas las razas</a>
                    <?php else: ?>
                        <p>Aún no has registrado ninguna raza</p>
                        <a href="<?php echo BASE_URL; ?>/razas/create" class="btn btn-primary">Registrar primera raza</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
