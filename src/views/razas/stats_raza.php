<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Razas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>📊 Estadísticas de Razas</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/razas" class="btn btn-secondary">↩️ Volver</a>
            </nav>
        </header>

        <main class="main-content">
            <div class="stats-section">
                <h2>Resumen</h2>
                <div class="stats-card">
                    <h3>Total de razas registradas</h3>
                    <p class="stat-value"><?php echo $stats['total_razas']; ?></p>
                </div>

                <h2>Razas más populares</h2>
                <div class="stats-list">
                    <?php if (!empty($stats['populares'])): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Raza</th>
                                    <th>Total de Cabras</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['populares'] as $raza): ?>
                                    <tr>
                                        <td><?php echo e($raza['nombre']); ?></td>
                                        <td><?php echo $raza['total_cabras']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay datos de popularidad disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>