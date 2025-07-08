
<?php // src/views/historial_propiedad/create_edit_historial.php ?>
<h3><?= isset($historial) ? '✏️ Editar Historial' : '➕ Nuevo Historial' ?></h3>
<form method="POST" action="<?= isset($historial) ? BASE_URL . '/historial/' . $historial['id_historial'] . '/edit' : BASE_URL . '/historial/' . $id_cabra . '/create' ?>">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id_cabra" value="<?= $id_cabra ?>">

    <div class="form-group">
        <label for="id_propietario">Propietario *</label>
        <select name="id_propietario" id="id_propietario" required>
            <?php foreach ($propietarios () as $p): ?>
                <option value="<?= $p['id_propietario'] ?>"
                    <?= isset($historial) && $historial['id_propietario'] == $p['id_propietario'] ? 'selected' : '' ?> >
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha_inicio">Fecha Inicio *</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required value="<?= $historial['fecha_inicio'] ?? '' ?>">
    </div>

    <div class="form-group">
        <label for="fecha_fin">Fecha Fin</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $historial['fecha_fin'] ?? '' ?>">
    </div>

    <div class="form-group">
        <label for="motivo_cambio">Motivo</label>
        <input type="text" name="motivo_cambio" id="motivo_cambio" value="<?= $historial['motivo_cambio'] ?? '' ?>">
    </div>

    <div class="form-group">
        <label for="precio_transaccion">Precio</label>
        <input type="number" name="precio_transaccion" id="precio_transaccion" step="0.01" value="<?= $historial['precio_transaccion'] ?? '' ?>">
    </div>

    <button type="submit" class="btn btn-success">💾 Guardar</button>
    <a href="<?= BASE_URL ?>/cabras/<?= $id_cabra ?>" class="btn btn-secondary">← Cancelar</a>
</form>
