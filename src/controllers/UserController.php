<?php
// src/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController {
    
    // Verificar si está logueado
    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    // Mostrar formulario de editar perfil
    public function showEditProfile() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }

        include __DIR__ . '/../views/user/edit_profile.php';
    }

    // Procesar actualización de perfil
    public function updateProfile() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/profile/edit");
            exit();
        }

        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            header("Location: " . BASE_URL . "/profile/edit");
            exit();
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }

        // Actualizar datos
        $user->nombre = $_POST['nombre'] ?? '';
        $user->email = $_POST['email'] ?? '';
        $user->telefono = $_POST['telefono'] ?? '';

        // Validar datos
        $errors = $user->validateUpdate($_SESSION['user_id']);

        if (empty($errors)) {
            if ($user->update()) {
                // Actualizar datos de sesión si el email cambió
                if ($_SESSION['user_email'] !== $user->email) {
                    $_SESSION['user_email'] = $user->email;
                }
                if ($_SESSION['user_name'] !== $user->nombre) {
                    $_SESSION['user_name'] = $user->nombre;
                }

                $_SESSION['success'] = "Perfil actualizado exitosamente";
                header("Location: " . BASE_URL . "/profile/edit");
                exit();
            } else {
                $errors[] = "Error al actualizar el perfil. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: " . BASE_URL . "/profile/edit");
        exit();
    }

    // Mostrar formulario de cambiar contraseña
    public function showChangePassword() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        include __DIR__ . '/../views/user/change_password.php';
    }

    // Procesar cambio de contraseña
     public function changePassword() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/profile/password");
            exit();
        }

        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            header("Location: " . BASE_URL . "/profile/password");
            exit();
        }

        $user = new User();
        // USAR EL MÉTODO CORREGIDO
        if (!$user->getUserWithPasswordById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validar cambio de contraseña
        $errors = $user->validatePasswordChange($current_password, $new_password, $confirm_password);

        if (empty($errors)) {
            if ($user->changePassword($new_password)) {
                $_SESSION['success'] = "Contraseña actualizada exitosamente";
                header("Location: " . BASE_URL . "/profile/password");
                exit();
            } else {
                $errors[] = "Error al cambiar la contraseña. Intenta nuevamente.";
            }
        }

        $_SESSION['errors'] = $errors;
        header("Location: " . BASE_URL . "/profile/password");
        exit();
    }

    // Mostrar perfil del usuario
    public function showProfile() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $user = new User();
        if (!$user->getUserById($_SESSION['user_id'])) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }

        include __DIR__ . '/../views/user/profile.php';
    }
}