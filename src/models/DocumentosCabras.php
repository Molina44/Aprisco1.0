<?php
// src/models/DocumentosCabras.php

class DocumentosCabras {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getByCabra(int $id_cabra): array {
        $sql = "SELECT d.*, u.nombre AS nombre_usuario
                FROM documentos_cabras d
                LEFT JOIN usuarios u ON d.subido_por = u.id
                WHERE d.id_cabra = :id_cabra
                ORDER BY d.fecha_subida DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id_documento): ?array {
        $sql = "SELECT * FROM documentos_cabras WHERE id_documento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_documento, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO documentos_cabras (
                    id_cabra, tipo_documento, ruta_archivo, subido_por
                ) VALUES (
                    :id_cabra, :tipo_documento, :ruta_archivo, :subido_por
                )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id_cabra', $data['id_cabra'], PDO::PARAM_INT);
        $stmt->bindValue(':tipo_documento', $data['tipo_documento'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':ruta_archivo', $data['ruta_archivo'], PDO::PARAM_STR);
        $stmt->bindValue(':subido_por', $data['subido_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id_documento): bool {
        $stmt = $this->db->prepare("DELETE FROM documentos_cabras WHERE id_documento = :id");
        $stmt->bindValue(':id', $id_documento, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
