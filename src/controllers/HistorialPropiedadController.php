<?php
// src/controllers/HistorialPropiedadController.php
require_once __DIR__ . '/../models/HistorialPropiedad.php';
require_once __DIR__ . '/../models/Propietarios.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class HistorialPropiedadController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new HistorialPropiedad($this->db);
    }

    private function isLoggedIn() {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;
        if ($isLoginPage) return false;
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        if (strpos($_SERVER['REQUEST_URI'] ?? '', '/login') === false) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }

    private function redirectToHistorial($idCabra = null) {
        $url = BASE_URL . "/cabras/" . ($idCabra ?? '');
        header("Location: $url");
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('historial', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $data = ['historial' => $this->model->getById($id), 'id_cabra' => $id];
        $this->loadView('index', $data);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $csrf_token = generateCSRFToken();
        $propietarioModel = new Propietario($this->db);
        $propietarios = $propietarioModel->getAll();
        $this->loadView('create_edit', [
            'csrf_token' => $csrf_token,
            'id_cabra' => $id,
            'propietarios' => $propietarios
        ]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirectToHistorial();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token inválido';
            $this->redirectToHistorial($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToHistorial($data['id_cabra']);
        }

        $this->model->create($data);
        $_SESSION['success'] = 'Historial registrado';
        $this->redirectToHistorial($data['id_cabra']);
    }

    public function show() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $registro = $this->model->getById($id);
        $this->loadView('show', ['historial' => $registro, 'id_cabra' => $registro['id_cabra']]);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $registro = $this->model->getById($id);
        $csrf_token = generateCSRFToken();
        $propietarioModel = new Propietario($this->db);
        $propietarios = $propietarioModel->getAll();
        $this->loadView('create_edit', [
            'historial' => $registro,
            'csrf_token' => $csrf_token,
            'id_cabra' => $registro['id_cabra'],
            'propietarios' => $propietarios
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token inválido';
            $this->redirectToHistorial($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToHistorial($data['id_cabra']);
        }

        $this->model->update($id, $data);
        $_SESSION['success'] = 'Historial actualizado';
        $this->redirectToHistorial($data['id_cabra']);
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $registro = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Registro eliminado';
        }
        $this->redirectToHistorial($registro['id_cabra']);
    }

    private function getDataFromRequest(): array {
        return [
            'id_cabra' => $_POST['id_cabra'] ?? null,
            'id_propietario' => $_POST['id_propietario'] ?? null,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null,
            'motivo_cambio' => $_POST['motivo_cambio'] ?? null,
            'precio_transaccion' => $_POST['precio_transaccion'] ?? null,
        ];
    }

    private function validate(array $data): array {
        $errors = [];

        if (!$data['id_cabra']) $errors[] = 'La cabra es obligatoria';
        if (!$data['id_propietario']) $errors[] = 'El propietario es obligatorio';
        if (!$data['fecha_inicio']) $errors[] = 'La fecha de inicio es obligatoria';

        return $errors;
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/historial_propiedad/index_historial.php',
            'create_edit' => __DIR__ . '/../views/historial_propiedad/create_edit_historial.php',
            'show' => __DIR__ . '/../views/historial_propiedad/show_historial.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada: ' . $view;
            $this->redirectToHistorial();
        }
    }
}
