<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($control) ? 'Editar Control Sanitario' : 'Registrar Control Sanitario'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1><?php echo isset($control) ? '✏️ Editar Control Sanitario' : '➕ Nuevo Control Sanitario'; ?></h1>
        </header>

        <main class="main-content">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach;
                        unset($_SESSION['errors']); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data"
                action="<?php echo isset($control) ? BASE_URL . '/controles/' . $control['id_control'] . '/edit' : BASE_URL . '/controles/' . $id_cabra . '/create'; ?>"
                class="cabra-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="id_cabra" value="<?php echo $id_cabra; ?>">

                <div class="form-section">
                    <h3>📋 Datos Generales</h3>

                    <div class="form-group">
                        <label for="fecha_control">Fecha *</label>
                        <input type="date" name="fecha_control" required value="<?php echo $control['fecha_control'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="peso_kg">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso_kg" value="<?php echo $control['peso_kg'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="peso_nacer_kg">Peso al nacer (kg)</label>
                        <input type="number" step="0.01" name="peso_nacer_kg" value="<?php echo $control['peso_nacer_kg'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="condicion_especial">Condición especial</label>
                        <select name="condicion_especial">
                            <option value="">Seleccione</option>
                            <?php foreach (['vacia', 'preñada', 'lactante', 'nacimiento'] as $opt): ?>
                                <option value="<?= $opt ?>" <?= (isset($control) && $control['condicion_especial'] === $opt) ? 'selected' : '' ?>>
                                    <?= ucfirst($opt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="vitaminacion">Vitaminación</label>
                        <input type="text" name="vitaminacion" value="<?php echo $control['vitaminacion'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="purga">Purga</label>
                        <input type="text" name="purga" value="<?php echo $control['purga'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-section">
                    <h3>🩺 Evaluaciones</h3>

                    <div class="form-group">
                        <label for="fama_hoja">Fama hoja (1-5)</label>
                        <input type="number" name="fama_hoja" min="1" max="5" value="<?php echo $control['fama_hoja'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="orejas">Orejas</label>
                        <select name="orejas">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['orejas'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['orejas'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mucosas">Mucosas</label>
                        <select name="mucosas">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['mucosas'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['mucosas'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="famacha">Famacha (1-5)</label>
                        <select name="famacha">
                            <option value="">Seleccione</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($control) && $control['famacha'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="c_corporal">Condición Corporal (1-5)</label>
                        <select name="c_corporal">
                            <option value="">Seleccione</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($control) && $control['c_corporal'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="drack_score">Drack Score (1-5)</label>
                        <select name="drack_score">
                            <option value="">Seleccione</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($control) && $control['drack_score'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="genitales">Genitales</label>
                        <select name="genitales">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['genitales'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['genitales'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ubre">Estado Ubre</label>
                        <select name="ubre">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['ubre'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['ubre'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="foto_ubre">Foto de la Ubre</label>
                        <input type="file" name="foto_ubre" accept="image/*">
                        <?php if (!empty($control['foto_ubre'])): ?>
                            <p>Actual: <img src="<?php echo BASE_URL . '/uploads/' . $control['foto_ubre']; ?>" height="80"></p>
                        <?php endif; ?>
                    </div>


                </div>

                <div class="form-section">
                    <h3>🦷 Dentadura</h3>
                    <div class="form-group">
                        <label for="sin_muda">¿Sin muda?</label>
                        <select name="sin_muda">
                            <option value="">Seleccione</option>
                            <option value="si" <?= (isset($control) && $control['sin_muda'] === 'si') ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= (isset($control) && $control['sin_muda'] === 'no') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                    <?php foreach (['pinzas', 'primeros_medios', 'segundos_medios', 'extremos'] as $diente): ?>
                        <div class="form-group">
                            <label for="<?= $diente ?>"><?= ucfirst(str_replace('_', ' ', $diente)) ?></label>
                            <select name="<?= $diente ?>">
                                <option value="">Seleccione</option>
                                <option value="1" <?= (isset($control) && $control[$diente] === '1') ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= (isset($control) && $control[$diente] === '2') ? 'selected' : '' ?>>2</option>
                            </select>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label for="desgaste">Desgaste</label>
                        <select name="desgaste">
                            <option value="">Seleccione</option>
                            <option value="si" <?= (isset($control) && $control['desgaste'] === 'si') ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= (isset($control) && $control['desgaste'] === 'no') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="perdidas_dentales">Pérdidas dentales</label>
                        <select name="perdidas_dentales">
                            <option value="">Seleccione</option>
                            <option value="si" <?= (isset($control) && $control['perdidas_dentales'] === 'si') ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= (isset($control) && $control['perdidas_dentales'] === 'no') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🐾 Extremidades</h3>

                    <div class="form-group">
                        <label for="cascos">Cascos</label>
                        <select name="cascos">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['cascos'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['cascos'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="e_interdigital">Espacio Interdigital</label>
                        <select name="e_interdigital">
                            <option value="">Seleccione</option>
                            <option value="normal" <?= (isset($control) && $control['e_interdigital'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="anormal" <?= (isset($control) && $control['e_interdigital'] === 'anormal') ? 'selected' : '' ?>>Anormal</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" rows="3"><?php echo $control['observaciones'] ?? ''; ?></textarea>
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