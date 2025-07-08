<?php
require_once __DIR__ . '/../models/Razas.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class RazasController {
    private $raza;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->raza = new Raza($this->db);
    }

    private function isLoggedIn() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;

        if ($isLoginPage) return false;

        return isset($_SESSION['user_id']) &&
               isset($_SESSION['login_time']) &&
               (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        if (strpos($_SERVER['REQUEST_URI'] ?? '', '/login') === false) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }

    private function redirectToRazas() {
        header("Location: " . BASE_URL . "/razas");
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('razas', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $razas = $this->raza->getAll();
        $data = ['razas' => $razas];
        $this->loadView('index', $data);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $this->loadView('create');
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirectToRazas();

        $data = ['nombre' => trim($_POST['nombre'])];
        if ($this->raza->create($data)) {
            $_SESSION['success'] = 'Raza registrada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al registrar la raza';
        }
        $this->redirectToRazas();
    }

    public function show() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $raza = $this->raza->getById($id);
        $this->loadView('show', ['raza' => $raza]);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $raza = $this->raza->getById($id);
        $this->loadView('edit', ['raza' => $raza]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        $data = ['nombre' => trim($_POST['nombre'])];
        if ($this->raza->update($id, $data)) {
            $_SESSION['success'] = 'Raza actualizada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la raza';
        }
        $this->redirectToRazas();
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        if ($this->raza->delete($id)) {
            $_SESSION['success'] = 'Raza eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la raza';
        }
        $this->redirectToRazas();
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/razas/razas.php',
            'create' => __DIR__ . '/../views/razas/create_raza.php',
            'edit' => __DIR__ . '/../views/razas/edit_raza.php',
            'show' => __DIR__ . '/../views/razas/show_raza.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToRazas();
        }
    }
}
