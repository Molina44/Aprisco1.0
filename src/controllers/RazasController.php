<?php
require_once __DIR__ . '/../models/Razas.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class RazaController {
    private $raza;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->raza = new Raza($this->db);
    }
    
    // CORRECCIÓN: Lógica simplificada de verificación de sesión
    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login_time']) && 
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    // CORRECCIÓN: Redirección mejorada
    private function redirectToLogin() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        
        // Solo redirigir si no estamos en la página de login
        if (!$isLoginPage) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }
    private function redirectToRazas() {
        header("Location: " . BASE_URL . "/razas");
        exit();
    }
    
    private function redirectToRazaShow($id) {
        header("Location: " . BASE_URL . "/razas/$id");
        exit();
    }
    
    private function redirectToRazaEdit($id) {
        header("Location: " . BASE_URL . "/razas/$id/edit");
        exit();
    }
    
    // Extraer ID de la URL
    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        
        // Buscar el segmento después de 'razas'
        $razasIndex = array_search('razas', $segments);
        if ($razasIndex !== false && isset($segments[$razasIndex + 1])) {
            $id = (int)$segments[$razasIndex + 1];
            return $id > 0 ? $id : null;
        }
        
        // Fallback a $_GET['id']
        return isset($_GET['id']) ? (int)$_GET['id'] : null;
    }
    
    // Mostrar lista de razas
    public function index() {
        // CORRECCIÓN: Verificación mejorada
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $razas = $this->raza->getAll($limit, $offset);
        $total = $this->raza->count();
        $totalPages = ceil($total / $limit);
        
        $data = [
            'razas' => $razas,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
        
        $this->loadView('index', $data);
    }
    
    // Mostrar formulario para crear raza
    public function create() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $this->loadView('create');
    }
    
    // Procesar creación de raza
    public function store() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToRazas();
            return;
        }
        
        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido o expirado";
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/razas/create');
            exit();
        }
        
        $errors = $this->validateRazaData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/razas/create');
            exit();
        }
        
        $data = [
            'nombre' => trim($_POST['nombre'])
        ];
        
        $raza_id = $this->raza->create($data);
        
        if ($raza_id) {
            $_SESSION['success'] = 'Raza registrada exitosamente';
            $this->redirectToRazaShow($raza_id);
        } else {
            $_SESSION['error'] = 'Error al registrar la raza';
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/razas/create');
            exit();
        }
    }
    
    // Mostrar detalles de una raza
    public function show() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = $this->getIdFromUrl();
        
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de raza inválido';
            $this->redirectToRazas();
            return;
        }
        
        $raza = $this->raza->getById($id);
        
        if (!$raza) {
            $_SESSION['error'] = 'Raza no encontrada';
            $this->redirectToRazas();
            return;
        }
        
        $data = ['raza' => $raza];
        $this->loadView('show', $data);
    }
    
    // Mostrar formulario de edición
    public function edit() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = $this->getIdFromUrl();
        
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de raza inválido';
            $this->redirectToRazas();
            return;
        }
        
        $raza = $this->raza->getById($id);
        
        if (!$raza) {
            $_SESSION['error'] = 'Raza no encontrada';
            $this->redirectToRazas();
            return;
        }
        
        $data = ['raza' => $raza];
        $this->loadView('edit', $data);
    }
    
    // Procesar actualización de raza
    public function update() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToRazas();
            return;
        }
        
        $id = $this->getIdFromUrl();
        
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de raza inválido para actualización';
            $this->redirectToRazas();
            return;
        }
        
        // Verificar que la raza existe
        $existingRaza = $this->raza->getById($id);
        if (!$existingRaza) {
            $_SESSION['error'] = 'La raza que intenta actualizar no existe';
            $this->redirectToRazas();
            return;
        }
        
        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido o expirado";
            $_SESSION['form_data'] = $_POST;
            $this->redirectToRazaEdit($id);
            return;
        }
        
        $errors = $this->validateRazaData($_POST, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirectToRazaEdit($id);
            return;
        }
        
        $data = [
            'nombre' => trim($_POST['nombre'])
        ];
        
        if ($this->raza->update($id, $data)) {
            $_SESSION['success'] = 'Raza actualizada exitosamente';
            $this->redirectToRazaShow($id);
        } else {
            $_SESSION['error'] = 'Error al actualizar la raza';
            $this->redirectToRazaEdit($id);
        }
    }
    
    // Eliminar raza
    public function delete() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        } else {
            $id = $this->getIdFromUrl();
        }
        
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de raza inválido';
            $this->redirectToRazas();
            return;
        }
        
        // Verificar que la raza existe
        $existingRaza = $this->raza->getById($id);
        if (!$existingRaza) {
            $_SESSION['error'] = 'La raza que intenta eliminar no existe';
            $this->redirectToRazas();
            return;
        }
        
        // Si es GET, mostrar confirmación
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['raza' => $existingRaza];
            $this->loadView('delete', $data);
            return;
        }
        
        // Si es POST, proceder con la eliminación
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar token CSRF
            if (isset($_POST['csrf_token']) && !verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Token de seguridad inválido o expirado";
                $this->redirectToRazas();
                return;
            }
            
            $result = $this->raza->delete($id);
            
            if ($result === 'has_cabras') {
                $_SESSION['error'] = 'No se puede eliminar la raza porque tiene cabras asociadas';
            } elseif ($result) {
                $_SESSION['success'] = 'Raza eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la raza';
            }
        }
        
        $this->redirectToRazas();
    }
    
    // Buscar razas
    public function search() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }
        
        $term = isset($_GET['term']) ? trim($_GET['term']) : '';
        
        if (empty($term)) {
            $this->redirectToRazas();
            return;
        }
        
        $razas = $this->raza->search($term);
        
        $data = [
            'razas' => $razas,
            'searchTerm' => $term,
            'currentPage' => 1,
            'totalPages' => 1,
            'total' => count($razas)
        ];
        
        $this->loadView('index', $data);
    }
    
    // Obtener estadísticas
    public function stats() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }
        
        $stats = $this->raza->getStats();
        $data = ['stats' => $stats];
        $this->loadView('stats', $data);
    }
    
    // Validar datos de raza
    private function validateRazaData($data, $excludeId = null) {
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre de la raza es obligatorio';
        } else {
            $nombre = trim($data['nombre']);
            
            if (strlen($nombre) < 2) {
                $errors[] = 'El nombre debe tener al menos 2 caracteres';
            }
            
            if (strlen($nombre) > 100) {
                $errors[] = 'El nombre no puede tener más de 100 caracteres';
            }
            
            // Verificar si ya existe
            if ($this->raza->exists($nombre, $excludeId)) {
                $errors[] = 'Ya existe una raza con ese nombre';
            }
        }
        
        return $errors;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        
        // Mapear las vistas
        $viewPaths = [
            'index' => __DIR__ . "/../views/raza/razas.php",
            'create' => __DIR__ . "/../views/raza/create_raza.php",
            'show' => __DIR__ . "/../views/raza/show_raza.php",
            'edit' => __DIR__ . "/../views/raza/edit_raza.php",
            'delete' => __DIR__ . "/../views/raza/delete_raza.php",
            'stats' => __DIR__ . "/../views/raza/stats_raza.php"
        ];
        
        if (isset($viewPaths[$view]) && file_exists($viewPaths[$view])) {
            include $viewPaths[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToRazas();
        }
    }
}