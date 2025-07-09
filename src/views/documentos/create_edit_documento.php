<?php
// src/views/documentos/create_edit_documento.php
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
    <title><?php echo isset($documento) ? 'Editar Documento' : 'Subir Documento'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>

<body>
<?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
<div class="container">
    <header class="dashboard-header">
        <h1><?php echo isset($documento) ? '✏️ Editar Documento' : '📎 Subir Documento'; ?></h1>
    </header>

    <main class="main-content">
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; unset($_SESSION['errors']); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data"
              action="<?php echo isset($documento)
                  ? BASE_URL . '/documentos/' . $documento['id_documento'] . '/edit'
                  : BASE_URL . '/documentos/' . $id_cabra . '/create'; ?>"
              class="cabra-form">

            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_cabra" value="<?php echo $id_cabra; ?>">

            <div class="form-section">
                <h3>📄 Información del Documento</h3>

                <div class="form-group">
                    <label for="tipo_documento">Tipo de Documento</label>
                    <input type="text" name="tipo_documento" id="tipo_documento"
                           value="<?php echo $documento['tipo_documento'] ?? ''; ?>"
                           placeholder="Ej. Certificado Sanitario">
                </div>

                <div class="form-group">
                    <label for="ruta_archivo">Archivo <?php echo isset($documento) ? '' : '*'; ?></label>
                    <input type="file" name="ruta_archivo"
                           accept=".pdf,.jpg,.png,.doc,.docx"
                           <?php echo isset($documento) ? '' : 'required'; ?>>

                    <?php if (!empty($documento['ruta_archivo'])): ?>
                        <p>Actual:
                            <a href="<?php echo BASE_URL . '/uploads/' . $documento['ruta_archivo']; ?>" target="_blank">
                                Ver archivo
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Guardar</button>
                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $id_cabra; ?>" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
