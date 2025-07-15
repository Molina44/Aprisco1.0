<!-- src/views/includes/sidebar.php -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

<!-- Botón hamburguesa para móvil -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
</button>

<!-- Overlay para cerrar en móvil -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="mainSidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Logo" class="logo-image">
            </div>
        </div>
        <!-- Botón cerrar para móvil -->
        <button class="mobile-close-btn" id="mobileCloseBtn">
            <i class="fas fa-times"></i>
        </button>
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
                    <a href="<?php echo BASE_URL; ?>/cabras" class="nav-link submenu-toggle">
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
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/cabras') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cabras" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span class="submenu-text">Cabras</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/cabras/create') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/cabras/create" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="submenu-text">Nueva Cabra</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menú Razas COMPLETO -->
                <li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/razas') !== false) ? 'active expanded' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/razas" class="nav-link submenu-toggle">
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
                        <li class="submenu-item <?php echo (preg_match('#/razas(/[0-9]+)?$#', $_SERVER['REQUEST_URI'])) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/razas" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                                <span class="submenu-text">Listado</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/razas/create') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/razas/create" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="submenu-text">Nueva Raza</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menú propietarios COMPLETO -->
                <li class="nav-item has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios') !== false) ? 'active expanded' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/propietarios" class="nav-link submenu-toggle">
                        <div class="nav-icon"><i class="fas fa-user-friends"></i></div>
                        <span class="nav-text">Propietarios</span>
                        <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
                        <div class="nav-indicator"></div>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios') !== false && !strpos($_SERVER['REQUEST_URI'], '/create')) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/propietarios" class="submenu-link">
                                <div class="submenu-icon"><i class="fas fa-list-ul"></i></div>
                                <span class="submenu-text">Listado</span>
                            </a>
                        </li>
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/propietarios/create') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/propietarios/create" class="submenu-link">
                                <div class="submenu-icon"><i class="fas fa-plus"></i></div>
                                <span class="submenu-text">Nuevo Propietario</span>
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
                    <a href="<?php echo BASE_URL; ?>/profile" class="nav-link submenu-toggle">
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
                        <li class="submenu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo BASE_URL; ?>/profile" class="submenu-link">
                                <div class="submenu-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <span class="submenu-text">Perfil</span>
                            </a>
                        </li>
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
/* Variables de colores */
:root {
  --dark: #260F01;
  --brown: #583619;
  --tan: #AC815B;
  --beige: #DBB593;
  --cream: #EADFD5;
  --light: #F2F2F2;
  --black: #000000;
  --white: #FFFFFF;
  --gray: #666666;
  --light-gray: #999999;
}

/* Estilos base del logo */
.logo-image {
    width: 240px;
    height: 240px;
    object-fit: contain;
    border-radius: 8px;
}

/* Estilos para el botón hamburguesa */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: var(--cream);
    border: 2px solid var(--tan);
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 4px 15px rgba(38, 15, 1, 0.15);
    cursor: pointer;
    flex-direction: column;
    width: 50px;
    height: 50px;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
    background: var(--beige);
    border-color: var(--brown);
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(38, 15, 1, 0.2);
}

