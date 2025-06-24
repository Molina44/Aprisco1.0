<?php
// src/views/cabras/index.php
require_once __DIR__ . '/../../../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cabras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        .cabras-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px 0;
            border-bottom: 2px solid #e9ecef;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-form input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .cabras-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .cabras-table th,
        .cabras-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .cabras-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .cabras-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .cabra-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-activa {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactiva {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .pagination .current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 2em;
        }
        
        .stat-card p {
            margin: 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con navegación -->
        <header class="dashboard-header">
            <h1>Gestión de Cabras</h1>
            <nav>
                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">Perfil</a>
                <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-secondary">Cerrar Sesión</a>
            </nav>
        </header>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas rápidas -->
        <div class="stats-cards">
            <div class="stat-card">
                <h3><?php echo $total; ?></h3>
                <p>Total de Cabras</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($cabras, fn($c) => $c['sexo'] === 'HEMBRA')); ?></h3>
                <p>Hembras</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($cabras, fn($c) => $c['sexo'] === 'MACHO')); ?></h3>
                <p>Machos</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($cabras, fn($c) => $c['estado'] === 'ACTIVA')); ?></h3>
                <p>Activas</p>
            </div>
        </div>

        <!-- Controles principales -->
        <div class="cabras-header">
            <div>
                <h2>Lista de Cabras</h2>
                <p>Total: <?php echo $total; ?> cabras registradas</p>
            </div>
            <div>
                <a href="<?php echo BASE_URL; ?>/cabras/create" class="btn btn-primary">➕ Nueva Cabra</a>
                <a href="<?php echo BASE_URL; ?>/cabras/stats" class="btn btn-secondary">📊 Estadísticas</a>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <form class="search-form" method="GET" action="<?php echo BASE_URL; ?>/cabras/search">
            <input type="text" name="term" placeholder="Buscar por nombre, color, raza o propietario..." 
                   value="<?php echo isset($_GET['term']) ? htmlspecialchars($_GET['term']) : ''; ?>">
            <button type="submit" class="btn btn-primary">🔍 Buscar</button>
            <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-secondary">Limpiar</a>
        </form>

        <!-- Tabla de cabras -->
        <?php if (!empty($cabras)): ?>
            <table class="cabras-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Sexo</th>
                        <th>Raza</th>
                        <th>Color</th>
                        <th>Propietario</th>
                        <th>Fecha Nac.</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cabras as $cabra): ?>
                        <tr>
                            <td>
                                <?php if ($cabra['foto']): ?>
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo $cabra['foto']; ?>" 
                                         alt="Foto de <?php echo htmlspecialchars($cabra['nombre']); ?>" 
                                         class="cabra-photo">
                                <?php else: ?>
                                    <div class="cabra-photo" style="background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                        🐐
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($cabra['nombre']); ?></strong></td>
                            <td>
                                <span class="status-badge <?php echo $cabra['sexo'] === 'MACHO' ? 'status-activa' : 'status-inactiva'; ?>">
                                    <?php echo $cabra['sexo']; ?>
                                </span>
                            </td>
                            <td><?php echo $cabra['raza_nombre'] ?: 'Sin raza'; ?></td>
                            <td><?php echo htmlspecialchars($cabra['color']) ?: '-'; ?></td>
                            <td><?php echo $cabra['propietario_nombre'] ?: 'Sin propietario'; ?></td>
                            <td><?php echo $cabra['fecha_nacimiento'] ? date('d/m/Y', strtotime($cabra['fecha_nacimiento'])) : '-'; ?></td>
                            <td>
                                <span class="status-badge <?php echo $cabra['estado'] === 'ACTIVA' ? 'status-activa' : 'status-inactiva'; ?>">
                                    <?php echo $cabra['estado']; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>" 
                                   class="btn-small btn-primary" title="Ver detalles">👁️</a>
                                <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/edit" 
                                   class="btn-small btn-secondary" title="Editar">✏️</a>
                                <?php if ($cabra['estado'] === 'ACTIVA'): ?>
                                    <a href="<?php echo BASE_URL; ?>/cabras/<?php echo $cabra['id_cabra']; ?>/delete" 
                                       class="btn-small btn-danger" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta cabra?')">🗑️</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $currentPage - 1; ?>">« Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo BASE_URL; ?>/cabras?page=<?php echo $currentPage + 1; ?>">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 8px;">
                <h3>No hay cabras registradas</h3>
                <p>Comienza registrando tu primera cabra.</p>
                <a href="<?php echo BASE_URL; ?>/cabras/create" class="btn btn-primary">➕ Nueva Cabra</a>
            </div>      
        <?php endif; ?>
    </div>
</body>