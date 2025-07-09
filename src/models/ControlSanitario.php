<?php
// src/models/ControlSanitario.php
class ControlSanitario {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getByCabra($id_cabra) {
        $sql = "SELECT cs.*, u.nombre AS nombre_usuario
                FROM controles_sanitarios cs
                LEFT JOIN usuarios u ON cs.registrado_por = u.id
                WHERE cs.id_cabra = :id_cabra
                ORDER BY cs.fecha_control DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cabra', $id_cabra, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_control) {
        $sql = "SELECT * FROM controles_sanitarios WHERE id_control = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_control, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO controles_sanitarios (
                    id_cabra, fecha_control, peso_kg, peso_nacer_kg, condicion_especial, fama_hoja,
                    orejas, mucosas, vitaminacion, purga, observaciones, registrado_por,
                    c_corporal, genitales, ubre, foto_ubre, drack_score, famacha, sin_muda,
                    pinzas, primeros_medios, segundos_medios, extremos, desgaste, perdidas_dentales,
                    cascos, e_interdigital
                ) VALUES (
                    :id_cabra, :fecha_control, :peso_kg, :peso_nacer_kg, :condicion_especial, :fama_hoja,
                    :orejas, :mucosas, :vitaminacion, :purga, :observaciones, :registrado_por,
                    :c_corporal, :genitales, :ubre, :foto_ubre, :drack_score, :famacha, :sin_muda,
                    :pinzas, :primeros_medios, :segundos_medios, :extremos, :desgaste, :perdidas_dentales,
                    :cascos, :e_interdigital
                )";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cabra' => $data['id_cabra'],
            ':fecha_control' => $data['fecha_control'],
            ':peso_kg' => $data['peso_kg'],
            ':peso_nacer_kg' => $data['peso_nacer_kg'],
            ':condicion_especial' => $data['condicion_especial'],
            ':fama_hoja' => $data['fama_hoja'],
            ':orejas' => $data['orejas'],
            ':mucosas' => $data['mucosas'],
            ':vitaminacion' => $data['vitaminacion'],
            ':purga' => $data['purga'],
            ':observaciones' => $data['observaciones'],
            ':registrado_por' => $data['registrado_por'],
            ':c_corporal' => $data['c_corporal'],
            ':genitales' => $data['genitales'],
            ':ubre' => $data['ubre'],
            ':foto_ubre' => $data['foto_ubre'],
            ':drack_score' => $data['drack_score'],
            ':famacha' => $data['famacha'],
            ':sin_muda' => $data['sin_muda'],
            ':pinzas' => $data['pinzas'],
            ':primeros_medios' => $data['primeros_medios'],
            ':segundos_medios' => $data['segundos_medios'],
            ':extremos' => $data['extremos'],
            ':desgaste' => $data['desgaste'],
            ':perdidas_dentales' => $data['perdidas_dentales'],
            ':cascos' => $data['cascos'],
            ':e_interdigital' => $data['e_interdigital'],
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE controles_sanitarios SET
                    id_cabra = :id_cabra,
                    fecha_control = :fecha_control,
                    peso_kg = :peso_kg,
                    peso_nacer_kg = :peso_nacer_kg,
                    condicion_especial = :condicion_especial,
                    fama_hoja = :fama_hoja,
                    orejas = :orejas,
                    mucosas = :mucosas,
                    vitaminacion = :vitaminacion,
                    purga = :purga,
                    observaciones = :observaciones,
                    c_corporal = :c_corporal,
                    genitales = :genitales,
                    ubre = :ubre,
                    foto_ubre = :foto_ubre,
                    drack_score = :drack_score,
                    famacha = :famacha,
                    sin_muda = :sin_muda,
                    pinzas = :pinzas,
                    primeros_medios = :primeros_medios,
                    segundos_medios = :segundos_medios,
                    extremos = :extremos,
                    desgaste = :desgaste,
                    perdidas_dentales = :perdidas_dentales,
                    cascos = :cascos,
                    e_interdigital = :e_interdigital
                WHERE id_control = :id";

        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id_control) {
        $sql = "DELETE FROM controles_sanitarios WHERE id_control = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_control, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
