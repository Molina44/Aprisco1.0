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
    <title>Registrar Nueva Cabra - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    <div class="container">
          
        <header class="dashboard-header">
            
            
        <main class="main-content"><h1>🐐 Registrar Nueva Cabra</h1>
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

            <!-- Formulario de registro -->
            <div class="form-container">
                <form method="POST" action="<?php echo BASE_URL; ?>/cabras/create" enctype="multipart/form-data" class="cabra-form">
                      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="form-sections">
                        <!-- Información Básica -->
                        <div class="form-section">
                            <h3>📋 Información Básica</h3>
                            
                            <div class="form-group">
                                <label for="nombre">Nombre de la Cabra *</label>
                                <input type="text" id="nombre" name="nombre" required 
                                       value="<?php echo isset($_SESSION['form_data']['nombre']) ? e($_SESSION['form_data']['nombre']) : ''; ?>"
                                       placeholder="Ej: Esperanza, Ramón, etc.">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sexo">Sexo *</label>
                                    <select id="sexo" name="sexo" required>
                                        <option value="">Seleccionar sexo</option>
                                        <option value="MACHO" <?php echo (isset($_SESSION['form_data']['sexo']) && $_SESSION['form_data']['sexo'] === 'MACHO') ? 'selected' : ''; ?>>
                                            ♂ Macho
                                        </option>
                                        <option value="HEMBRA" <?php echo (isset($_SESSION['form_data']['sexo']) && $_SESSION['form_data']['sexo'] === 'HEMBRA') ? 'selected' : ''; ?>>
                                            ♀ Hembra
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                           value="<?php echo isset($_SESSION['form_data']['fecha_nacimiento']) ? e($_SESSION['form_data']['fecha_nacimiento']) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" id="color" name="color" 
                                           value="<?php echo isset($_SESSION['form_data']['color']) ? e($_SESSION['form_data']['color']) : ''; ?>"
                                           placeholder="Ej: Blanco, Negro, Marrón, etc.">
                                </div>

                                <div class="form-group">
                                    <label for="id_raza">Raza</label>
                                    <select id="id_raza" name="id_raza">
                                        <option value="">Seleccionar raza</option>
                                        <?php if (!empty($breeds)): ?>
                                            <?php foreach ($breeds as $breed): ?>
                                                <option value="<?php echo $breed['id_raza']; ?>"
                                                    <?php echo (isset($_SESSION['form_data']['id_raza']) && $_SESSION['form_data']['id_raza'] == $breed['id_raza']) ? 'selected' : ''; ?>>
                                                    <?php echo e($breed['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
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
                                                    <?php echo (isset($_SESSION['form_data']['madre']) && $_SESSION['form_data']['madre'] == $female['id_cabra']) ? 'selected' : ''; ?>>
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
                                                    <?php echo (isset($_SESSION['form_data']['padre']) && $_SESSION['form_data']['padre'] == $male['id_cabra']) ? 'selected' : ''; ?>>
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
                                                <?php echo (isset($_SESSION['form_data']['id_propietario_actual']) && $_SESSION['form_data']['id_propietario_actual'] == $owner['id_propietario']) ? 'selected' : ''; ?>>
                                                <?php echo e($owner['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="form-section">
                            <h3>📷 Fotografía</h3>
                            
                            <div class="form-group">
                                <label for="foto">Foto de la Cabra</label>
                                <input type="file" id="foto" name="foto" accept="image/*">
                                <small class="form-help">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB
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
                            💾 Registrar Cabra
                        </button>
                        <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">
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
        });
    </script>

    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .cabra-form {
            padding: 30px;
        }

        .form-sections {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .form-section {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            background: #fafafa;
        }

        .form-section h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 1.2em;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .form-help {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 0.9em;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                margin: 10px;
            }
            
            .cabra-form {
                padding: 20px;
            }
        }
    </style>

    <?php 
    // Limpiar datos del formulario de la sesión
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>
</body>
</html>