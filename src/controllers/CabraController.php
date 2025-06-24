<?php
require_once '../models/Cabras.php';
require_once '../models/User.php';
require_once '../config/database.php';

class CabraController {
    private $Cabra;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->Cabra = new Cabra($this->db);
        
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/index.php');
            exit();
        }
    }
    
    // Mostrar lista de cabras
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $Cabras = $this->Cabra->getAll($limit, $offset);
        $total = $this->Cabra->count();
        $totalPages = ceil($total / $limit);
        
        $data = [
            'Cabras' => $Cabras,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
        
        $this->loadView('Cabras/index', $data);
    }
    
    // Mostrar formulario para crear cabra
    public function create() {
        $data = [
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $this->Cabra->getBySex('MACHO'),
            'females' => $this->Cabra->getBySex('HEMBRA')
        ];
        
        $this->loadView('Cabras/create', $data);
    }
    
    // Procesar creación de cabra
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $errors = $this->validateCabraData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: /src/controllers/CabraController.php?action=create');
            exit();
        }
        
        // Manejar subida de foto
        $photoPath = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->handlePhotoUpload($_FILES['foto']);
        }
        
        $data = [
            'nombre' => trim($_POST['nombre']),
            'madre' => !empty($_POST['madre']) ? $_POST['madre'] : null,
            'padre' => !empty($_POST['padre']) ? $_POST['padre'] : null,
            'fecha_nacimiento' => $_POST['fecha_nacimiento'],
            'sexo' => $_POST['sexo'],
            'id_raza' => !empty($_POST['id_raza']) ? $_POST['id_raza'] : null,
            'color' => trim($_POST['color']),
            'id_propietario_actual' => !empty($_POST['id_propietario_actual']) ? $_POST['id_propietario_actual'] : null,
            'estado' => 'ACTIVA',
            'creado_por' => $_SESSION['user_id'],
            'foto' => $photoPath
        ];
        
        $Cabra_id = $this->Cabra->create($data);
        
        if ($Cabra_id) {
            $_SESSION['success'] = 'Cabra registrada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al registrar la cabra';
        }
        
        header('Location: /src/controllers/CabraController.php?action=index');
        exit();
    }
    
    // Mostrar detalles de una cabra
    public function show() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $Cabra = $this->Cabra->getById($id);
        
        if (!$Cabra) {
            $_SESSION['error'] = 'Cabra no encontrada';
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $data = ['Cabra' => $Cabra];
        $this->loadView('Cabras/show', $data);
    }
    
    // Mostrar formulario de edición
    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $Cabra = $this->Cabra->getById($id);
        
        if (!$Cabra) {
            $_SESSION['error'] = 'Cabra no encontrada';
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $data = [
            'Cabra' => $Cabra,
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $this->Cabra->getBySex('MACHO'),
            'females' => $this->Cabra->getBySex('HEMBRA')
        ];
        
        $this->loadView('Cabras/edit', $data);
    }
    
    // Procesar actualización de cabra
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $id = isset($_POST['id_cabra']) ? (int)$_POST['id_cabra'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $errors = $this->validateCabraData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header("Location: /src/controllers/CabraController.php?action=edit&id={$id}");
            exit();
        }
        
        // Obtener datos actuales de la cabra
        $currentCabra = $this->Cabra->getById($id);
        $photoPath = $currentCabra['foto'];
        
        // Manejar subida de nueva foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $newPhotoPath = $this->handlePhotoUpload($_FILES['foto']);
            if ($newPhotoPath) {
                // Eliminar foto anterior si existe
                if ($photoPath && file_exists("../public/uploads/" . $photoPath)) {
                    unlink("../public/uploads/" . $photoPath);
                }
                $photoPath = $newPhotoPath;
            }
        }
        
        $data = [
            'nombre' => trim($_POST['nombre']),
            'madre' => !empty($_POST['madre']) ? $_POST['madre'] : null,
            'padre' => !empty($_POST['padre']) ? $_POST['padre'] : null,
            'fecha_nacimiento' => $_POST['fecha_nacimiento'],
            'sexo' => $_POST['sexo'],
            'id_raza' => !empty($_POST['id_raza']) ? $_POST['id_raza'] : null,
            'color' => trim($_POST['color']),
            'id_propietario_actual' => !empty($_POST['id_propietario_actual']) ? $_POST['id_propietario_actual'] : null,
            'estado' => $_POST['estado'],
            'modificado_por' => $_SESSION['user_id'],
            'foto' => $photoPath
        ];
        
        if ($this->Cabra->update($id, $data)) {
            $_SESSION['success'] = 'Cabra actualizada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la cabra';
        }
        
        header('Location: /src/controllers/CabraController.php?action=index');
        exit();
    }
    
    // Eliminar cabra (cambiar estado a INACTIVA)
    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
        } else {
            if ($this->Cabra->delete($id, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'Cabra eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la cabra';
            }
        }
        
        header('Location: /src/controllers/CabraController.php?action=index');
        exit();
    }
    
    // Buscar cabras
    public function search() {
        $term = isset($_GET['term']) ? trim($_GET['term']) : '';
        
        if (empty($term)) {
            header('Location: /src/controllers/CabraController.php?action=index');
            exit();
        }
        
        $Cabras = $this->Cabra->search($term);
        
        $data = [
            'Cabras' => $Cabras,
            'searchTerm' => $term
        ];
        
        $this->loadView('Cabras/search', $data);
    }
    
    // Obtener estadísticas
    public function stats() {
        $stats = $this->Cabra->getStats();
        $data = ['stats' => $stats];
        $this->loadView('Cabras/stats', $data);
    }
    
    // Métodos auxiliares
    private function getBreeds() {
        try {
            $query = "SELECT * FROM razas ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    private function getOwners() {
        try {
            $query = "SELECT * FROM propietarios ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    private function validateCabraData($data) {
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        }
        
        if (empty($data['sexo']) || !in_array($data['sexo'], ['MACHO', 'HEMBRA'])) {
            $errors[] = 'El sexo debe ser MACHO o HEMBRA';
        }
        
        if (!empty($data['fecha_nacimiento'])) {
            $fecha = DateTime::createFromFormat('Y-m-d', $data['fecha_nacimiento']);
            if (!$fecha || $fecha->format('Y-m-d') !== $data['fecha_nacimiento']) {
                $errors[] = 'Fecha de nacimiento inválida';
            } elseif ($fecha > new DateTime()) {
                $errors[] = 'La fecha de nacimiento no puede ser futura';
            }
        }
        
        return $errors;
    }
    
    private function handlePhotoUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG y GIF.';
            return null;
        }
        
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'El archivo es demasiado grande. Máximo 5MB.';
            return null;
        }
        
        $uploadDir = '../public/uploads/Cabras/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('Cabra_') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'Cabras/' . $filename;
        }
        
        $_SESSION['error'] = 'Error al subir la foto';
        return null;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        include "../views/{$view}.php";
    }
}

// Routing simple
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$controller = new CabraController();

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'show':
        $controller->show();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'search':
        $controller->search();
        break;
    case 'stats':
        $controller->stats();
        break;
    default:
        $controller->index();
        break;
}