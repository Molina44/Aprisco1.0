<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Razas - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    
    <div class="container">
        <header class="dashboard-header">
            <h1>🐏 Gestión de Razas</h1>
        
        </header>

        <main class="main-content">
            <!-- Mensajes -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']) ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']) ?>
            <?php endif; ?>

            <!-- Listado -->
            <div class="razas-grid">
                <?php if (!empty($razas) && is_array($razas)): ?>
                    <?php foreach ($razas as $raza): ?>
                        <div class="raza-card">
                            <div class="raza-header">
                                <h3><?= htmlspecialchars($raza['nombre']) ?></h3>
                               
                            </div>
                            
                            <div class="raza-actions">
                                <a href="<?= BASE_URL ?>/razas/<?= $raza['id_raza'] ?>" class="btn btn-sm btn-info">👁️ Ver</a>
                                <a href="<?= BASE_URL ?>/razas/<?= $raza['id_raza'] ?>/edit" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form method="POST" action="<?= BASE_URL ?>/razas/<?= $raza['id_raza'] ?>/delete" 
                                      onsubmit="return confirm('¿Eliminar esta raza?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>🐏 No hay razas registradas</h3>
                        <p>Comienza agregando tu primera raza</p>
                        <a href="<?= BASE_URL ?>/razas/create" class="btn btn-primary">➕ Registrar Raza</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= BASE_URL ?>/razas?page=<?= $i ?>" 
                           class="<?= $i == $currentPage ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <style>
        .razas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .raza-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .raza-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .raza-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</body>
</html>