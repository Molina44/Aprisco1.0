<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($raza['nombre']) ?> - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    
    <div class="container">
        <header class="dashboard-header">
            <h1>🐏 <?= htmlspecialchars($raza['nombre']) ?></h1>
            <nav>
                <a href="<?= BASE_URL ?>/razas" class="btn btn-secondary">← Volver</a>
                <a href="<?= BASE_URL ?>/razas/<?= $raza['id_raza'] ?>/edit" class="btn btn-warning">✏️ Editar</a>
            </nav>
        </header>

<main class="main-content">
    <div class="raza-detail">
        <div class="detail-item">
            <strong>ID:</strong> <?= $raza['id_raza'] ?>
        </div>

        <!-- Línea removida para evitar error de clave indefinida -->
        <!-- <div class="detail-item">
            <strong>Total de Cabras:</strong> 
            <span class="badge"><?= $raza['total_cabras'] ?></span>
        </div> -->

        <div class="detail-item">
            <strong>Nombre:</strong> <?= htmlspecialchars($raza['nombre']) ?>
        </div>
    </div>

    <div class="form-actions">
        <form method="POST" action="<?= BASE_URL ?>/razas/<?= $raza['id_raza'] ?>/delete"
              onsubmit="return confirm('¿Eliminar esta raza permanentemente?')">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <button type="submit" class="btn btn-danger">🗑️ Eliminar Raza</button>
        </form>
    </div>
</main>

    </div>

    <style>
        .raza-detail {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            font-size: 1.1em;
        }
    </style>
</body>
</html>