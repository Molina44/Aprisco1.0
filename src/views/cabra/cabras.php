<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cabras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Gestión de Cabras</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">Perfil</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">Cerrar Sesión</a>
            </nav>
        </header>

        <main class="main-content">
            <!-- Mensajes de éxito o error -->
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

            <!-- Barra de acciones -->
            <div class="actions-bar">
                <div class="actions-left">
                    <a href="<?php echo BASE_URL; ?>/cabras/create" class="btn btn-primary">
                        ➕ Nueva Cabra
                    </a>
                    <a href="<?php echo BASE_URL; ?>/cabras/stats" class="btn btn-info">
                        📊 Estadísticas
                    </a>
                </div>
                <div class="actions-right">
                    <form method="GET" action="<?php echo BASE_URL; ?>/cabras/search" class="search-form">
                        <input type="text" name="term" placeholder="Buscar cabras..." 
                               value="<?php echo isset($_GET['term']) ? e($_GET['term']) : ''; ?>">
                        <button type="submit" class="btn btn-secondary">🔍</button>
                    </form>
                </div>
            </div>

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
                                   class="btn btn-sm btn-info">👁️ Ver</a>
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" 
                                   class="btn btn-sm btn-warning">✏️ Editar</a>
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Estás seguro de eliminar esta cabra?')">🗑️ Eliminar</a>
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

    <style>
        .cabras-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .cabra-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .cabra-card:hover {
            transform: translateY(-5px);
        }

        .cabra-photo {
            height: 200px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cabra-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-photo {
            font-size: 60px;
            color: #ccc;
        }

        .cabra-info {
            padding: 15px;
        }

        .cabra-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .sex-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .sex-badge.macho {
            background: #e3f2fd;
            color: #1976d2;
        }

        .sex-badge.hembra {
            background: #fce4ec;
            color: #c2185b;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .status-badge.activa {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .status-badge.inactiva {
            background: #ffebee;
            color: #d32f2f;
        }

        .cabra-actions {
            padding: 15px;
            background: #f9f9f9;
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 2em;
            color: #4CAF50;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            grid-column: 1 / -1;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }

        .pagination .current {
            background: #4CAF50;
            color: white;
        }
    </style>
</body>
</html>