<?php
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
}