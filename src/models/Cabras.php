<?php
class Cabra {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Crear una nueva cabra
    public function create($data) {
        try {
            $query = "INSERT INTO cabras (nombre, madre, padre, fecha_nacimiento, sexo, id_raza, color, id_propietario_actual, estado, creado_por, foto) 
                     VALUES (:nombre, :madre, :padre, :fecha_nacimiento, :sexo, :id_raza, :color, :id_propietario_actual, :estado, :creado_por, :foto)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':madre', $data['madre']);
            $stmt->bindParam(':padre', $data['padre']);
            $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindParam(':sexo', $data['sexo']);
            $stmt->bindParam(':id_raza', $data['id_raza']);
            $stmt->bindParam(':color', $data['color']);
            $stmt->bindParam(':id_propietario_actual', $data['id_propietario_actual']);
            $stmt->bindParam(':estado', $data['estado']);
            $stmt->bindParam(':creado_por', $data['creado_por']);
            $stmt->bindParam(':foto', $data['foto']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating Cabra: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener todas las cabras con información relacionada
    public function getAll($limit = null, $offset = null) {
        try {
            $query = "SELECT c.*, 
                            r.nombre as raza_nombre,
                            p.nombre as propietario_nombre,
                            madre.nombre as madre_nombre,
                            padre.nombre as padre_nombre,
                            u.nombre as creado_por_nombre
                     FROM cabras c
                     LEFT JOIN razas r ON c.id_raza = r.id_raza
                     LEFT JOIN propietarios p ON c.id_propietario_actual = p.id_propietario
                     LEFT JOIN cabras madre ON c.madre = madre.id_cabra
                     LEFT JOIN cabras padre ON c.padre = padre.id_cabra
                     LEFT JOIN usuarios u ON c.creado_por = u.id
                     ORDER BY c.fecha_registro DESC";
            
            if ($limit !== null) {
                $query .= " LIMIT :limit";
                if ($offset !== null) {
                    $query .= " OFFSET :offset";
                }
            }
            
            $stmt = $this->db->prepare($query);
            
            if ($limit !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset !== null) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Cabras: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener una cabra por ID
    public function getById($id) {
        try {
            $query = "SELECT c.*, 
                            r.nombre as raza_nombre,
                            p.nombre as propietario_nombre,
                            madre.nombre as madre_nombre,
                            padre.nombre as padre_nombre,
                            u.nombre as creado_por_nombre
                     FROM cabras c
                     LEFT JOIN razas r ON c.id_raza = r.id_raza
                     LEFT JOIN propietarios p ON c.id_propietario_actual = p.id_propietario
                     LEFT JOIN cabras madre ON c.madre = madre.id_cabra
                     LEFT JOIN cabras padre ON c.padre = padre.id_cabra
                     LEFT JOIN usuarios u ON c.creado_por = u.id
                     WHERE c.id_cabra = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Cabra by ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar una cabra
    public function update($id, $data) {
        try {
            $query = "UPDATE cabras SET 
                        nombre = :nombre,
                        madre = :madre,
                        padre = :padre,
                        fecha_nacimiento = :fecha_nacimiento,
                        sexo = :sexo,
                        id_raza = :id_raza,
                        color = :color,
                        id_propietario_actual = :id_propietario_actual,
                        estado = :estado,
                        modificado_por = :modificado_por,
                        foto = :foto
                     WHERE id_cabra = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':madre', $data['madre']);
            $stmt->bindParam(':padre', $data['padre']);
            $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindParam(':sexo', $data['sexo']);
            $stmt->bindParam(':id_raza', $data['id_raza']);
            $stmt->bindParam(':color', $data['color']);
            $stmt->bindParam(':id_propietario_actual', $data['id_propietario_actual']);
            $stmt->bindParam(':estado', $data['estado']);
            $stmt->bindParam(':modificado_por', $data['modificado_por']);
            $stmt->bindParam(':foto', $data['foto']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Cabra: " . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar una cabra (cambiar estado a INACTIVA)
    public function delete($id, $user_id) {
        try {
            $query = "UPDATE cabras SET estado = 'INACTIVA', modificado_por = :modificado_por WHERE id_cabra = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':modificado_por', $user_id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Cabra: " . $e->getMessage());
            return false;
        }
    }
    
    // Buscar cabras
    public function search($term) {
        try {
            $query = "SELECT c.*, 
                            r.nombre as raza_nombre,
                            p.nombre as propietario_nombre
                     FROM cabras c
                     LEFT JOIN razas r ON c.id_raza = r.id_raza
                     LEFT JOIN propietarios p ON c.id_propietario_actual = p.id_propietario
                     WHERE c.nombre LIKE :term 
                        OR c.color LIKE :term 
                        OR r.nombre LIKE :term
                        OR p.nombre LIKE :term
                     ORDER BY c.nombre";
            
            $stmt = $this->db->prepare($query);
            $searchTerm = "%{$term}%";
            $stmt->bindParam(':term', $searchTerm);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching Cabras: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener cabras por sexo
    public function getBySex($sex) {
        try {
            $query = "SELECT * FROM cabras WHERE sexo = :sex AND estado = 'ACTIVA' ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':sex', $sex);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Cabras by sex: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener estadísticas
    public function getStats() {
        try {
            $stats = [];
            
            // Total de cabras activas
            $query = "SELECT COUNT(*) as total FROM cabras WHERE estado = 'ACTIVA'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Cabras por sexo
            $query = "SELECT sexo, COUNT(*) as cantidad FROM cabras WHERE estado = 'ACTIVA' GROUP BY sexo";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $sexStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($sexStats as $stat) {
                $stats['por_sexo'][$stat['sexo']] = $stat['cantidad'];
            }
            
            // Cabras por raza
            $query = "SELECT r.nombre, COUNT(*) as cantidad 
                     FROM cabras c 
                     LEFT JOIN razas r ON c.id_raza = r.id_raza 
                     WHERE c.estado = 'ACTIVA' 
                     GROUP BY r.nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['por_raza'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting Cabra stats: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar total de cabras
    public function count() {
        try {
            $query = "SELECT COUNT(*) as total FROM cabras WHERE estado = 'ACTIVA'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error counting Cabras: " . $e->getMessage());
            return 0;
        }
    }
}