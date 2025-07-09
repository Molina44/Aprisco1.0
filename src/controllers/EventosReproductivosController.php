<?php
// src/controllers/EventoReproductivoController.php
require_once __DIR__ . '/../models/EventoReproductivo.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';


class EventoReproductivoController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new EventoReproductivo($this->db);
    }

    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin() {
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    private function redirectToCabra($id) {
        header("Location: " . BASE_URL . "/cabras/" . $id);
        exit();
    }

    private function getIdFromUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $index = array_search('eventos', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $eventos = $this->model->getByCabra($id);
        $this->loadView('index', ['eventos' => $eventos, 'id_cabra' => $id]);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $csrf_token = generateCSRFToken();
        $sementales = $this->model->getSementalesDisponibles(); 

        $this->loadView('create_edit', [
            'csrf_token' => $csrf_token,
            'id_cabra' => $id,
            'sementales' => $sementales
        ]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }

        $this->model->create($data);
        $_SESSION['success'] = 'Evento registrado correctamente';
        $this->redirectToCabra($data['id_cabra']);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $evento = $this->model->getById($id);
        $csrf_token = generateCSRFToken();
        $sementales = $this->model->getSementalesDisponibles();

        $this->loadView('create_edit', [
            'evento' => $evento,
            'csrf_token' => $csrf_token,
            'id_cabra' => $evento['id_cabra'],
            'sementales' => $sementales
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_cabra']);
        }

        $this->model->update($id, $data);
        $_SESSION['success'] = 'Evento actualizado correctamente';
        $this->redirectToCabra($data['id_cabra']);
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();
        $evento = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Evento eliminado correctamente';
        }

        $this->redirectToCabra($evento['id_cabra']);
    }

    private function getDataFromRequest(): array {
        return [
            'id_cabra' => $_POST['id_cabra'] ?? null,
            'fecha_evento' => $_POST['fecha_evento'] ?? null,
            'tipo_evento' => $_POST['tipo_evento'] ?? null,
            'id_semental' => $_POST['id_semental'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'registrado_por' => $_SESSION['user_id'] ?? null
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['id_cabra'])) $errors[] = 'La cabra es requerida';
        if (empty($data['fecha_evento'])) $errors[] = 'La fecha del evento es requerida';
        if (empty($data['tipo_evento'])) $errors[] = 'El tipo de evento es requerido';
        return $errors;
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/eventos/index_eventos.php',
            'create_edit' => __DIR__ . '/../views/eventos/create_edit_evento.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada';
            $this->redirectToCabra($data['id_cabra'] ?? '');
        }
    }
}
