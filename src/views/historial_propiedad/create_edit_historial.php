<?php
// src/views/historial_propiedad/create_edit_historial.php
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
    <title><?php echo isset($historial) ? 'Editar Historial' : 'Registrar Historial'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
<div class="container">
    <header class="dashboard-header">
        <h1><?php echo isset($historial) ? '✏️ Editar Historial' : '➕ Registrar Historial'; ?></h1>
    </header>
    <main class="main-content">
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; unset($_SESSION['errors']); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo isset($historial) ? BASE_URL . '/historial/' . $historial['id_historial'] . '/edit' : BASE_URL . '/historial/' . $id_cabra . '/create'; ?>" class="cabra-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_cabra" value="<?php echo $id_cabra; ?>">

            <div class="form-section">
                <h3>👤 Información de Propiedad</h3>

                <div class="form-group">
                    <label for="id_propietario">Propietario *</label>
                    <select name="id_propietario" id="id_propietario" required>
                        <option value="">Seleccionar propietario</option>
                        <?php foreach (getAllPropietarios() as $p): ?>
                            <option value="<?php echo $p['id_propietario']; ?>" <?php echo (isset($historial) && $historial['id_propietario'] == $p['id_propietario']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio *</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required value="<?php echo $historial['fecha_inicio'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $historial['fecha_fin'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="motivo_cambio">Motivo</label>
                    <input type="text" id="motivo_cambio" name="motivo_cambio" value="<?php echo $historial['motivo_cambio'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="precio_transaccion">Precio</label>
                    <input type="number" id="precio_transaccion" name="precio_transaccion" step="0.01" value="<?php echo $historial['precio_transaccion'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar</button>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $id_cabra; ?>" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
