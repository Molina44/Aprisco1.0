<?php
require_once __DIR__ . '/../../models/HistorialPropiedad.php';
require_once __DIR__ . '/../../models/Propietarios.php';
require_once __DIR__ . '/../../models/Parto.php';
require_once __DIR__ . '/../../models/EventoReproductivo.php';
require_once __DIR__ . '/../../models/ControlSanitario.php';
require_once __DIR__ . '/../../models/DocumentosCabras.php';



$db = (new Database())->getConnection();

$historialModel = new HistorialPropiedad($db);
$historial_propiedad = $historialModel->getByCabra($cabra['id_cabra']);

$partoModel = new Parto($db);
$partos = $partoModel->getByCabra($cabra['id_cabra']);
$padres = $partoModel->getPadresDisponibles();

$eventoModel = new EventoReproductivo($db);
$eventos_reproductivos = $eventoModel->getByCabra($cabra['id_cabra']);

$controlSanitarioModel = new ControlSanitario($db);
$controles_sanitarios = $controlSanitarioModel->getByCabra($cabra['id_cabra']);

$documentosModel = new DocumentosCabras($db);
$documentos_cabra = $documentosModel->getByCabra($cabra['id_cabra']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de <?php echo e($cabra['nombre']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
        <header class="dashboard-header">
            <h1>🐐 <?php echo e($cabra['nombre']); ?></h1>
        </header>

        <main class="main-content">
            <!-- Mensajes de éxito o error -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo e($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo e($_SESSION['error']);
                    unset($_SESSION['error']); ?>
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
                            <a href="<?= BASE_URL ?>/cabras/<?= $cabra['id_cabra'] ?>/pdf" target="_blank" class="btn btn-info">Generar PDF</a>
                            <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit"
                                class="btn btn-warning"> Editar Cabra</a>
                            <form method="POST" action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete"
                                style="display: inline-block;"
                                onsubmit="return confirm('¿Estás seguro de eliminar la cabra <?php echo e($cabra['nombre']); ?>? Esta acción la marcará como INACTIVA.')">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="id" value="<?php echo $cabra['id_cabra']; ?>">
                                <button type="submit"   class="btn btn-warning">Eliminar  </button>
                            </form>
                   
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
<?php if ($cabra['sexo'] === 'HEMBRA'): ?>
        <!-- Sección adicional: Historial de Partos -->
        <div class="info-group">
            <h3>👶 Historial de Partos</h3>

            <div class="detail-actions">
                <a href="<?= BASE_URL ?>/partos/<?= $cabra['id_cabra'] ?>/create" class="btn btn-primary">➕ Añadir Parto</a>
            </div>

            <?php if (!empty($partos)): ?>
                <div class="info-items">
                    <?php foreach ($partos as $parto): ?>
                        <div class="info-item">
                            <div>
                                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($parto['fecha_parto'])) ?><br>
                                <strong>Crías:</strong> <?= $parto['numero_crias'] ?><br>
                                <strong>Peso Total:</strong> <?= $parto['peso_total_crias'] ?? '-' ?> kg<br>
                                <strong>Tipo:</strong> <?= $parto['tipo_parto'] ?><br>
                                <strong>Dificultad:</strong> <?= $parto['dificultad'] ?><br>
                                <strong>Padre:</strong>
                                <?= !empty($parto['nombre_padre']) ? htmlspecialchars($parto['nombre_padre']) : '<span class="text-muted">No registrado</span>' ?><br>
                                <?php if (!empty($parto['observaciones'])): ?>
                                    <strong>Obs.:</strong> <?= htmlspecialchars($parto['observaciones']) ?><br>
                                <?php endif; ?>
                                <strong>Registrado por:</strong> <?= $parto['nombre_usuario'] ?? '-' ?>
                            </div>

                            <div style="margin-left:auto;">
                                <a href="<?= BASE_URL ?>/partos/<?= $parto['id_parto'] ?>/edit" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form method="POST" action="<?= BASE_URL ?>/partos/<?= $parto['id_parto'] ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este parto?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🔚️</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay partos registrados.</p>
            <?php endif; ?>
        </div>



        <!-- Sección adicional: Eventos Reproductivos -->
        <div class="info-group">
            <h3>📅 Eventos Reproductivos</h3>
            <div class="detail-actions">
                <a href="<?= BASE_URL ?>/eventos/<?= $cabra['id_cabra'] ?>/create" class="btn btn-primary">➕ Añadir Evento</a>
            </div>

            <?php if (!empty($eventos_reproductivos)): ?>
                <div class="info-items">
                    <?php foreach ($eventos_reproductivos as $evento): ?>
                        <div class="info-item">
                            <div>
                                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($evento['fecha_evento'])) ?><br>
                                <strong>Tipo:</strong> <?= $evento['tipo_evento'] ?><br>
                                <strong>Semental:</strong>
                                <?= !empty($evento['nombre_semental']) ? htmlspecialchars($evento['nombre_semental']) : '<span class="text-muted">No registrado</span>' ?><br>
                                <?php if (!empty($evento['observaciones'])): ?>
                                    <strong>Obs.:</strong> <?= htmlspecialchars($evento['observaciones']) ?><br>
                                <?php endif; ?>
                                <strong>Registrado por:</strong> <?= $evento['nombre_usuario'] ?? '-' ?>
                            </div>

                            <div style="margin-left:auto;">
                                <a href="<?= BASE_URL ?>/eventos/<?= $evento['id_evento'] ?>/edit" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form method="POST" action="<?= BASE_URL ?>/eventos/<?= $evento['id_evento'] ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este evento?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay eventos registrados.</p>
            <?php endif; ?>
        </div>
<?php endif; ?>


        <!-- Sección adicional: Controles Sanitarios -->
        <div class="info-group">
            <h3>🩺 Controles Sanitarios</h3>
            <div class="detail-actions">
                <a href="<?= BASE_URL ?>/controles/<?= $cabra['id_cabra'] ?>/create" class="btn btn-primary">➕ Añadir Control</a>
            </div>

            <?php if (!empty($controles_sanitarios)): ?>
                <div class="info-items">
                    <?php foreach ($controles_sanitarios as $control): ?>
                        <div class="info-item">
                            <div>
                                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($control['fecha_control'])) ?><br>
                                <strong>Peso:</strong> <?= $control['peso_kg'] ?? '-' ?> kg<br>
                                <strong>Condición:</strong> <?= $control['condicion_especial'] ?? '-' ?><br>
                                <strong>Famacha:</strong> <?= $control['famacha'] ?? '-' ?><br>
                                <strong>Drack:</strong> <?= $control['drack_score'] ?? '-' ?><br>
                                <?php if (!empty($control['foto_ubre'])): ?>
                                    <strong>Foto Ubre:</strong>
                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($control['foto_ubre']) ?>" alt="Foto Ubre" style="max-height:100px;">

                                <?php endif; ?>
                                <strong>Observaciones:</strong> <?= nl2br(htmlspecialchars($control['observaciones'])) ?><br>
                            </div>
                            <div style="margin-left:auto;">
                                <form method="POST" action="<?= BASE_URL ?>/controles/<?= $control['id_control'] ?>/delete" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este control?')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay controles sanitarios registrados.</p>
            <?php endif; ?>
        </div>
        <!-- Sección adicional: Documentos Adjuntos -->
        <div class="info-group">
            <h3>📂 Documentos Adjuntos</h3>
            <div class="detail-actions">
                <a href="<?= BASE_URL ?>/documentos/<?= $cabra['id_cabra'] ?>/create" class="btn btn-primary">➕ Subir Documento</a>
            </div>

            <?php if (!empty($documentos_cabra)): ?>
                <div class="info-items">
                    <?php foreach ($documentos_cabra as $doc): ?>
                        <div class="info-item">
                            <div>
                                <strong>Archivo:</strong>
                                <a href="<?= BASE_URL ?>/uploads/<?= $doc['ruta_archivo'] ?>" download>
                                    <?= basename($doc['ruta_archivo']) ?> ⬇️
                                </a><br>
                                <strong>Tipo:</strong> <?= htmlspecialchars($doc['tipo_documento']) ?><br>
                                <strong>Subido por:</strong> <?= htmlspecialchars($doc['nombre_usuario']) ?><br>
                                <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?>
                            </div>
                            <div style="margin-left:auto;">
                                <form method="POST" action="<?= BASE_URL ?>/documentos/<?= $doc['id_documento'] ?>/delete"
                                    onsubmit="return confirm('¿Eliminar este documento?')" style="display:inline-block;">
                                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay documentos adjuntos.</p>
            <?php endif; ?>
        </div>


      
            <?php
            // Función PHP mejorada para el árbol genealógico
            function renderTreeAsc($node, $gen = 1)
            {
                if (!$node || $gen > 4) return;

                $sexoClass = strtolower($node['sexo']) === 'macho' ? 'macho' : 'hembra';
                $iconClass = strtolower($node['sexo']) === 'macho' ? 'fas fa-mars' : 'fas fa-venus';

                echo "<div class='gen-nivel'>";
                echo "<div class='gen-hijos'>";

                // Renderizar hijos primero (padres)
                if (isset($node['padre'])) renderTreeAsc($node['padre'], $gen + 1);
                if (isset($node['madre'])) renderTreeAsc($node['madre'], $gen + 1);

                echo "</div>";

                if ($gen < 4) {
                    echo "<div class='gen-connector'></div>";
                }

                echo "<div class='cabra-box {$sexoClass}'>";
                echo "<div class='gen-badge'>Gen {$gen}</div>";

                // Icono especial para el individuo principal
                if ($gen === 1) {
                    echo "<i class='fas fa-star animal-icon' style='color: #f39c12;'></i>";
                } else {
                    echo "<i class='{$iconClass} animal-icon'></i>";
                }

                echo "<div class='animal-name'>" . htmlspecialchars($node['nombre']) . "</div>";
                echo "<div class='animal-info'>";
                echo "<i class='{$iconClass}'></i>";
                echo "<span>" . ucfirst($node['sexo']) . "</span>";
                echo "</div>";

                // Imagen
                if (!empty($node['foto'])) {
                    $img = BASE_URL . "/uploads/" . htmlspecialchars($node['foto']);
                    echo "<img src='{$img}' alt='Foto' class='animal-photo'>";
                } else {
                    // Placeholder si no hay foto
                    $placeholder = strtoupper(substr($node['nombre'], 0, 2));
                    $color = $sexoClass === 'macho' ? '3498db' : 'e91e63';
                    echo "<img src='https://via.placeholder.com/60x60/{$color}/ffffff?text={$placeholder}' alt='Foto' class='animal-photo'>";
                }

                echo "</div>";
                echo "</div>";
            }
            ?>

            <!-- HTML Structure -->
           
                <div class="header">
                    <h1>
                        <i class="fas fa-sitemap"></i>
                        Árbol Genealógico
                    </h1>
                    <p>Linaje familiar hasta 4 generaciones</p>
                </div>

                <div class="tree-container">
                    <div class="genealogia-tree-asc">
                        <?php renderTreeAsc($genealogia); ?>
                    </div>

                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color macho"></div>
                            <span><i class="fas fa-mars"></i> Macho</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color hembra"></div>
                            <span><i class="fas fa-venus"></i> Hembra</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-star" style="color: #f39c12;"></i>
                            <span>Individuo Principal</span>
                        </div>
                    </div>
                </div>
       





        <style>
            /* Importar Font Awesome */
            @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .header {
                background: linear-gradient(135deg, #260F01, #583619 );
                color: white;
                padding: 30px;
                text-align: center;
            }

            .header h1 {
                font-size: 2.5em;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15px;
            }

            .header p {
                font-size: 1.1em;
                opacity: 0.9;
            }

            .tree-container {
                padding: 40px 20px;
                overflow-x: auto;
            }

            .genealogia-tree-asc {
                display: flex;
                flex-direction: column-reverse;
                align-items: center;
                gap: 50px;
                position: relative;
                min-width: 800px;
            }

            .gen-nivel {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
            }

            .gen-hijos {
                display: flex;
                justify-content: center;
                gap: 60px;
                position: relative;
                flex-wrap: wrap;
            }

            .gen-connector {
                width: 3px;
                height: 30px;
                background: linear-gradient(to bottom,  #260F01, #583619 );
                margin: 10px 0;
                border-radius: 2px;
                position: relative;
            }

            .gen-connector::before {
                content: '';
                position: absolute;
                top: -5px;
                left: 50%;
                transform: translateX(-50%);
                width: 10px;
                height: 10px;
                background: #583619  ;
                border-radius: 50%;
            }

            .cabra-box {
                background: white;
                border: 2px solid #e0e0e0;
                padding: 20px;
                border-radius: 15px;
                min-width: 180px;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                position: relative;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .cabra-box:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }

            .cabra-box.macho {
                border-color: #3498db;
                background: linear-gradient(135deg, #f8fbff 0%, #e3f2fd 100%);
            }

            .cabra-box.hembra {
                border-color: #e91e63;
                background: linear-gradient(135deg, #fff8fb 0%, #fce4ec 100%);
            }

            .gen-badge {
                position: absolute;
                top: -10px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(135deg,#260F01, #583619 100%);
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.8em;
                font-weight: bold;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .animal-icon {
                font-size: 2.5em;
                margin-bottom: 10px;
                display: block;
            }

            .macho .animal-icon {
                color: #3498db;
            }

            .hembra .animal-icon {
                color: #e91e63;
            }

            .animal-name {
                font-size: 1.2em;
                font-weight: bold;
                margin-bottom: 8px;
                color: #2c3e50;
            }

            .animal-info {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                margin-bottom: 8px;
                color: #7f8c8d;
                font-size: 0.9em;
            }

            .animal-photo {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid #ecf0f1;
                margin-top: 10px;
                transition: all 0.3s ease;
            }

            .animal-photo:hover {
                transform: scale(1.1);
                border-color: #3498db;
            }

            .legend {
                display: flex;
                justify-content: center;
                gap: 30px;
                margin-top: 30px;
                padding: 20px;
                background: rgba(236, 240, 241, 0.5);
                border-radius: 15px;
            }

            .legend-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 0.9em;
                color: #2c3e50;
            }

            .legend-color {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 2px solid;
            }

            .legend-color.macho {
                background: linear-gradient(135deg, #f8fbff 0%, #e3f2fd 100%);
                border-color: #3498db;
            }

            .legend-color.hembra {
                background: linear-gradient(135deg, #fff8fb 0%, #fce4ec 100%);
                border-color: #e91e63;
            }

            @media (max-width: 768px) {
                .gen-hijos {
                    gap: 30px;
                }

                .cabra-box {
                    min-width: 150px;
                    padding: 15px;
                }

                .header h1 {
                    font-size: 2em;
                }

                .legend {
                    flex-direction: column;
                    gap: 15px;
                }
            }


            .cabra-detail-container {
                max-width: 1000px;
                margin: 20px auto;
            }

            .cabra-detail-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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