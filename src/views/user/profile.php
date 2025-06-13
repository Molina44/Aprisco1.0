<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>Mi Perfil</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">← Volver al Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">Cerrar Sesión</a>
            </nav>
        </header>

        <?php showMessages(); ?>

        <main class="profile-content">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($user->nombre, 0, 2)); ?>
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo e($user->nombre); ?></h2>
                        <p class="profile-email"><?php echo e($user->email); ?></p>
                        <p class="profile-joined">
                            Miembro desde: <?php echo date('d/m/Y', strtotime($user->fecha_registro)); ?>
                        </p>
                    </div>
                </div>

                <div class="profile-details">
                    <h3>Información de la cuenta</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Nombre completo:</label>
                            <span><?php echo e($user->nombre); ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <label>Email:</label>
                            <span><?php echo e($user->email); ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <label>Teléfono:</label>
                            <span><?php echo $user->telefono ? e($user->telefono) : 'No especificado'; ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <label>Fecha de registro:</label>
                            <span><?php echo date('d/m/Y H:i', strtotime($user->fecha_registro)); ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <label>Último acceso:</label>
                            <span>
                                <?php 
                                if ($user->fecha_ultimo_acceso) {
                                    echo date('d/m/Y H:i', strtotime($user->fecha_ultimo_acceso));
                                } else {
                                    echo 'Primer acceso';
                                }
                                ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <label>Estado:</label>
                            <span class="status <?php echo $user->activo ? 'active' : 'inactive'; ?>">
                                <?php echo $user->activo ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="<?php echo BASE_URL; ?>/profile/edit" class="btn btn-primary">
                        📝 Editar Perfil
                    </a>
                    <a href="<?php echo BASE_URL; ?>/profile/password" class="btn btn-secondary">
                        🔒 Cambiar Contraseña
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>