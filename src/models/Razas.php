<?php
// models/Raza.php
class Raza {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Crear una nueva raza
    public function create($data) {
        try {
            $query = "INSERT INTO razas (nombre) VALUES (:nombre)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $data['nombre']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating Raza: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener todas las razas
    public function getAll($limit = null, $offset = null) {
        try {
            $query = "SELECT r.*, 
                            COUNT(c.id_cabra) as total_cabras
                     FROM razas r
                     LEFT JOIN cabras c ON r.id_raza = c.id_raza AND c.estado = 'ACTIVA'
                     GROUP BY r.id_raza
                     ORDER BY r.nombre";
            
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
            error_log("Error getting Razas: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener una raza por ID
    public function getById($id) {
        try {
            $query = "SELECT r.*, 
                            COUNT(c.id_cabra) as total_cabras
                     FROM razas r
                     LEFT JOIN cabras c ON r.id_raza = c.id_raza AND c.estado = 'ACTIVA'
                     WHERE r.id_raza = :id
                     GROUP BY r.id_raza";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting Raza by ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar una raza
    public function update($id, $data) {
        try {
            $query = "UPDATE razas SET nombre = :nombre WHERE id_raza = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $data['nombre']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating Raza: " . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar una raza (solo si no tiene cabras asociadas)
    public function delete($id) {
        try {
            // Verificar si hay cabras asociadas
            $checkQuery = "SELECT COUNT(*) as total FROM cabras WHERE id_raza = :id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                return 'has_cabras'; // Retorna un código especial
            }
            
            // Si no hay cabras asociadas, eliminar la raza
            $query = "DELETE FROM razas WHERE id_raza = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting Raza: " . $e->getMessage());
            return false;
        }
    }
    
    // Buscar razas
    public function search($term) {
        try {
            $query = "SELECT r.*, 
                            COUNT(c.id_cabra) as total_cabras
                     FROM razas r
                     LEFT JOIN cabras c ON r.id_raza = c.id_raza AND c.estado = 'ACTIVA'
                     WHERE r.nombre LIKE :term
                     GROUP BY r.id_raza
                     ORDER BY r.nombre";
            
            $stmt = $this->db->prepare($query);
            $searchTerm = "%{$term}%";
            $stmt->bindParam(':term', $searchTerm);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching Razas: " . $e->getMessage());
            return [];
        }
    }
    
    // Verificar si una raza ya existe
    public function exists($nombre, $excludeId = null) {
        try {
            $query = "SELECT COUNT(*) as total FROM razas WHERE nombre = :nombre";
            
            if ($excludeId !== null) {
                $query .= " AND id_raza != :exclude_id";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            
            if ($excludeId !== null) {
                $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error checking if Raza exists: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar total de razas
    public function count() {
        try {
            $query = "SELECT COUNT(*) as total FROM razas";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error counting Razas: " . $e->getMessage());
            return 0;
        }
    }
    
    // Obtener estadísticas de razas
    public function getStats() {
        try {
            $stats = [];
            
            // Total de razas
            $stats['total_razas'] = $this->count();
            
            // Razas más populares (con más cabras)
            $query = "SELECT r.nombre, COUNT(c.id_cabra) as total_cabras
                     FROM razas r
                     LEFT JOIN cabras c ON r.id_raza = c.id_raza AND c.estado = 'ACTIVA'
                     GROUP BY r.id_raza, r.nombre
                     ORDER BY total_cabras DESC
                     LIMIT 5";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['populares'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting Raza stats: " . $e->getMessage());
            return [];
        }
    }
}