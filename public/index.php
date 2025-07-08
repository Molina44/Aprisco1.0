
<?php

// public/index.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/CabraController.php';
require_once __DIR__ . '/../src/controllers/RazasController.php';

// Obtener URI y método HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Router simple
$authController = new AuthController();
$userController = new UserController();
$cabraController = new CabraController();
$razasController = new RazaController();

switch ($uri) {
    case '':
    case '/':
        if ($authController->isLoggedIn()) {
            header("Location: " . BASE_URL . "/dashboard");
        } else {
            header("Location: " . BASE_URL . "/login");
        }
        break;

    case '/register':
        if ($method === 'GET') {
            $authController->showRegister();
        } elseif ($method === 'POST') {
            $authController->register();
        }
        break;

    case '/login':
        if ($method === 'GET') {
            $authController->showLogin();
        } elseif ($method === 'POST') {
            $authController->login();
        }
        break;

    case '/logout':
        $authController->logout();
        break;

    case '/dashboard':
        $authController->dashboard();
        break;

    case '/profile':
        $userController->showProfile();
        break;

    case '/profile/edit':
        if ($method === 'GET') {
            $userController->showEditProfile();
        } elseif ($method === 'POST') {
            $userController->updateProfile();
        }
        break;

    case '/profile/password':
        if ($method === 'GET') {
            $userController->showChangePassword();
        } elseif ($method === 'POST') {
            $userController->changePassword();
        }
        break;

    // Rutas para cabras
    case '/cabras':
        $cabraController->index();
        break;

    case '/cabras/create':
        if ($method === 'GET') {
            $cabraController->create();
        } elseif ($method === 'POST') {
            $cabraController->store();
        }
        break;

    case (preg_match('/^\/cabras\/(\d+)$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $cabraController->show();
        break;

    case (preg_match('/^\/cabras\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        if ($method === 'GET') {
            $cabraController->edit();
        } elseif ($method === 'POST') {
            $cabraController->update();
        }
        break;

    case (preg_match('/^\/cabras\/(\d+)\/delete$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $cabraController->delete();
        break;

    case '/cabras/search':
        $cabraController->search();
        break;

    case '/cabras/stats':
        $cabraController->stats();
        break;
  case '/razas':
        $razasController->index();
        break;

    case '/razas/create':
        if ($method === 'GET') {
            $razasController->create();
        } elseif ($method === 'POST') {
            $razasController->store();
        }
        break;

    case (preg_match('/^\/razas\/(\d+)$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $razasController->show();
        break;

    case (preg_match('/^\/razas\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        if ($method === 'GET') {
            $razasController->edit();
        } elseif ($method === 'POST') {
            $razasController->update();
        }
        break;

    case (preg_match('/^\/razas\/(\d+)\/delete$/', $uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        $razasController->delete();
        break;

    case '/razas/search':
        $razasController->search();
        break;

    case '/razas/stats':
        $razasController->stats();
        break;

    default:
        http_response_code(404);
        echo '<h1>404 - Página no encontrada</h1>';
        break;
}