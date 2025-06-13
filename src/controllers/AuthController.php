<?php
// src/controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    
    // Mostrar formulario de registro
    public function showRegister() {
        if ($this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    // Procesar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/register");
            exit();
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
                header("Location: " . BASE_URL . "/login");
                exit();
            } else {
                $errors[] = "Error al registrar usuario. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: " . BASE_URL . "/register");
        exit();
    }

    // Mostrar formulario de login
    public function showLogin() {
        if ($this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email y contraseña son requeridos";
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $user = new User();
        $user->email = $email;

        if ($user->login($password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->nombre;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['login_time'] = time();

            header("Location: " . BASE_URL . "/dashboard");
            exit();
        } else {
            $_SESSION['error'] = "Email o contraseña incorrectos";
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    // Verificar si está logueado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    // Mostrar dashboard
    public function dashboard() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        include __DIR__ . '/../views/dashboard.php';
    }
}