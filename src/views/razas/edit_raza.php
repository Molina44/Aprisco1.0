<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($raza) ? "Editar Raza" : "Nueva Raza" ?> - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    
    <div class="container">
        <header class="dashboard-header">
            <h1>🐏 <?= isset($raza) ? "Editar: " . htmlspecialchars($raza['nombre']) : "Nueva Raza" ?></h1>
            <a href="<?= BASE_URL ?>/razas" class="btn btn-secondary">← Volver</a>
        </header>

        <main class="main-content">
            <!-- Mensajes de error -->
            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']) ?>
            <?php endif; ?>

            <form method="POST" action="<?= isset($raza) ? BASE_URL . '/razas/' . $raza['id_raza'] . '/edit' : BASE_URL . '/razas/create' ?>" class="form-container">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre de la Raza *</label>
                    <input type="text" id="nombre" name="nombre" required
                           value="<?= htmlspecialchars($_SESSION['form_data']['nombre'] ?? $raza['nombre'] ?? '') ?>"
                           placeholder="Ej: Saanen, Alpina, etc.">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?= isset($raza) ? "💾 Guardar Cambios" : "➕ Crear Raza" ?>
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>