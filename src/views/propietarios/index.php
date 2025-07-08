<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propietarios - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <div class="container">
        <header class="dashboard-header">
            <h1>👥 Gestión de Propietarios</h1>

        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="razas-grid">
                <?php if (!empty($propietarios) && is_array($propietarios)): ?>
                    <?php foreach ($propietarios as $p): ?>
                        <div class="raza-card">
                            <div class="raza-header">
                                <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                            </div>

                            <div class="detail-item"><strong>ID:</strong> <?= $p['id_propietario'] ?></div>
                            <div class="detail-item"><strong>Identificación:</strong> <?= $p['identificacion'] ?></div>
                            <div class="detail-item"><strong>Teléfono:</strong> <?= $p['telefono'] ?></div>
                            <div class="detail-item"><strong>Email:</strong> <?= $p['email'] ?></div>

                            <div class="raza-actions">
                                <a href="<?= BASE_URL ?>/propietarios/<?= $p['id_propietario'] ?>" class="btn btn-sm btn-info">👁️ Ver</a>
                                <a href="<?= BASE_URL ?>/propietarios/<?= $p['id_propietario'] ?>/edit" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form method="POST" action="<?= BASE_URL ?>/propietarios/<?= $p['id_propietario'] ?>/delete" 
                                      onsubmit="return confirm('¿Eliminar este propietario?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>👥 No hay propietarios registrados</h3>
                        <p>Comienza agregando tu primer propietario</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
        .razas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
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

        .raza-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .detail-item {
            font-size: 0.95em;
            margin-bottom: 6px;
        }
    </style>
</body>
</html>
