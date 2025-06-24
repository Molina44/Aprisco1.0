<?php
require_once __DIR__ . '/../models/Cabras.php';
require_once __DIR__ . '/../../config/database.php';

class CabraController {
    private $cabra;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cabra = new Cabra($this->db);
    }
    
    private function isLoggedIn() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        
        if ($isLoginPage) {
            return false;
        }
        
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        
        if (!$isLoginPage) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }
    
    private function redirectToCabras() {
        header("Location: " . BASE_URL . "/cabras");
        exit();
    }
    
    private function redirectToCabraEdit($id) {
        header("Location: " . BASE_URL . "/cabras/$id/edit");
        exit();
    }
    
    private function redirectToCabraShow($id) {
        header("Location: " . BASE_URL . "/cabras/$id");
        exit();
    }
    
    // Mostrar lista de cabras
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $cabras = $this->cabra->getAll($limit, $offset);
        $total = $this->cabra->count();
        $totalPages = ceil($total / $limit);
        
        $data = [
            'cabras' => $cabras,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
        
        $this->loadView('index', $data);
    }
    
    // Mostrar formulario para crear cabra
    public function create() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $data = [
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $this->cabra->getBySex('MACHO'),
            'females' => $this->cabra->getBySex('HEMBRA')
        ];
        
        $this->loadView('create', $data);
    }
    
    // Procesar creación de cabra
    public function store() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToCabras();
            return;
        }
        
        // Verificar token CSRF
        if (!function_exists('verifyCSRFToken') || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            $this->redirectToCabras();
            return;
        }
        
        $errors = $this->validateCabraData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/cabras/create');
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
        
        $cabra_id = $this->cabra->create($data);
        
        if ($cabra_id) {
            $_SESSION['success'] = 'Cabra registrada exitosamente';
            $this->redirectToCabraShow($cabra_id);
        } else {
            $_SESSION['error'] = 'Error al registrar la cabra';
            $_SESSION['form_data'] = $_POST;
            $this->redirectToCabras();
        }
    }
    
    // Mostrar detalles de una cabra
    public function show() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            $this->redirectToCabras();
            return;
        }
        
        $cabra = $this->cabra->getById($id);
        
        if (!$cabra) {
            $_SESSION['error'] = 'Cabra no encontrada';
            $this->redirectToCabras();
            return;
        }
        
        $data = ['cabra' => $cabra];
        $this->loadView('show', $data);
    }
    
    // Mostrar formulario de edición
    public function edit() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            $this->redirectToCabras();
            return;
        }
        
        $cabra = $this->cabra->getById($id);
        
        if (!$cabra) {
            $_SESSION['error'] = 'Cabra no encontrada';
            $this->redirectToCabras();
            return;
        }
        
        $data = [
            'cabra' => $cabra,
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $this->cabra->getBySex('MACHO'),
            'females' => $this->cabra->getBySex('HEMBRA')
        ];
        
        $this->loadView('edit', $data);
    }
    
    // Procesar actualización de cabra
    public function update() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToCabras();
            return;
        }
        
        $id = isset($_POST['id_cabra']) ? (int)$_POST['id_cabra'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            $this->redirectToCabras();
            return;
        }
        
        // Verificar token CSRF
        if (!function_exists('verifyCSRFToken') || !verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido";
            $this->redirectToCabraEdit($id);
            return;
        }
        
        $errors = $this->validateCabraData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirectToCabraEdit($id);
            return;
        }
        
        // Obtener datos actuales de la cabra
        $currentCabra = $this->cabra->getById($id);
        $photoPath = $currentCabra['foto'];
        
        // Manejar subida de nueva foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $newPhotoPath = $this->handlePhotoUpload($_FILES['foto']);
            if ($newPhotoPath) {
                // Eliminar foto anterior si existe
                if ($photoPath && file_exists(__DIR__ . "/../../public/uploads/" . $photoPath)) {
                    unlink(__DIR__ . "/../../public/uploads/" . $photoPath);
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
        
        if ($this->cabra->update($id, $data)) {
            $_SESSION['success'] = 'Cabra actualizada exitosamente';
            $this->redirectToCabraShow($id);
        } else {
            $_SESSION['error'] = 'Error al actualizar la cabra';
            $this->redirectToCabraEdit($id);
        }
    }
    
    // Eliminar cabra (cambiar estado a INACTIVA)
    public function delete() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToCabras();
            return;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
        } else {
            if ($this->cabra->delete($id, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'Cabra eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la cabra';
            }
        }
        
        $this->redirectToCabras();
    }
    

    // Buscar cabras
    public function search() {
        $term = isset($_GET['term']) ? trim($_GET['term']) : '';
        
        if (empty($term)) {
            header('Location: ' . BASE_URL . '/cabras');
            exit();
        }
        
        $cabras = $this->cabra->search($term);
        
        $data = [
            'cabras' => $cabras,
            'searchTerm' => $term,
            'currentPage' => 1,
            'totalPages' => 1,
            'total' => count($cabras)
        ];
        
        $this->loadView('index', $data);
    }
    
    // Obtener estadísticas
    public function stats() {
        $stats = $this->cabra->getStats();
        $data = ['stats' => $stats];
        $this->loadView('stats', $data);
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
        
        $uploadDir = __DIR__ . '/../../public/uploads/cabras/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('cabra_') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'cabras/' . $filename;
        }
        
        $_SESSION['error'] = 'Error al subir la foto';
        return null;
    }
    
    // CORRECCIÓN: Método loadView corregido para evitar bucles
    private function loadView($view, $data = []) {
        extract($data);
        
        // Mapear las vistas correctamente
        $viewPaths = [
            'index' => __DIR__ . "/../views/cabra/cabras.php",
            'create' => __DIR__ . "/../views/cabra/create_cabra.php",
            'show' => __DIR__ . "/../views/cabra/show_cabra.php",
            'edit' => __DIR__ . "/../views/cabra/edit_cabra.php",
            'stats' => __DIR__ . "/../views/cabra/stats_cabra.php"
        ];
        
        if (isset($viewPaths[$view]) && file_exists($viewPaths[$view])) {
            include $viewPaths[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            header('Location: ' . BASE_URL . '/cabras');
            exit();
        }
    }
}