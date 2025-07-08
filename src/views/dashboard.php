<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php  BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Botón de menú móvil -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Incluir sidebar -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <header class="dashboard-header">
                <h1>Bienvenido, <?php echo e($_SESSION['user_name']); ?>!</h1>
                <div class="header-actions">
                    <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">Perfil</a>
                </div>
            </header>

            <main class="dashboard-content">
                <div class="user-info">
                    <h2>Información de tu cuenta</h2>
                    <p><strong>Nombre:</strong> <?php echo e($_SESSION['user_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo e($_SESSION['user_email']); ?></p>
                    <p><strong>Última sesión:</strong> <?php echo date('d/m/Y H:i:s', $_SESSION['login_time']); ?></p>
                </div>

                <div class="dashboard-actions">
                    <h2>Acciones disponibles</h2>
                    <div class="action-cards">
                        <div class="card">
                            <h3>Perfil</h3>
                            <p>Actualiza tu información personal</p>
                            <a href="<?php echo BASE_URL; ?>/profile/edit" class="btn btn-primary">
                                📝 Editar Perfil
                            </a>
                        </div> 
                        <div class="card">
                            <h3>Cabras</h3>
                            <p>información Cabras</p>
                            <a href="<?php echo BASE_URL; ?>/cabras" class="btn btn-primary">
                                📝 Ver Cabras
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar en móviles
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('mainSidebar').classList.toggle('active');
        });
        
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('mainSidebar').classList.remove('active');
        });
        
        // Activar submenús
        const menuItems = document.querySelectorAll('.sidebar-nav > ul > li');
        menuItems.forEach(item => {
            if (item.classList.contains('active')) {
                const submenu = item.querySelector('.submenu');
                if (submenu) {
                    submenu.style.display = 'block';
                }
            }
            
            item.addEventListener('click', function(e) {
                if (this.querySelector('.submenu')) {
                    e.preventDefault();
                    this.classList.toggle('active');
                    const submenu = this.querySelector('.submenu');
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                }
            });
        });
    </script>
</body>
</html>