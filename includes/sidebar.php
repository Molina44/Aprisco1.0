<!-- src/views/includes/sidebar.php -->
<aside class="sidebar" id="mainSidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Logo" class="logo-image">

            </div>

        </div>
    </div>


    <nav class="sidebar-nav">
        <div class="nav-section">
            <h4 class="nav-title">Principal</h4>
            <ul class="nav-list">
                <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h4 class="nav-title">Gestión</h4>
            <ul class="nav-list">
                <li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/cabras') !== false) ? 'active expanded' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/cabras" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <span class="nav-text">Cabras</span>
                        <div class="nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-indicator"></div>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/cabras/create') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cabras/create" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="submenu-text">Nueva Cabra</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/cabras') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cabras" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span class="submenu-text">cabras</span>
                            </a>
                        </li>
                      
                    </ul>
                </li>


              <!-- Menú Razas COMPLETO -->
<li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/razas') !== false) ? 'active expanded' : ''; ?>">
    <a href="<?php echo BASE_URL; ?>/razas" class="nav-link">
        <div class="nav-icon">
            <i class="fas fa-paw"></i>
        </div>
        <span class="nav-text">Razas</span>
        <div class="nav-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
        <div class="nav-indicator"></div>
    </a>
    <ul class="submenu">
        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/razas/create') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/razas/create" class="submenu-link">
                <div class="submenu-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <span class="submenu-text">Nueva Raza</span>
            </a>
        </li>
        <li class="submenu-item <?php echo (preg_match('#/razas(/[0-9]+)?$#', $_SERVER['REQUEST_URI'])) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/razas" class="submenu-link">
                <div class="submenu-icon">
                    <i class="fas fa-list-ul"></i>
                </div>
                <span class="submenu-text">Listado</span>
            </a>
        </li>
    </ul>
</li>
    <!-- Menú propietarios COMPLETO -->
<li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios') !== false) ? 'active expanded' : ''; ?>">
    <a href="<?php echo BASE_URL; ?>/propietarios" class="nav-link">
        <div class="nav-icon"><i class="fas fa-user-friends"></i></div>
        <span class="nav-text">Propietarios</span>
        <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
        <div class="nav-indicator"></div>
    </a>
    <ul class="submenu">
        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios/create') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/propietarios/create" class="submenu-link">
                <div class="submenu-icon"><i class="fas fa-plus"></i></div>
                <span class="submenu-text">Nuevo Propietario</span>
            </a>
        </li>
        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios') !== false && !strpos($_SERVER['REQUEST_URI'], '/create')) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/propietarios" class="submenu-link">
                <div class="submenu-icon"><i class="fas fa-list-ul"></i></div>
                <span class="submenu-text">Listado</span>
            </a>
        </li>
    </ul>
</li>


          
            </ul>
        </div>

        <div class="nav-section">
            <h4 class="nav-title">Cuenta</h4>
            <ul class="nav-list">
                <li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile') !== false) ? 'active expanded' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/profile" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="nav-text">Perfil</span>
                        <div class="nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="nav-indicator"></div>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/edit') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/profile/edit" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <span class="submenu-text">Editar Perfil</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/password') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/profile/password" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <span class="submenu-text">Seguridad</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/profile" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <span class="submenu-text">perfil</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="logout-section">
            <a href="<?php echo BASE_URL; ?>/logout" class="logout-btn">
                <div class="logout-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="logout-text">Cerrar Sesión</span>
            </a>
        </div>
        <div class="footer-info">
            <p class="copyright">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?></p>
            <p class="version">v2.1.0</p>
        </div>
    </div>
</aside>

