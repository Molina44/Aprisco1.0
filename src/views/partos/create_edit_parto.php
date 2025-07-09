<?php
// src/views/partos/create_edit_parto.php
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
    <title><?php echo isset($parto) ? 'Editar Parto' : 'Registrar Parto'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
<div class="container">
    <header class="dashboard-header">
        <h1><?php echo isset($parto) ? '✏️ Editar Parto' : '➕ Registrar Parto'; ?></h1>
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

        <form method="POST" action="<?php echo isset($parto) ? BASE_URL . '/partos/' . $parto['id_parto'] . '/edit' : BASE_URL . '/partos/' . $id_cabra . '/create'; ?>" class="cabra-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_madre" value="<?php echo $id_cabra; ?>">

            <div class="form-section">
                <h3>👶 Información del Parto</h3>

                <div class="form-group">
                    <label for="id_padre">Padre</label>
                    <select name="id_padre" id="id_padre">
                        <option value="">Seleccionar padre</option>
                        <?php foreach ($padres as $p): ?>
                            <option value="<?php echo $p['id_cabra']; ?>" <?php echo (isset($parto) && $parto['id_padre'] == $p['id_cabra']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_parto">Fecha de Parto *</label>
                        <input type="date" id="fecha_parto" name="fecha_parto" required value="<?php echo $parto['fecha_parto'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="numero_crias">Número de Crías *</label>
                        <input type="number" id="numero_crias" name="numero_crias" required min="1" value="<?php echo $parto['numero_crias'] ?? '1'; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="peso_total_crias">Peso Total de Crías (kg)</label>
                    <input type="number" id="peso_total_crias" name="peso_total_crias" step="0.01" value="<?php echo $parto['peso_total_crias'] ?? ''; ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo_parto">Tipo de Parto *</label>
                        <select id="tipo_parto" name="tipo_parto" required>
                            <option value="">Seleccionar tipo</option>
                            <?php foreach (["SIMPLE", "GEMELAR", "TRIPLE", "MULTIPLE"] as $tipo): ?>
                                <option value="<?php echo $tipo; ?>" <?php echo (isset($parto) && $parto['tipo_parto'] === $tipo) ? 'selected' : ''; ?>><?php echo ucfirst(strtolower($tipo)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dificultad">Dificultad *</label>
                        <select id="dificultad" name="dificultad" required>
                            <option value="">Seleccionar dificultad</option>
                            <?php foreach (["NORMAL", "ASISTIDO", "CESAREO"] as $dif): ?>
                                <option value="<?php echo $dif; ?>" <?php echo (isset($parto) && $parto['dificultad'] === $dif) ? 'selected' : ''; ?>><?php echo ucfirst(strtolower($dif)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3"><?php echo $parto['observaciones'] ?? ''; ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar</button>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $id_cabra; ?>" class="btn btn-secondary" style="text-decoration:none;display:inline-block;text-align:center;line-height:36px;">❌ Cancelar</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
