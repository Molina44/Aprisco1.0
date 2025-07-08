<?php
// models/Propietarios.php
class Propietario {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function create($data) {
        try {
            $query = "INSERT INTO propietarios (nombre, identificacion, direccion, telefono, email) VALUES (:nombre, :identificacion, :direccion, :telefono, :email)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':identificacion', $data['identificacion']);
            $stmt->bindParam(':direccion', $data['direccion']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':email', $data['email']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating Propietario: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM propietarios ORDER BY fecha_registro DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Propietarios: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM propietarios WHERE id_propietario = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Propietario by ID: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $query = "UPDATE propietarios SET nombre = :nombre, identificacion = :identificacion, direccion = :direccion, telefono = :telefono, email = :email WHERE id_propietario = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':identificacion', $data['identificacion']);
            $stmt->bindParam(':direccion', $data['direccion']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':email', $data['email']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Propietario: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM propietarios WHERE id_propietario = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Propietario: " . $e->getMessage());
            return false;
        }
    }
}
