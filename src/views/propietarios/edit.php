<?php
// Generar token CSRF solo si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($propietario) ? "Editar Propietario" : "Registrar Nuevo Propietario" ?> - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>

<div class="container">
    <header class="dashboard-header">
        <h1>👥 <?= isset($propietario) ? "Editar: " . htmlspecialchars($propietario['nombre']) : "Registrar Nuevo Propietario" ?></h1>
        <a href="<?= BASE_URL ?>/propietarios" class="btn btn-secondary">← Volver</a>
    </header>

    <main class="main-content">
        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <h4>Por favor, corrige los siguientes errores:</h4>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= isset($propietario) ? BASE_URL . '/propietarios/' . $propietario['id_propietario'] . '/edit' : BASE_URL . '/propietarios/create' ?>" class="form-container">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" required
                       value="<?= htmlspecialchars($_SESSION['form_data']['nombre'] ?? $propietario['nombre'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="identificacion">Identificación</label>
                <input type="text" id="identificacion" name="identificacion"
                       value="<?= htmlspecialchars($_SESSION['form_data']['identificacion'] ?? $propietario['identificacion'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion"
                       value="<?= htmlspecialchars($_SESSION['form_data']['direccion'] ?? $propietario['direccion'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono"
                       value="<?= htmlspecialchars($_SESSION['form_data']['telefono'] ?? $propietario['telefono'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? $propietario['email'] ?? '') ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?= isset($propietario) ? "💾 Guardar Cambios" : "➕ Registrar Propietario" ?>
                </button>
                <a href="<?= BASE_URL ?>/propietarios" class="btn btn-secondary">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </main>
</div>

<?php unset($_SESSION['form_data']); ?>
</body>
</html>
