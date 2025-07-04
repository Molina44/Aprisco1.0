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
        <header class="dashboard-header">
            <h1>🐐 Editar: <?php echo e($cabra['nombre']); ?></h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>" class="btn btn-secondary">← Ver Detalles</a>
                <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">Lista de Cabras</a>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">Cerrar Sesión</a>
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
                                    <img src="<?php echo BASE_URL; ?>/uploads/goats/<?php echo e($cabra['foto']); ?>" 
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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

        .current-photo {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .current-photo label {
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        /* Estilos para alertas mejoradas */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #ffebee;
            border: 1px solid #f44336;
            color: #c62828;
        }

        .alert-success {
            background-color: #e8f5e8;
            border: 1px solid #4CAF50;
            color: #2e7d32;
        }

        .alert h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 5px;
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

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</body>
</html>