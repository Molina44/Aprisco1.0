<h2>Estadísticas de Cabras</h2>

<div class="stat-card">
    <h3>Distribución por Género</h3>
    <ul>
        <?php foreach ($stats['genero'] as $item): ?>
        <li><?= $item['genero'] ?>: <?= $item['total'] ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="stat-card">
    <h3>Promedios</h3>
    <p>Peso: <?= number_format($stats['promedios']['peso_promedio'], 2) ?> kg</p>
    <p>Altura: <?= number_format($stats['promedios']['altura_promedio'], 2) ?> cm</p>
</div>