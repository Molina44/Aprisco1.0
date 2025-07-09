<?php
// src/controllers/PartoController.php

require_once __DIR__ . '/../models/Parto.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class PartosController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new Parto($this->db);
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
        $index = array_search('partos', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function index() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $partos = $this->model->getByCabra($id);
        $this->loadView('index', ['partos' => $partos, 'id_cabra' => $id]);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $csrf_token = generateCSRFToken();
        $padres = $this->model->getPadresDisponibles();

        $this->loadView('create_edit', [
            'csrf_token' => $csrf_token,
            'id_cabra' => $id,
            'padres' => $padres
        ]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_madre']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_madre']);
        }

        $this->model->create($data);
        $_SESSION['success'] = 'Parto registrado correctamente';
        $this->redirectToCabra($data['id_madre']);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $parto = $this->model->getById($id);
        $csrf_token = generateCSRFToken();
        $padres = $this->model->getPadresDisponibles();

        $this->loadView('create_edit', [
            'parto' => $parto,
            'csrf_token' => $csrf_token,
            'id_cabra' => $parto['id_madre'],
            'padres' => $padres
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_madre']);
        }

        $data = $this->getDataFromRequest();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $data;
            $this->redirectToCabra($data['id_madre']);
        }

        $this->model->update($id, $data);
        $_SESSION['success'] = 'Parto actualizado correctamente';
        $this->redirectToCabra($data['id_madre']);
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $parto = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Parto eliminado correctamente';
        }

        $this->redirectToCabra($parto['id_madre']);
    }

    private function getDataFromRequest(): array {
        return [
            'id_madre' => $_POST['id_madre'] ?? null,
            'id_padre' => $_POST['id_padre'] ?? null,
            'fecha_parto' => $_POST['fecha_parto'] ?? null,
            'numero_crias' => $_POST['numero_crias'] ?? null,
            'peso_total_crias' => $_POST['peso_total_crias'] ?? null,
            'tipo_parto' => $_POST['tipo_parto'] ?? null,
            'dificultad' => $_POST['dificultad'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'registrado_por' => $_SESSION['user_id'] ?? null
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['id_madre'])) $errors[] = 'La madre es requerida';
        if (empty($data['fecha_parto'])) $errors[] = 'La fecha del parto es requerida';
        return $errors;
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'index' => __DIR__ . '/../views/partos/index_partos.php',
            'create_edit' => __DIR__ . '/../views/partos/create_edit_parto.php',
            'show' => __DIR__ . '/../views/partos/show_parto.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada';
            $this->redirectToCabra($data['id_madre'] ?? '');
        }
    }
}
