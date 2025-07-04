<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de <?php echo e($cabra['nombre']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 <?php echo e($cabra['nombre']); ?></h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">← Volver a Lista</a>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" class="btn btn-warning">✏️ Editar</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
               
            </nav>
        </header>

        <main class="main-content">
            <!-- Mensajes de éxito o error -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="cabra-detail-container">
                <div class="cabra-detail-card">
                    <!-- Foto de la cabra -->
                    <div class="cabra-photo-section">
                        <?php if (!empty($cabra['foto'])): ?>
                            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($cabra['foto']); ?>" 
                                 alt="<?php echo e($cabra['nombre']); ?>" class="cabra-detail-image">
                        <?php else: ?>
                            <div class="no-photo-large">🐐</div>
                        <?php endif; ?>
                    </div>

                    <!-- Información de la cabra -->
                    <div class="cabra-info-section">
                        <div class="info-header">
                            <h2><?php echo e($cabra['nombre']); ?></h2>
                            <span class="status-badge-large <?php echo strtolower($cabra['estado']); ?>">
                                <?php echo e($cabra['estado']); ?>
                            </span>
                        </div>

                        <div class="info-grid">
                            <!-- Información básica -->
                            <div class="info-group">
                                <h3>📋 Información Básica</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>Sexo:</strong>
                                        <span class="sex-badge-large <?php echo strtolower($cabra['sexo']); ?>">
                                            <?php echo $cabra['sexo'] === 'MACHO' ? '♂' : '♀'; ?> <?php echo e($cabra['sexo']); ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($cabra['fecha_nacimiento'])): ?>
                                        <div class="info-item">
                                            <strong>Fecha de Nacimiento:</strong>
                                            <?php echo date('d/m/Y', strtotime($cabra['fecha_nacimiento'])); ?>
                                            <small>(<?php 
                                                $fecha_nac = new DateTime($cabra['fecha_nacimiento']);
                                                $hoy = new DateTime();
                                                $edad = $hoy->diff($fecha_nac);
                                                echo $edad->y . ' años, ' . $edad->m . ' meses';
                                            ?>)</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($cabra['color'])): ?>
                                        <div class="info-item">
                                            <strong>Color:</strong> <?php echo e($cabra['color']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($cabra['raza_nombre'])): ?>
                                        <div class="info-item">
                                            <strong>Raza:</strong> <?php echo e($cabra['raza_nombre']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Parentesco -->
                            <div class="info-group">
                                <h3>👨‍👩‍👧‍👦 Parentesco</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>Madre:</strong>
                                        <?php if (!empty($cabra['madre_nombre'])): ?>
                                            <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['madre']; ?>" class="link">
                                                <?php echo e($cabra['madre_nombre']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No registrada</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="info-item">
                                        <strong>Padre:</strong>
                                        <?php if (!empty($cabra['padre_nombre'])): ?>
                                            <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['padre']; ?>" class="link">
                                                <?php echo e($cabra['padre_nombre']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No registrado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Propietario -->
                            <div class="info-group">
                                <h3>👤 Propietario</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>Propietario Actual:</strong>
                                        <?php if (!empty($cabra['propietario_nombre'])): ?>
                                            <?php echo e($cabra['propietario_nombre']); ?>
                                        <?php else: ?>
                                            <span class="text-muted">No asignado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Registro -->
                            <div class="info-group">
                                <h3>📅 Información de Registro</h3>
                                <div class="info-items">
                                    <div class="info-item">
                                        <strong>Fecha de Registro:</strong>
                                        <?php echo date('d/m/Y H:i:s', strtotime($cabra['fecha_registro'])); ?>
                                    </div>
                                    
                                    <?php if (!empty($cabra['creado_por_nombre'])): ?>
                                        <div class="info-item">
                                            <strong>Registrado por:</strong> <?php echo e($cabra['creado_por_nombre']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="detail-actions">
                            <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" 
                               class="btn btn-warning">✏️ Editar Cabra</a>
                                <form method="POST" action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete" 
          style="display: inline-block;" 
          onsubmit="return confirm('¿Estás seguro de eliminar la cabra <?php echo e($cabra['nombre']); ?>? Esta acción la marcará como INACTIVA.')">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="id" value="<?php echo $cabra['id_cabra']; ?>">
        <button type="submit" class="btn btn-sm btn-danger">🗑️ Eliminar</button>
    </form>
                            <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">← Volver a Lista</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .cabra-detail-container {
            max-width: 1000px;
            margin: 20px auto;
        }

        .cabra-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 0;
        }

        .cabra-photo-section {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }

        .cabra-detail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-photo-large {
            font-size: 120px;
            color: #ddd;
        }

        .cabra-info-section {
            padding: 30px;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .info-header h2 {
            margin: 0;
            color: #333;
            font-size: 2em;
        }

        .status-badge-large {
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .status-badge-large.activa {
            background: #d4edda;
            color: #155724;
        }

        .status-badge-large.inactiva {
            background: #f8d7da;
            color: #721c24;
        }

        .sex-badge-large {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .sex-badge-large.macho {
            background: #e3f2fd;
            color: #1976d2;
        }

        .sex-badge-large.hembra {
            background: #fce4ec;
            color: #c2185b;
        }

        .info-grid {
            display: grid;
            gap: 25px;
        }

        .info-group {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
        }

        .info-group h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 1.1em;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
        }

        .info-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item strong {
            min-width: 140px;
            color: #495057;
        }

        .link {
            color: #007bff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .text-muted {
            color: #6c757d;
            font-style: italic;
        }

        .detail-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .cabra-detail-card {
                grid-template-columns: 1fr;
                margin: 10px;
            }
            
            .cabra-photo-section {
                min-height: 250px;
            }
            
            .info-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .detail-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>