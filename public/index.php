<?php
// public/index.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/CabraController.php';
require_once __DIR__ . '/../src/controllers/RazasController.php';
require_once __DIR__ . '/../src/controllers/PropietariosController.php';
require_once __DIR__ . '/../src/controllers/HistorialPropiedadController.php';
require_once __DIR__ . '/../src/controllers/PartosController.php';
require_once __DIR__ . '/../src/controllers/EventosReproductivosController.php';
require_once __DIR__ . '/../src/controllers/ControlSanitarioController.php';
require_once __DIR__ . '/../src/controllers/DocumentosCabrasController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
$userController = new UserController();
$cabraController = new CabraController();
$razasController = new RazasController();
$propietariosController = new PropietariosController();
$historialController = new HistorialPropiedadController();
$partosController = new PartosController();
$eventoController = new EventoReproductivoController();
$controlController = new ControlSanitarioController();
$documentosController = new DocumentosCabrasController();


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

        case (preg_match('#^/cabras/(\d+)/pdf$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $cabraController->generarPDF();
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

        // Partos
case (preg_match('#^/partos/(\d+)/create$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $partosController->create() : $partosController->store();
    break;

case (preg_match('#^/partos/(\d+)/edit$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $partosController->edit() : $partosController->update();
    break;

case (preg_match('#^/partos/(\d+)/delete$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $partosController->delete();
    break;

    // Eventos Reproductivos
case (preg_match('#^/eventos/(\d+)$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $eventoController->index();
    break;

case (preg_match('#^/eventos/(\d+)/create$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $eventoController->create() : $eventoController->store();
    break;

case (preg_match('#^/eventos/(\d+)/edit$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $eventoController->edit() : $eventoController->update();
    break;

case (preg_match('#^/eventos/(\d+)/delete$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $eventoController->delete();
    break;


// Controles Sanitarios
case (preg_match('#^/controles/(\d+)/create$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $controlController->create() : $controlController->store();
    break;

case (preg_match('#^/controles/(\d+)/edit$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $controlController->edit() : $controlController->update();
    break;

case (preg_match('#^/controles/(\d+)/delete$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $controlController->delete();
    break;

    case (preg_match('#^/documentos/(\d+)/create$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $method === 'GET' ? $documentosController->create() : $documentosController->store();
    break;

case (preg_match('#^/documentos/(\d+)/delete$#', $uri, $matches) ? true : false):
    $_GET['id'] = $matches[1];
    $documentosController->delete();
    break;


    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}
