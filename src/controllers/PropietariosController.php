<?php 
require_once __DIR__ . '/../models/Propietarios.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class PropietariosController {
    private $propietario;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->propietario = new Propietario($this->db);
    }

    private function isLoggedIn() {
        $url = $_SERVER['REQUEST_URI'] ?? '';
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    private function redirectToList() {
        header("Location: " . BASE_URL . "/propietarios");
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('propietarios', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $data = ['propietarios' => $this->propietario->getAll()];
        $this->loadView('index', $data);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $this->loadView('create');
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirectToList();

        $this->propietario->create($_POST)
            ? $_SESSION['success'] = 'Propietario creado'
            : $_SESSION['error'] = 'Error al crear propietario';

        $this->redirectToList();
    }

    public function show() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $this->loadView('show', ['propietario' => $this->propietario->getById($id)]);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $this->loadView('edit', ['propietario' => $this->propietario->getById($id)]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        $this->propietario->update($id, $_POST)
            ? $_SESSION['success'] = 'Propietario actualizado'
            : $_SESSION['error'] = 'Error al actualizar';

        $this->redirectToList();
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        $this->propietario->delete($id)
            ? $_SESSION['success'] = 'Propietario eliminado'
            : $_SESSION['error'] = 'Error al eliminar';

        $this->redirectToList();
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/propietarios/index.php',
            'create' => __DIR__ . '/../views/propietarios/create.php',
            'edit' => __DIR__ . '/../views/propietarios/edit.php',
            'show' => __DIR__ . '/../views/propietarios/show.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToList();
        }
    }
}