.hamburger-line {
    width: 20px;
    height: 2px;
    background: var(--dark);
    margin: 2px 0;
    transition: all 0.3s ease;
    border-radius: 1px;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.mobile-menu-toggle.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Botón cerrar en móvil */
.mobile-close-btn {
    display: none;
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    color: var(--gray);
    font-size: 24px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.mobile-close-btn:hover {
    background: var(--cream);
    color: var(--dark);
}

/* Overlay para móvil */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(38, 15, 1, 0.6);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Estilos responsive */
@media (max-width: 768px) {
    /* Mostrar botón hamburguesa */
    .mobile-menu-toggle {
        display: flex;
    }
    
    /* Mostrar botón cerrar */
    .mobile-close-btn {
        display: block;
    }
    
    /* Mostrar overlay */
    .sidebar-overlay {
        display: block;
    }
    
    /* Sidebar móvil */
    .sidebar {
        position: fixed !important;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s ease;
        overflow-y: auto;
        box-shadow: 4px 0 20px rgba(38, 15, 1, 0.15);
        background: var(--white);
        border-right: 3px solid var(--tan);
        transform: translateZ(0);
        -webkit-transform: translateZ(0);
    }
    
    .sidebar.active {
        left: 0 !important;
    }
    
    /* Ajustar header del sidebar */
    .sidebar-header {
        display: flex;
        justify-content: center;
        position: center;
        padding: 20px;
        border-bottom: 2px solid var(--cream);
        background: linear-gradient(135deg, var(--cream) 0%, var(--beige) 100%);
    }
    
    /* Ajustar logo para móvil */
    .logo-image {
        width: 200px !important;
        height: 200px !important;
        border: 2px solid var(--tan);
        border-radius: 12px;
    }
    
    /* Ajustar navegación */
    .sidebar-nav {
    
        padding: 20px 0;
        background: var(--white);
    }
    
    .nav-section {
        margin-bottom: 30px;
    }
    
    .nav-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--brown);
        margin-bottom: 15px;
        padding: 0 20px;
        letter-spacing: 1px;
    }
    
    .nav-item {
        margin-bottom: 5px;
    }
    
    .nav-link {
        padding: 15px 20px;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--dark);
        position: relative;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .nav-link:hover {
        background: var(--cream);
        color: var(--brown);
        border-left-color: var(--tan);
    }
    
    .nav-icon {
        width: 20px;
        margin-right: 15px;
        text-align: center;
        color: var(--tan);
    }
    
    .nav-text {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
    }
    
    .nav-arrow {
        width: 20px;
        text-align: center;
        transition: transform 0.3s ease;
        color: var(--gray);
    }
    
    /* Submenús */
    .submenu {
        display: none;
        background: var(--light);
        padding: 8px 0;
        margin: 5px 0;
        border-left: 4px solid var(--beige);
        border-radius: 0 8px 8px 0;
    }
    
    .submenu.active {
        display: block;
    }
    
    .submenu-link {
        padding: 12px 20px 12px 52px;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--gray);
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .submenu-link:hover {
        background: var(--cream);
        color: var(--dark);
        border-left-color: var(--tan);
    }
    
    .submenu-icon {
        width: 16px;
        margin-right: 10px;
        text-align: center;
        color: var(--tan);
    }
    
    /* Estados activos */
    .nav-item.active > .nav-link {
        background: linear-gradient(135deg, var(--brown) 0%, var(--dark) 100%);
        color: var(--white);
        border-left-color: var(--tan);
    }
    
    .nav-item.active .nav-icon {
        color: var(--beige);
    }
    
    .nav-item.active .nav-arrow {
        transform: rotate(90deg);
        color: var(--beige);
    }
    
    .submenu-item.active .submenu-link {
        background: linear-gradient(135deg, var(--tan) 0%, var(--brown) 100%);
        color: var(--white);
        border-left-color: var(--cream);
    }
    
    .submenu-item.active .submenu-icon {
        color: var(--cream);
    }
    
    /* Footer del sidebar */
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, var(--cream) 0%, var(--beige) 100%);
        border-top: 2px solid var(--tan);
        padding: 20px;
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--dark);
        padding: 12px 0;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border-radius: 8px;
        padding-left: 10px;
    }
    
    .logout-btn:hover {
        background: var(--white);
        color: var(--brown);
        transform: translateX(5px);
    }
    
    .logout-icon {
        width: 20px;
        margin-right: 10px;
        text-align: center;
        color: var(--brown);
    }
    
    .footer-info {
        margin-top: 15px;
        text-align: center;
        background: var(--white);
        padding: 10px;
        border-radius: 8px;
        border: 1px solid var(--tan);
    }
    
    .copyright,
    .version {
        font-size: 11px;
        color: var(--gray);
        margin: 2px 0;
        font-weight: 500;
    }
    
    .version {
        color: var(--brown);
        font-weight: 600;
    }
}

/* Ajustes para pantallas muy pequeñas */
@media (max-width: 480px) {
    .sidebar {
        width: 100%;
        left: -100%;
        border-right: none;
        border-bottom: 3px solid var(--tan);
    }
    
    .mobile-menu-toggle {
        top: 15px;
        left: 15px;
        width: 45px;
        height: 45px;
        background: var(--beige);
        border-color: var(--brown);
    }
    
    .mobile-menu-toggle:hover {
        background: var(--tan);
    }
    
    .hamburger-line {
        width: 18px;
        background: var(--dark);
    }
    
    .sidebar-header {
        text-align: center;
        background: linear-gradient(135deg, var(--beige) 0%, var(--tan) 100%);
    }
    
    .logo-image {
        width: 70px;
        height: 70px;
    }
}

/* Animaciones adicionales */
.sidebar {
    will-change: transform;
    backface-visibility: hidden;
}

.sidebar-overlay {
    will-change: opacity;
    backface-visibility: hidden;
}


</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    // Verificar que los elementos existen
    if (!mobileMenuToggle || !sidebar || !overlay) {
        console.error('Elementos del sidebar no encontrados');
        return;
    }
    
    // Función para abrir sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        mobileMenuToggle.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Función para cerrar sidebar
    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Event listeners
    mobileMenuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (sidebar.classList.contains('active')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });
    
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        });
    }
    
    overlay.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeSidebar();
    });
    
    // Manejar submenús
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation();
                
                const parent = this.parentElement;
                const submenu = parent.querySelector('.submenu');
                
                if (submenu) {
                    parent.classList.toggle('expanded');
                    submenu.classList.toggle('active');
                }
            }
        });
    });
    
    // Cerrar sidebar al hacer clic en un enlace (móvil)
    const navLinks = document.querySelectorAll('.nav-link:not(.submenu-toggle), .submenu-link');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(closeSidebar, 100);
            }
        });
    });
    
    // Manejar redimensionamiento de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
    
    // Prevenir cierre accidental
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>