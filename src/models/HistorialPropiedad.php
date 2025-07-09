<?php
// src/models/HistorialPropiedad.php
class HistorialPropiedad {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $sql = "SELECT h.*, p.nombre as nombre_propietario
                FROM historial_propiedad h
                LEFT JOIN propietarios p ON h.id_propietario = p.id_propietario
                ORDER BY h.fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT h.*, p.nombre as nombre_propietario
                FROM historial_propiedad h
                LEFT JOIN propietarios p ON h.id_propietario = p.id_propietario
                WHERE id_historial = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByCabra($id_cabra) {
    $sql = "SELECT h.*, p.nombre as nombre_propietario
            FROM historial_propiedad h
            LEFT JOIN propietarios p ON h.id_propietario = p.id_propietario
            WHERE h.id_cabra = :id_cabra
            ORDER BY h.fecha_inicio DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function create($data) {
        $sql = "INSERT INTO historial_propiedad (id_cabra, id_propietario, fecha_inicio, fecha_fin, motivo_cambio, precio_transaccion)
                VALUES (:id_cabra, :id_propietario, :fecha_inicio, :fecha_fin, :motivo_cambio, :precio_transaccion)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cabra', $data['id_cabra']);
        $stmt->bindParam(':id_propietario', $data['id_propietario']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':motivo_cambio', $data['motivo_cambio']);
        $stmt->bindParam(':precio_transaccion', $data['precio_transaccion']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $sql = "UPDATE historial_propiedad SET
                    id_cabra = :id_cabra,
                    id_propietario = :id_propietario,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    motivo_cambio = :motivo_cambio,
                    precio_transaccion = :precio_transaccion
                WHERE id_historial = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_cabra', $data['id_cabra']);
        $stmt->bindParam(':id_propietario', $data['id_propietario']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':motivo_cambio', $data['motivo_cambio']);
        $stmt->bindParam(':precio_transaccion', $data['precio_transaccion']);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM historial_propiedad WHERE id_historial = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
