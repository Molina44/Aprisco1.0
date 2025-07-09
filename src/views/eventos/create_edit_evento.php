<?php
// src/views/eventos/create_edit_evento.php
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
    <title><?php echo isset($evento) ? 'Editar Evento' : 'Registrar Evento'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1><?php echo isset($evento) ? '✏️ Editar Evento' : '➕ Registrar Evento'; ?></h1>
        </header>
        <main class="main-content">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach;
                        unset($_SESSION['errors']); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo isset($evento) ? BASE_URL . '/eventos/' . $evento['id_evento'] . '/edit' : BASE_URL . '/eventos/' . $id_cabra . '/create'; ?>" class="cabra-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="id_cabra" value="<?php echo $id_cabra; ?>">

                <div class="form-section">
                    <h3>📅 Detalles del Evento Reproductivo</h3>

                    <div class="form-group">
                        <label for="fecha_evento">Fecha del Evento *</label>
                        <input type="date" name="fecha_evento" id="fecha_evento" required value="<?php echo $evento['fecha_evento'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="tipo_evento">Tipo de Evento *</label>
                        <select name="tipo_evento" id="tipo_evento" required>
                            <option value="">Seleccionar tipo</option>
                            <?php foreach (
                                [
                                    'CELO',
                                    'MONTA',
                                    'INSEMINACION',
                                    'DIAGNOSTICO_GESTACION',
                                    'GESTANTE',
                                    'ABORTO',
                                    'SECADO',
                                    'VACIA'
                                ] as $tipo
                            ): ?>
                                <option value="<?php echo $tipo; ?>" <?php echo (isset($evento) && $evento['tipo_evento'] === $tipo) ? 'selected' : ''; ?>><?php echo ucfirst(strtolower(str_replace('_', ' ', $tipo))); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_semental">Semental</label>
                        <select name="id_semental" id="id_semental">
                            <option value="">Seleccionar semental</option>
                            <?php foreach ($sementales as $s): ?>
                                <option value="<?php echo $s['id_cabra']; ?>" <?php echo (isset($evento) && $evento['id_semental'] == $s['id_cabra']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3"><?php echo $evento['observaciones'] ?? ''; ?></textarea>
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