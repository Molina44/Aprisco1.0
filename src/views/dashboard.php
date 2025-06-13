<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>Bienvenido, <?php echo e($_SESSION['user_name']); ?>!</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">Perfil</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">Cerrar Sesión</a>
            </nav>
        </header>

        <main class="dashboard-content">
            <div class="user-info">
                <h2>Información de tu cuenta</h2>
                <p><strong>Nombre:</strong> <?php echo e($_SESSION['user_name']); ?></p>
                <p><strong>Email:</strong> <?php echo e($_SESSION['user_email']); ?></p>
                <p><strong>Última sesión:</strong> <?php echo date('d/m/Y H:i:s', $_SESSION['login_time']); ?></p>
            </div>

            <div class="dashboard-actions">
                <h2>Acciones disponibles</h2>
                <div class="action-cards">
                    <div class="card">
                        <h3>Perfil</h3>
                        <p>Actualiza tu información personal</p>
                        <button class="btn btn-primary">Editar Perfil</button>
                    </div>
                    
                    <div class="card">
                        <h3>Configuración</h3>
                        <p>Ajusta las configuraciones de tu cuenta</p>
                        <button class="btn btn-primary">Configurar</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>