<?php
// src/controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    
    // Mostrar formulario de registro
    public function showRegister() {
        if ($this->isLoggedIn()) {
            $this->safeRedirect(BASE_URL . "/dashboard");
            return;
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    // Procesar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->safeRedirect(BASE_URL . "/register");
            return;
        }

        $user = new User();
        $user->nombre = $_POST['nombre'] ?? '';
        $user->email = $_POST['email'] ?? '';
        $user->password = $_POST['password'] ?? '';
        $user->telefono = $_POST['telefono'] ?? '';

        // Validar datos
        $errors = $user->validateRegistration();

        if (empty($errors)) {
            if ($user->register()) {
                $_SESSION['success'] = "Usuario registrado exitosamente. Puedes iniciar sesión.";
                $this->safeRedirect(BASE_URL . "/login");
                return;
            } else {
                $errors[] = "Error al registrar usuario. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        $this->safeRedirect(BASE_URL . "/register");
    }

    // Mostrar formulario de login
    public function showLogin() {
        // Verificar si ya está autenticado pero evitando bucle
        if ($this->isLoggedIn() && !$this->isAuthPage()) {
            $this->safeRedirect(BASE_URL . "/dashboard");
            return;
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->safeRedirect(BASE_URL . "/login");
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email y contraseña son requeridos";
            $this->safeRedirect(BASE_URL . "/login");
            return;
        }

        $user = new User();
        $user->email = $email;

        if ($user->login($password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->nombre;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['login_time'] = time();

            $this->safeRedirect(BASE_URL . "/dashboard");
        } else {
            $_SESSION['error'] = "Email o contraseña incorrectos";
            $this->safeRedirect(BASE_URL . "/login");
        }
    }

    // Cerrar sesión (completamente)
    public function logout() {
        // Destruir completamente la sesión
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        session_destroy();

        // Redirigir a login con prevención de bucle
        $this->safeRedirect(BASE_URL . "/login");
    }

    // Verificar si está logueado (mejorado)
    public function isLoggedIn() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isAuthPage = $this->isAuthPage();
        
        // Evitar bucles en páginas de autenticación
        if ($isAuthPage) {
            return false;
        }
        
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    // Verificar si es página de autenticación
    private function isAuthPage() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($currentUrl, '/login') !== false || 
               strpos($currentUrl, '/register') !== false ||
               strpos($currentUrl, '/logout') !== false;
    }

    // Mostrar dashboard
    public function dashboard() {
        if (!$this->isLoggedIn()) {
            $this->safeRedirect(BASE_URL . "/login");
            return;
        }
        include __DIR__ . '/../views/dashboard.php';
    }
    
    // Redirección segura
    private function safeRedirect($url) {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isSamePage = strpos($currentUrl, parse_url($url, PHP_URL_PATH)) !== false;
        
        if (!$isSamePage) {
            header("Location: " . $url);
            exit();
        }
    }
}