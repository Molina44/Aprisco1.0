<?php
// src/models/Parto.php
class Parto {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function getPadresDisponibles() {
    $sql = "SELECT id_cabra, nombre FROM cabras WHERE sexo = 'MACHO' AND estado = 'ACTIVA'";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getByCabra($id_madre) {
        $sql = "SELECT p.*, c.nombre AS nombre_padre, u.nombre AS nombre_usuario
                FROM partos p
                LEFT JOIN cabras c ON p.id_padre = c.id_cabra
                LEFT JOIN usuarios u ON p.registrado_por = u.id
                WHERE p.id_madre = :id_madre
                ORDER BY fecha_parto DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_madre', $id_madre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_parto) {
        $sql = "SELECT * FROM partos WHERE id_parto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_parto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO partos (id_madre, id_padre, fecha_parto, numero_crias, peso_total_crias, tipo_parto, dificultad, observaciones, registrado_por)
                VALUES (:id_madre, :id_padre, :fecha_parto, :numero_crias, :peso_total_crias, :tipo_parto, :dificultad, :observaciones, :registrado_por)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_madre' => $data['id_madre'],
            ':id_padre' => $data['id_padre'],
            ':fecha_parto' => $data['fecha_parto'],
            ':numero_crias' => $data['numero_crias'],
            ':peso_total_crias' => $data['peso_total_crias'],
            ':tipo_parto' => $data['tipo_parto'],
            ':dificultad' => $data['dificultad'],
            ':observaciones' => $data['observaciones'],
            ':registrado_por' => $data['registrado_por'],
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE partos SET id_madre = :id_madre, id_padre = :id_padre, fecha_parto = :fecha_parto,
                numero_crias = :numero_crias, peso_total_crias = :peso_total_crias, tipo_parto = :tipo_parto,
                dificultad = :dificultad, observaciones = :observaciones
                WHERE id_parto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_madre', $data['id_madre']);
        $stmt->bindParam(':id_padre', $data['id_padre']);
        $stmt->bindParam(':fecha_parto', $data['fecha_parto']);
        $stmt->bindParam(':numero_crias', $data['numero_crias']);
        $stmt->bindParam(':peso_total_crias', $data['peso_total_crias']);
        $stmt->bindParam(':tipo_parto', $data['tipo_parto']);
        $stmt->bindParam(':dificultad', $data['dificultad']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        return $stmt->execute();
    }

    public function delete($id_parto) {
        $sql = "DELETE FROM partos WHERE id_parto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_parto, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
