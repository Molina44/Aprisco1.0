<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Propietario - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
<div class="container">
    <header class="dashboard-header">
        <h1>👤 <?= htmlspecialchars($propietario['nombre']) ?></h1>
        <nav>
            <a href="<?= BASE_URL ?>/propietarios" class="btn btn-secondary">← Volver</a>
            <a href="<?= BASE_URL ?>/propietarios/<?= $propietario['id_propietario'] ?>/edit" class="btn btn-warning">✏️ Editar</a>
            <form method="POST" action="<?= BASE_URL ?>/propietarios/<?= $propietario['id_propietario'] ?>/delete" style="display:inline-block" onsubmit="return confirm('¿Eliminar este propietario permanentemente?')">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
            </form>
        </nav>
    </header>

    <main class="main-content">
        <div class="form-container">
            <div class="form-section">
                <h3>📋 Información General</h3>
                <div class="info-items">
                    <div class="info-item"><strong>Nombre:</strong> <?= htmlspecialchars($propietario['nombre']) ?></div>
                    <div class="info-item"><strong>Identificación:</strong> <?= htmlspecialchars($propietario['identificacion']) ?: '<span class="text-muted">No registrada</span>' ?></div>
                    <div class="info-item"><strong>Dirección:</strong> <?= htmlspecialchars($propietario['direccion']) ?: '<span class="text-muted">No registrada</span>' ?></div>
                    <div class="info-item"><strong>Teléfono:</strong> <?= htmlspecialchars($propietario['telefono']) ?: '<span class="text-muted">No registrado</span>' ?></div>
                    <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($propietario['email']) ?: '<span class="text-muted">No registrado</span>' ?></div>
                    <div class="info-item"><strong>Fecha de Registro:</strong> <?= date('d/m/Y', strtotime($propietario['fecha_registro'])) ?></div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .form-container {
        max-width: 800px;
        margin: 20px auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .form-section h3 {
        margin-bottom: 20px;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
    }

    .info-items {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-item {
        font-size: 1.1em;
    }

    .text-muted {
        color: #888;
        font-style: italic;
    }

    .dashboard-header nav {
        margin-top: 10px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>
</body>
</html>
