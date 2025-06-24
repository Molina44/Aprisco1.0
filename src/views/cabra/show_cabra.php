<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de <?php echo htmlspecialchars($Cabra['nombre']); ?> - Aprisco</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-paw"></i> <?php echo htmlspecialchars($Cabra['nombre']); ?></h1>
                <div class="header-actions">
                    <a href="/src/controllers/CabraController.php?action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la Lista
                    </a>
                    <a href="/src/controllers/CabraController.php?action=edit&id=<?php echo $Cabra['id_cabra']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </header>

        <!-- Contenido principal -->
        <div class="Cabra-profile">
            <!-- Foto y información básica -->
            <div class="profile-header">
                <div class="profile-photo">
                    <?php if ($Cabra['foto']): ?>
                        <img src="/public/uploads/<?php echo htmlspecialchars($Cabra['foto']); ?>" 
                             alt="<?php echo htmlspecialchars($Cabra['nombre']); ?>" 
                             class="Cabra-photo">
                    <?php else: ?>
                        <div class="no-photo-large">
                            <i class="fas fa-image"></i>
                            <span>Sin foto</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($Cabra['nombre']); ?></h2>
                    <div class="profile-badges">
                        <span class="badge <?php echo $Cabra['sexo'] === 'MACHO' ? 'badge-primary' : 'badge-secondary'; ?>">
                            <i class="fas <?php echo $Cabra['sexo'] === 'MACHO' ? 'fa-mars' : 'fa-venus'; ?>"></i>
                            <?php echo $Cabra['sexo']; ?>
                        </span>
                        <span class="badge <?php echo $Cabra['estado'] === 'ACTIVA' ? 'badge-success' : 'badge-danger'; ?>">
                            <i class="fas <?php echo $Cabra['estado'] === 'ACTIVA' ? 'fa-check' : 'fa-times'; ?>"></i>
                            <?php echo $Cabra['estado']; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información detallada -->
            <div class="info-grid">
                <!-- Información básica -->
                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Información Básica</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <label>ID:</label>
                            <span><?php echo $Cabra['id_cabra']; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Nombre:</label>
                            <span><?php echo htmlspecialchars($Cabra['nombre']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Sexo:</label>
                            <span><?php echo $Cabra['sexo']; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Fecha de Nacimiento:</label>
                            <span>
                                <?php 
                                if ($Cabra['fecha_nacimiento']) {
                                    $fecha = new DateTime($Cabra['fecha_nacimiento']);
                                    $hoy = new DateTime();
                                    $edad = $hoy->diff($fecha);
                                    echo $fecha->format('d/m/Y') . ' (' . $edad->y . ' años, ' . $edad->m . ' meses)';
                                } else {
                                    echo 'No registrada';
                                }
                                ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label>Color:</label>
                            <span><?php echo htmlspecialchars($Cabra['color'] ?? 'No especificado'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Raza:</label>
                            <span><?php echo htmlspecialchars($Cabra['raza_nombre'] ?? 'No especificada'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Estado:</label>
                            <span><?php echo $Cabra['estado']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Información genealógica -->
                <div class="info-card">
                    <h3><i class="fas fa-sitemap"></i> Genealogía</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <label>Madre:</label>
                            <span>
                                <?php if ($Cabra['madre_nombre']): ?>
                                    <a href="/src/controllers/CabraController.php?action=show&id=<?php echo $Cabra['madre']; ?>" class="link">
                                        <?php echo htmlspecialchars($Cabra['madre_nombre']); ?>
                                    </a>
                                <?php else: ?>
                                    No registrada
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label>Padre:</label>
                            <span>
                                <?php if ($Cabra['padre_nombre']): ?>
                                    <a href="/src/controllers/CabraController.php?action=show&id=<?php echo $Cabra['padre']; ?>" class="link">
                                        <?php echo htmlspecialchars($Cabra['padre_nombre']); ?>
                                    </a>
                                <?php else: ?>
                                    No registrado
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información de propiedad -->
                <div class="info-card">
                    <h3><i class="fas fa-user"></i> Propiedad</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <label>Propietario Actual:</label>
                            <span><?php echo htmlspecialchars($Cabra['propietario_nombre'] ?? 'Sin propietario'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Información de registro -->
                <div class="info-card">
                    <h3><i class="fas fa-clock"></i> Información de Registro</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <label>Fecha de Registro:</label>
                            <span><?php echo date('d/m/Y', strtotime($Cabra['fecha_registro'])); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Registrado por:</label>
                            <span><?php echo htmlspecialchars($Cabra['creado_por_nombre'] ?? 'Usuario desconocido'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="quick-actions">
                <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                <div class="action-buttons">
                    <a href="/src/controllers/CabraController.php?action=edit&id=<?php echo $Cabra['id_cabra']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Información
                    </a>
                    <a href="#" class="btn btn-info" onclick="alert('Funcionalidad próximamente disponible'); return false;">
                        <i class="fas fa-plus"></i> Añadir Registro de Salud
                    </a>
                    <a href="#" class="btn btn-danger" onclick="alert('Funcionalidad próximamente disponible'); return false;">
                        <i class="fas fa-trash"></i> Eliminar Cabra
                    </a>
                </div>
            </div>
        </div>
