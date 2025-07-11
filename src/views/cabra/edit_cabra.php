<?php
// Generar token CSRF solo si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
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
        <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
        <header class="dashboard-header">
            <h1>🐐 Editar: <?php echo e($cabra['nombre']); ?></h1>
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

            <!-- Formulario de edición -->
            <div class="form-container">
                <form action="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" method="POST" enctype="multipart/form-data" class="cabra-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-sections">
                        <!-- Información Básica -->
                        <div class="form-section">
                            <h3>📋 Información Básica</h3>
                            
                            <div class="form-group">
                                <label for="nombre">Nombre de la Cabra *</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo e($cabra['nombre']); ?>" required
                                       placeholder="Ej: Esperanza, Ramón, etc.">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sexo">Sexo *</label>
                                    <select id="sexo" name="sexo" required>
                                        <option value="">Seleccionar sexo</option>
                                        <option value="MACHO" <?php echo (isset($cabra['sexo']) && $cabra['sexo'] === 'MACHO') ? 'selected' : ''; ?>>
                                            ♂ Macho
                                        </option>
                                        <option value="HEMBRA" <?php echo (isset($cabra['sexo']) && $cabra['sexo'] === 'HEMBRA') ? 'selected' : ''; ?>>
                                            ♀ Hembra
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                           value="<?php echo e($cabra['fecha_nacimiento']); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" id="color" name="color" value="<?php echo e($cabra['color']); ?>"
                                           placeholder="Ej: Blanco, Negro, Marrón, etc.">
                                </div>

                                <div class="form-group">
                                    <label for="id_raza">Raza</label>
                                    <select id="id_raza" name="id_raza">
                                        <option value="">Seleccionar raza</option>
                                        <?php if (!empty($breeds)): ?>
                                            <?php foreach ($breeds as $breed): ?>
                                                <option value="<?php echo $breed['id_raza']; ?>"
                                                    <?php echo (isset($cabra['id_raza']) && $cabra['id_raza'] == $breed['id_raza']) ? 'selected' : ''; ?>>
                                                    <?php echo e($breed['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="estado">Estado *</label>
                                <select id="estado" name="estado" required>
                                    <option value="ACTIVA" <?php echo (isset($cabra['estado']) && $cabra['estado'] === 'ACTIVA') ? 'selected' : ''; ?>>
                                        ✅ Activa
                                    </option>
                                    <option value="INACTIVA" <?php echo (isset($cabra['estado']) && $cabra['estado'] === 'INACTIVA') ? 'selected' : ''; ?>>
                                        ❌ Inactiva
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Información de Parentesco -->
                        <div class="form-section">
                            <h3>👨‍👩‍👧‍👦 Información de Parentesco</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="madre">Madre</label>
                                    <select id="madre" name="madre">
                                        <option value="">Seleccionar madre</option>
                                        <?php if (!empty($females)): ?>
                                            <?php foreach ($females as $female): ?>
                                                <option value="<?php echo $female['id_cabra']; ?>"
                                                    <?php echo (isset($cabra['madre']) && $cabra['madre'] == $female['id_cabra']) ? 'selected' : ''; ?>>
                                                    <?php echo e($female['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="padre">Padre</label>
                                    <select id="padre" name="padre">
                                        <option value="">Seleccionar padre</option>
                                        <?php if (!empty($males)): ?>
                                            <?php foreach ($males as $male): ?>
                                                <option value="<?php echo $male['id_cabra']; ?>"
                                                    <?php echo (isset($cabra['padre']) && $cabra['padre'] == $male['id_cabra']) ? 'selected' : ''; ?>>
                                                    <?php echo e($male['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Propiedad -->
                        <div class="form-section">
                            <h3>👤 Información de Propiedad</h3>
                            
                            <div class="form-group">
                                <label for="id_propietario_actual">Propietario Actual</label>
                                <select id="id_propietario_actual" name="id_propietario_actual">
                                    <option value="">Seleccionar propietario</option>
                                    <?php if (!empty($owners)): ?>
                                        <?php foreach ($owners as $owner): ?>
                                            <option value="<?php echo $owner['id_propietario']; ?>"
                                                <?php echo (isset($cabra['id_propietario_actual']) && $cabra['id_propietario_actual'] == $owner['id_propietario']) ? 'selected' : ''; ?>>
                                                <?php echo e($owner['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Fotografía -->
                        <div class="form-section">
                            <h3>📷 Fotografía</h3>
                            
                            <?php if (!empty($cabra['foto'])): ?>
                                <div class="current-photo">
                                    <label>Foto Actual:</label>
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo e($cabra['foto']); ?>" 
                                         alt="Foto actual de <?php echo e($cabra['nombre']); ?>" 
                                         style="max-width: 200px; max-height: 200px; border-radius: 8px; display: block; margin: 10px 0;">
                                </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="foto">Cambiar Foto</label>
                                <input type="file" id="foto" name="foto" accept="image/*">
                                <small class="form-help">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB
                                    <?php if (!empty($cabra['foto'])): ?>
                                        <br>Dejar vacío si no desea cambiar la foto actual.
                                    <?php endif; ?>
                                </small>
                            </div>

                            <div id="preview-container" style="display: none;">
                                <img id="photo-preview" src="" alt="Vista previa" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            💾 Guardar Cambios
                        </button>
                        <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>" class="btn btn-secondary">
                            ❌ Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Vista previa de la imagen
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                    document.getElementById('preview-container').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('preview-container').style.display = 'none';
            }
        });

        // Validación del formulario
        document.querySelector('.cabra-form').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const sexo = document.getElementById('sexo').value;
            const estado = document.getElementById('estado').value;

            if (!nombre) {
                alert('El nombre de la cabra es obligatorio');
                e.preventDefault();
                return;
            }

            if (!sexo) {
                alert('Debe seleccionar el sexo de la cabra');
                e.preventDefault();
                return;
            }

            if (!estado) {
                alert('Debe seleccionar el estado de la cabra');
                e.preventDefault();
                return;
            }
        });

        // Mostrar confirmación para cambios críticos
        document.getElementById('estado').addEventListener('change', function(e) {
            if (e.target.value === 'INACTIVA') {
                const confirm = window.confirm('¿Está seguro de marcar esta cabra como inactiva? Esta acción es importante para los registros.');
                if (!confirm) {
                    e.target.value = 'ACTIVA';
                }
            }
        });

        // Validación de parentesco (evitar que se seleccione a sí mismo)
        const cabraId = <?php echo $cabra['id_cabra']; ?>;
        
        document.getElementById('madre').addEventListener('change', function(e) {
            if (parseInt(e.target.value) === cabraId) {
                alert('Una cabra no puede ser su propia madre');
                e.target.value = '';
            }
        });

        document.getElementById('padre').addEventListener('change', function(e) {
            if (parseInt(e.target.value) === cabraId) {
                alert('Una cabra no puede ser su propio padre');
                e.target.value = '';
            }
        });
    </script>


</body>
</html>