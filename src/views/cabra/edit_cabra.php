<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?php echo e($cabra['nombre']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 Editar: <?php echo e($cabra['nombre']); ?></h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>" class="btn btn-secondary">← Ver Detalles</a>
                <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">Lista de Cabras</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
                
            </nav>
        </header>

        <main class="main-content">
            <!-- Mensajes de error -->
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <h4>Por favor, corrige los siguientes errores:</h4>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" method="POST" class="form">
                <div class="form-group
">
                    <label for="nombre">Nombre de la Cabra:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo e($cabra['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="raza">Raza:</label>
                    <input type="text" id="raza" name="raza" value="<?php echo e($cabra['raza']); ?>" required>
                </div>
                <div class="form-group
">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo e($cabra['fecha_nacimiento']); ?>" required>
                </div>
                <div class="form-group
">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" id="peso" name="peso" value="<?php echo e($cabra['peso']); ?>" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="vivo" <?php echo $cabra['estado'] === 'vivo' ? 'selected' : ''; ?>>Vivo</option>
                        <option value="muerto" <?php echo $cabra['estado'] === 'muerto' ? 'selected' : ''; ?>>Muerto</option>
                    </select>
                </div>
                <div class="form-group  
">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones" rows="4"><?php echo e($cabra['observaciones']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        </main>
    </div>
</body>
</html>
<?php
// Limpiar mensajes de sesión
unset($_SESSION['errors']);
unset($_SESSION['error']);
unset($_SESSION['
success']);
unset($_SESSION['cabra']);
unset($_SESSION['cabra_id']);
unset($_SESSION['cabra_nombre']);