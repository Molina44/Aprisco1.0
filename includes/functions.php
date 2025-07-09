<?php

// Mostrar mensajes de sesión
function showMessages() {
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    
    if (isset($_SESSION['errors'])) {
        echo '<div class="alert alert-error">';
        foreach ($_SESSION['errors'] as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
        unset($_SESSION['errors']);
    }
}

// Obtener valor del formulario anterior
function getFormValue($field, $default = '') {
    if (isset($_SESSION['form_data'][$field])) {
        $value = $_SESSION['form_data'][$field];
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $default;
}

// Limpiar datos de formulario de sesión
function clearFormData() {
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
}

// Generar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verificar token CSRF
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    
    if ($_SESSION['csrf_token'] !== $token) {
        return false;
    }
    
    // Regenerar token después de verificar
    unset($_SESSION['csrf_token']);
    return true;
}

// Escapar output HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}


