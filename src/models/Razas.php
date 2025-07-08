<?php
class Raza {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function create($data) {
        try {
            $query = "INSERT INTO razas (nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $data['nombre']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating Raza: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM razas ORDER BY nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Razas: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM razas WHERE id_raza = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Raza by ID: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $query = "UPDATE razas SET nombre = :nombre WHERE id_raza = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $data['nombre']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Raza: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM razas WHERE id_raza = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Raza: " . $e->getMessage());
            return false;
        }
    }
}