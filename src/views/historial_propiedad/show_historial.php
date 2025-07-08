<?php // src/views/historial_propiedad/show_historial.php ?>
<h3>👁️ Detalles del Historial</h3>
<ul class="detail-list">
    <li><strong>Propietario:</strong> <?= htmlspecialchars($historial['nombre_propietario']) ?></li>
    <li><strong>Fecha Inicio:</strong> <?= date('d/m/Y', strtotime($historial['fecha_inicio'])) ?></li>
    <li><strong>Fecha Fin:</strong> <?= $historial['fecha_fin'] ? date('d/m/Y', strtotime($historial['fecha_fin'])) : '-' ?></li>
    <li><strong>Motivo:</strong> <?= htmlspecialchars($historial['motivo_cambio'] ?? '-') ?></li>
    <li><strong>Precio:</strong> $<?= number_format($historial['precio_transaccion'], 2) ?></li>
</ul>
<a href="<?= BASE_URL ?>/cabras/<?= $id_cabra ?>" class="btn btn-secondary">← Volver</a>
