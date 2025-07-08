<?php // src/views/historial_propiedad/index_historial.php ?>
<?php if (!empty($historial)): ?>
    <h3>📜 Historial de Propiedad</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Propietario</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Motivo</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['nombre_propietario']) ?></td>
                    <td><?= date('d/m/Y', strtotime($h['fecha_inicio'])) ?></td>
                    <td><?= $h['fecha_fin'] ? date('d/m/Y', strtotime($h['fecha_fin'])) : '-' ?></td>
                    <td><?= htmlspecialchars($h['motivo_cambio'] ?? '-') ?></td>
                    <td>$<?= number_format($h['precio_transaccion'], 2) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/historial/<?= $h['id_historial'] ?>" class="btn btn-info">👁️</a>
                        <a href="<?= BASE_URL ?>/historial/<?= $h['id_historial'] ?>/edit" class="btn btn-warning">✏️</a>
                        <form method="POST" action="<?= BASE_URL ?>/historial/<?= $h['id_historial'] ?>/delete" style="display:inline-block" onsubmit="return confirm('¿Eliminar este historial?')">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay historial registrado.</p>
<?php endif; ?>
<a href="<?= BASE_URL ?>/historial/<?= $id_cabra ?>/create" class="btn btn-primary">➕ Registrar Historial</a>
