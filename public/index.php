<?php
// public/index.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/CabraController.php';
require_once __DIR__ . '/../src/controllers/RazasController.php';
require_once __DIR__ . '/../src/controllers/PropietariosController.php';
require_once __DIR__ . '/../src/controllers/HistorialPropiedadController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
$userController = new UserController();
$cabraController = new CabraController();
$razasController = new RazasController();
$propietariosController = new PropietariosController();
$historialController = new HistorialPropiedadController();

switch ($uri) {
    case '':
    case '/':
        if ($authController->isLoggedIn()) {
            header("Location: " . BASE_URL . "/dashboard");
        } else {
            header("Location: " . BASE_URL . "/login");
        }
        break;

    // Auth
    case '/register':
        $method === 'GET' ? $authController->showRegister() : $authController->register();
        break;

    case '/login':
        $method === 'GET' ? $authController->showLogin() : $authController->login();
        break;

    case '/logout':
        $authController->logout();
        break;

    // Dashboard
    case '/dashboard':
        $authController->dashboard();
        break;

    // Perfil
    case '/profile':
        $userController->showProfile();
        break;

    case '/profile/edit':
        $method === 'GET' ? $userController->showEditProfile() : $userController->updateProfile();
        break;

    case '/profile/password':
        $method === 'GET' ? $userController->showChangePassword() : $userController->changePassword();
        break;

    // Cabras
    case '/cabras':
        $cabraController->index();
        break;

    case '/cabras/create':
        $method === 'GET' ? $cabraController->create() : $cabraController->store();
        break;

    case (preg_match('#^/cabras/(\d+)$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $cabraController->show();
        break;

    case (preg_match('#^/cabras/(\d+)/edit$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $method === 'GET' ? $cabraController->edit() : $cabraController->update();
        break;

    case (preg_match('#^/cabras/(\d+)/delete$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $cabraController->delete();
        break;

    case '/cabras/search':
        $cabraController->search();
        break;

    case '/cabras/stats':
        $cabraController->stats();
        break;

    // Razas
    case '/razas':
        $razasController->index();
        break;

    case '/razas/create':
        $method === 'GET' ? $razasController->create() : $razasController->store();
        break;

    case (preg_match('#^/razas/(\d+)$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $razasController->show();
        break;

    case (preg_match('#^/razas/(\d+)/edit$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $method === 'GET' ? $razasController->edit() : $razasController->update();
        break;

    case (preg_match('#^/razas/(\d+)/delete$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $razasController->delete();
        break;

    // Propietarios
    case '/propietarios':
        $propietariosController->index();
        break;

    case '/propietarios/create':
        $method === 'GET' ? $propietariosController->create() : $propietariosController->store();
        break;

    case (preg_match('#^/propietarios/(\d+)$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $propietariosController->show();
        break;

    case (preg_match('#^/propietarios/(\d+)/edit$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $method === 'GET' ? $propietariosController->edit() : $propietariosController->update();
        break;

    case (preg_match('#^/propietarios/(\d+)/delete$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $propietariosController->delete();
        break;

    // Historial de Propiedad
    case (preg_match('#^/historial/(\d+)$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $historialController->index();
        break;

    case (preg_match('#^/historial/(\d+)/create$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $method === 'GET' ? $historialController->create() : $historialController->store();
        break;

    case (preg_match('#^/historial/(\d+)/edit$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $method === 'GET' ? $historialController->edit() : $historialController->update();
        break;

    case (preg_match('#^/historial/(\d+)/delete$#', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $historialController->delete();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}
