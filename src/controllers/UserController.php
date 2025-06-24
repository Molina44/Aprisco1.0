<?php
// src/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController {
    
    // Verificar si está logueado (versión mejorada)
    private function isLoggedIn() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        $isLogoutPage = strpos($currentUrl, '/logout') !== false;
        
        // Evitar redirecciones infinitas en páginas de autenticación
        if ($isLoginPage || $isLogoutPage) {
            return false;
        }
        
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    // Mostrar formulario de editar perfil
    public function showEditProfile() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            $this->redirectToDashboard();
            return;
        }

        include __DIR__ . '/../views/user/edit_profile.php';
    }

    // Procesar actualización de perfil
    public function updateProfile() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToProfileEdit();
            return;
        }

        // Verificar token CSRF
        if (!function_exists('verifyCSRFToken') || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            $this->redirectToProfileEdit();
            return;
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            $this->redirectToDashboard();
            return;
        }

        // Actualizar datos
        $user->nombre = $_POST['nombre'] ?? '';
        $user->email = $_POST['email'] ?? '';
        $user->telefono = $_POST['telefono'] ?? '';

        // Validar datos
        $errors = $user->validateUpdate($_SESSION['user_id']);

        if (empty($errors)) {
            if ($user->update()) {
                // Actualizar datos de sesión
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_name'] = $user->nombre;

                $_SESSION['success'] = "Perfil actualizado exitosamente";
                $this->redirectToProfileEdit();
                return;
            } else {
                $errors[] = "Error al actualizar el perfil. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        $this->redirectToProfileEdit();
    }

    // Mostrar formulario de cambiar contraseña
    public function showChangePassword() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        include __DIR__ . '/../views/user/change_password.php';
    }

    // Procesar cambio de contraseña
     public function changePassword() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToChangePassword();
            return;
        }

        // Verificar token CSRF
        if (!function_exists('verifyCSRFToken') || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            $this->redirectToChangePassword();
            return;
        }

        $user = new User();
        if (!$user->getUserWithPasswordById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            $this->redirectToDashboard();
            return;
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validar cambio de contraseña
        $errors = $user->validatePasswordChange($current_password, $new_password, $confirm_password);

        if (empty($errors)) {
            if ($user->changePassword($new_password)) {
                $_SESSION['success'] = "Contraseña actualizada exitosamente";
                $this->redirectToChangePassword();
                return;
            } else {
                $errors[] = "Error al cambiar la contraseña. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        $this->redirectToChangePassword();
    }

    // Mostrar perfil del usuario
    public function showProfile() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            $this->redirectToDashboard();
            return;
        }

        include __DIR__ . '/../views/user/profile.php';
    }
    
    // Métodos de redirección mejorados
    private function redirectToLogin() {
        // Evitar bucles cuando ya estamos en la página de login
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        
        if (!$isLoginPage) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }
    
    private function redirectToDashboard() {
        header("Location: " . BASE_URL . "/dashboard");
        exit();
    }
    
    private function redirectToProfileEdit() {
        header("Location: " . BASE_URL . "/profile/edit");
        exit();
    }
    
    private function redirectToChangePassword() {
        header("Location: " . BASE_URL . "/profile/password");
        exit();
    }
}