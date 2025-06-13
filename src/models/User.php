<?php
// src/models/User.php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $telefono;
    public $fecha_registro;
    public $fecha_ultimo_acceso;
    public $activo;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Registrar nuevo usuario
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, email, password, telefono) 
                  VALUES (:nombre, :email, :password, :telefono)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Encriptar contraseña
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":telefono", $this->telefono);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verificar si el email ya existe
    public function emailExists() {
        $query = "SELECT id, nombre, email, password, activo FROM " . $this->table_name . " 
                  WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->activo = $row['activo'];
            return true;
        }
        return false;
    }

    // Iniciar sesión
    public function login($password) {
        if ($this->emailExists() && $this->activo) {
            if (password_verify($password, $this->password)) {
                $this->updateLastAccess();
                return true;
            }
        }
        return false;
    }

    // Actualizar último acceso
    private function updateLastAccess() {
        $query = "UPDATE " . $this->table_name . " 
                  SET fecha_ultimo_acceso = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
    }

    // Obtener usuario por ID
    public function getUserById($id) {
        $query = "SELECT id, nombre, email, telefono, fecha_registro, fecha_ultimo_acceso, activo 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->fecha_ultimo_acceso = $row['fecha_ultimo_acceso'];
            $this->activo = $row['activo'];
            return true;
        }
        return false;
    }

    // Actualizar información del usuario
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, email = :email, telefono = :telefono 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Bind parameters
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cambiar contraseña
    public function changePassword($new_password) {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Encriptar nueva contraseña
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verificar si email existe para otro usuario (para edición)
    public function emailExistsForOtherUser($exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE email = :email";
        
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }
        
        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Validar datos de registro
    public function validateRegistration() {
        $errors = [];

        if (empty($this->nombre) || strlen($this->nombre) < 2) {
            $errors[] = "El nombre debe tener al menos 2 caracteres";
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }

        if (empty($this->password) || strlen($this->password) < PASSWORD_MIN_LENGTH) {
            $errors[] = "La contraseña debe tener al menos " . PASSWORD_MIN_LENGTH . " caracteres";
        }

        if ($this->emailExists()) {
            $errors[] = "El email ya está registrado";
        }

        return $errors;
    }

    // Validar datos de actualización
    public function validateUpdate($exclude_id = null) {
        $errors = [];

        if (empty($this->nombre) || strlen($this->nombre) < 2) {
            $errors[] = "El nombre debe tener al menos 2 caracteres";
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }

        if ($this->emailExistsForOtherUser($exclude_id)) {
            $errors[] = "El email ya está siendo usado por otro usuario";
        }

        return $errors;
    }

    // Validar cambio de contraseña
    public function validatePasswordChange($current_password, $new_password, $confirm_password) {
        $errors = [];

        // Verificar contraseña actual
        if (empty($current_password)) {
            $errors[] = "La contraseña actual es requerida";
        } elseif (!password_verify($current_password, $this->password)) {
            $errors[] = "La contraseña actual es incorrecta";
        }

        // Verificar nueva contraseña
        if (empty($new_password) || strlen($new_password) < PASSWORD_MIN_LENGTH) {
            $errors[] = "La nueva contraseña debe tener al menos " . PASSWORD_MIN_LENGTH . " caracteres";
        }

        // Verificar confirmación
        if ($new_password !== $confirm_password) {
            $errors[] = "Las contraseñas no coinciden";
        }

        return $errors;
    }
}