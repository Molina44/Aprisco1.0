<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit();
}

// Recuperar datos del formulario si hay errores
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Cabra - Aprisco</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-plus"></i> Registrar Nueva Cabra</h1>
                <div class="header-actions">
                    <a href="/src/controllers/CabraController.php?action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la Lista
                    </a>
                </div>
            </div>
        </header>

        <!-- Mensajes de error -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form method="POST" action="/src/controllers/CabraController.php?action=store" enctype="multipart/form-data" class="form-container">
            <div class="form-grid">
                <!-- Información básica -->
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Información Básica</h3>
                    
                    <div class="form-group">
                        <label for="nombre" class="required">Nombre de la Cabra</label>
                        <input type="text" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>" 
                               required maxlength="50">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="sexo" class="required">Sexo</label>
                            <select id="sexo" name="sexo" required>
                                <option value="">Seleccionar sexo</option>
                                <option value="MACHO" <?php echo (($formData['sexo'] ?? '') === 'MACHO') ? 'selected' : ''; ?>>
                                    Macho
                                </option>
                                <option value="HEMBRA" <?php echo (($formData['sexo'] ?? '') === 'HEMBRA') ? 'selected' : ''; ?>>
                                    Hembra
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                   value="<?php echo htmlspecialchars($formData['fecha_nacimiento'] ?? ''); ?>"
                                   max="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="text" id="color" name="color" 
                                   value="<?php echo htmlspecialchars($formData['color'] ?? ''); ?>" 
                                   maxlength="30" placeholder="Ej: Blanco, Negro, Marrón">
                        </div>

                        <div class="form-group">
                            <label for="id_raza">Raza</label>
                            <select id="id_raza" name="id_raza">
                                <option value="">Seleccionar raza</option>
                                <?php foreach ($breeds as $breed): ?>
                                    <option value="<?php echo $breed['id_raza']; ?>" 
                                            <?php echo (($formData['id_raza'] ?? '') == $breed['id_raza']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($breed['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Información genealógica -->
                <div class="form-section">
                    <h3><i class="fas fa-sitemap"></i> Información Genealógica</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="madre">Madre</label>
                            <select id="madre" name="madre">
                                <option value="">Seleccionar madre</option>
                                <?php foreach ($females as $female): ?>
                                    <option value="<?php echo $female['id_cabra']; ?>" 
                                            <?php echo (($formData['madre'] ?? '') == $female['id_cabra']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($female['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="padre">Padre</label>
                            <select id="padre" name="padre">
                                <option value="">Seleccionar padre</option>
                                <?php foreach ($males as $male): ?>
                                    <option value="<?php echo $male['id_cabra']; ?>" 
                                            <?php echo (($formData['padre'] ?? '') == $male['id_cabra']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($male['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Información de propiedad -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Información de Propiedad</h3>
                    
                    <div class="form-group">
                        <label for="id_propietario_actual">Propietario Actual</label>
                        <select id="id_propietario_actual" name="id_propietario_actual">
                            <option value="">Seleccionar propietario</option>
                            <?php foreach ($owners as $owner): ?>
                                <option value="<?php echo $owner['id_propietario']; ?>" 
                                        <?php echo (($formData['id_propietario_actual'] ?? '') == $owner['id_propietario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($owner['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Foto -->
                <div class="form-section">
                    <h3><i class="fas fa-camera"></i> Fotografía</h3>
                    
                    <div class="form-group">
                        <label for="foto">Foto de la Cabra</label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="file-input">
                        <div class="file-input-help">
                            <i class="fas fa-info-circle"></i>
                            Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB
                        </div>
                    </div>

                    <div id="preview-container" class="photo-preview hidden">
                        <img id="photo-preview" src="" alt="Vista previa">
                        <button type="button" id="remove-photo" class="btn btn-sm btn-danger">
                            <i class="fas fa-times"></i> Quitar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="form-actions">
                <a href="/src/controllers/CabraController.php?action=index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Cabra
                </button>
            </div>
        </form>
    </div>

    <script>
        // Preview de la foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Quitar foto
        document.getElementById('remove-photo').addEventListener('click', function() {
            document.getElementById('foto').value = '';
            document.getElementById('preview-container').classList.add('hidden');
        });

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
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
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-grid {
        display: grid;
        gap: 30px;
    }

    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        border-left: 4px solid #007bff;
    }

    .form-section h3 {
        margin: 0 0 20px 0;
        color: #333;
        font-size: 1.1rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }

    .form-group label.required:after {
        content: ' *';
        color: #dc3545;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .file-input {
        padding: 8px !important;
    }

    .file-input-help {
        margin-top: 5px;
        font-size: 0.8rem;
        color: #666;
    }

    .photo-preview {
        margin-top: 15px;
        text-align: center;
        position: relative;
    }

    .photo-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #ddd;
        object-fit: cover;
    }

    .photo-preview button {
        margin-top: 10px;
    }

    .hidden {
        display: none;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-container {
            padding: 20px;
        }
    }
    </style>
</body>
</html>