<style>
    .logo-image {
        width: 240px;
        /* Puedes ajustar esto según el tamaño ideal */
        height: 240px;
        /* O usa 'auto' para mantener proporción */
        object-fit: contain;
        border-radius: 8px;
        /* Opcional, por estética */
    }

    /* Modern Sidebar Styles */
    .sidebar {
        width: 280px;
        height: 100vh;
        background: #260F01;
        border-right: 1px solid rgba(168, 106, 46, 0.2);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 25px -5px rgba(50, 34, 18, 0.15), 0 10px 10px -5px rgba(50, 34, 18, 0.08);
    }

    /* Header */
    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(244, 241, 225, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 240px;
        height: 240px;
        background: #F2F2F2;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #322212;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(242, 197, 95, 0.3);
    }

    .site-title {
        color: #F4F1E1;
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.025em;
    }


    /* User Card */
    .user-card {
        padding: 20px;
        margin: 0 16px 24px;
        background: rgba(244, 241, 225, 0.1);
        border: 1px solid rgba(244, 241, 225, 0.15);
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(10px);
    }


    .action-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: rgba(244, 241, 225, 0.15);
        color: rgba(244, 241, 225, 0.8);
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #F2C55F;
        color: #322212;
    }

    /* Navigation */
    .sidebar-nav {
        flex: 1;
        padding: 0 16px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(244, 241, 225, 0.3) transparent;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(244, 241, 225, 0.3);
        border-radius: 2px;
    }

    .nav-section {
        margin-bottom: 32px;
    }

    .nav-title {
        color: rgba(244, 241, 225, 0.6);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 12px 16px;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin-bottom: 4px;
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: rgba(244, 241, 225, 0.8);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover {
        background: rgba(244, 241, 225, 0.1);
        color: #F2C55F;
        transform: translateX(4px);
    }

    .nav-item.active .nav-link {
        background: rgba(242, 197, 95, 0.15);
        color: #F2C55F;
        border: 1px solid rgba(242, 197, 95, 0.3);
        box-shadow: 0 2px 8px rgba(242, 197, 95, 0.2);
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 16px;
    }

    .nav-text {
        flex: 1;
        font-weight: 500;
        font-size: 14px;
    }

    .nav-arrow {
        margin-left: auto;
        transition: transform 0.2s ease;
        font-size: 12px;
        opacity: 0.7;
    }

    .nav-item.expanded .nav-arrow {
        transform: rotate(90deg);
    }

    .nav-indicator {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: linear-gradient(135deg, #F2C55F, #885926);
        border-radius: 0 2px 2px 0;
        transition: height 0.2s ease;
    }

    .nav-item.active .nav-indicator {
        height: 20px;
    }

    /* Submenu */
    .submenu {
        list-style: none;
        padding: 0;
        margin: 8px 0 0 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-item.expanded .submenu {
        max-height: 200px;
    }

    .submenu-item {
        margin-bottom: 2px;
    }

    .submenu-link {
        display: flex;
        align-items: center;
        padding: 8px 16px 8px 48px;
        color: rgba(244, 241, 225, 0.7);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 13px;
    }

    .submenu-link:hover {
        background: rgba(244, 241, 225, 0.08);
        color: #F2C55F;
        transform: translateX(4px);
    }

    .submenu-item.active .submenu-link {
        background: rgba(242, 197, 95, 0.1);
        color: #F2C55F;
    }

    .submenu-icon {
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-size: 12px;
    }

    .submenu-text {
        font-weight: 500;
    }

    /* Footer */
    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(244, 241, 225, 0.15);
        margin-top: auto;
    }

    .logout-section {
        margin-bottom: 16px;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: rgba(244, 241, 225, 0.8);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.2s ease;
        border: 1px solid rgba(168, 106, 46, 0.3);
        background: rgba(168, 106, 46, 0.1);
    }

    .logout-btn:hover {
        background: #885926;
        color: #F4F1E1;
        border-color: #885926;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(136, 89, 38, 0.3);
    }

    .logout-icon {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 14px;
    }

    .logout-text {
        font-weight: 500;
        font-size: 14px;
    }

    .footer-info {
        text-align: center;
    }

    .copyright {
        color: rgba(244, 241, 225, 0.5);
        font-size: 11px;
        margin: 0 0 4px 0;
    }

    .version {
        color: rgba(244, 241, 225, 0.4);
        font-size: 10px;
        margin: 0;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }
    }

    /* Animation for submenu toggle */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .nav-item.expanded .submenu {
        animation: slideDown 0.3s ease;
    }
</style>

<script>
    // JavaScript para funcionalidad del sidebar
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle submenu functionality
        const navItems = document.querySelectorAll('.nav-item.has-submenu');

        navItems.forEach(item => {
            const link = item.querySelector('.nav-link');

            link.addEventListener('click', function(e) {
                // Solo prevenir el comportamiento por defecto si no es el enlace activo
                if (!item.classList.contains('active')) {
                    e.preventDefault();
                }

                // Toggle expanded state
                item.classList.toggle('expanded');

                // Close other expanded items
                navItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('expanded');
                    }
                });
            });
        });

        // Auto-expand active menu items
        const activeItems = document.querySelectorAll('.nav-item.active.has-submenu');
        activeItems.forEach(item => {
            item.classList.add('expanded');
        });
    });
</script>