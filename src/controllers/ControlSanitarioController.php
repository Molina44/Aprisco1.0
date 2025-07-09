<?php
// src/controllers/ControlSanitarioController.php
require_once __DIR__ . '/../models/ControlSanitario.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class ControlSanitarioController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new ControlSanitario($this->db);
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
        $index = array_search('controles', $segments);
        return ($index !== false && isset($segments[$index + 1])) ? (int)$segments[$index + 1] : ($_GET['id'] ?? null);
    }

    public function create() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $csrf_token = generateCSRFToken();

        $this->loadView('create_edit', [
            'csrf_token' => $csrf_token,
            'id_cabra' => $id
        ]);
    }

    public function store() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirectToCabra($_POST['id_cabra']);
        }

        $data = $this->getDataFromRequest();
        $data['foto_ubre'] = $this->handleUbreUpload();

        $this->model->create($data);
        $_SESSION['success'] = 'Control sanitario registrado correctamente';
        $this->redirectToCabra($data['id_cabra']);
    }

    public function edit() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();
        $id = $this->getIdFromUrl();
        $control = $this->model->getById($id);
        $csrf_token = generateCSRFToken();

        $this->loadView('create_edit', [
            'control' => $control,
            'csrf_token' => $csrf_token,
            'id_cabra' => $control['id_cabra']
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
        $existing = $this->model->getById($id);

        $foto = $this->handleUbreUpload();
        $data['foto_ubre'] = $foto ?? $existing['foto_ubre'];

        $this->model->update($id, $data);
        $_SESSION['success'] = 'Control sanitario actualizado correctamente';
        $this->redirectToCabra($data['id_cabra']);
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();
        $control = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Control eliminado correctamente';
        }

        $this->redirectToCabra($control['id_cabra']);
    }

    private function getDataFromRequest(): array {
        return array_merge($_POST, [
            'registrado_por' => $_SESSION['user_id'] ?? null
        ]);
    }

    private function handleUbreUpload(): ?string {
        if (!isset($_FILES['foto_ubre']) || $_FILES['foto_ubre']['error'] !== UPLOAD_ERR_OK) return null;

        $file = $_FILES['foto_ubre'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('ubre_') . "." . $ext;
        $path = __DIR__ . '/../../public/uploads/ubres/';

        if (!is_dir($path)) mkdir($path, 0755, true);

        if (move_uploaded_file($file['tmp_name'], $path . $filename)) {
            return 'ubres/' . $filename;
        }

        return null;
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'create_edit' => __DIR__ . '/../views/controles/create_edit_control.php'
        ];

        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada';
            $this->redirectToCabra($data['id_cabra'] ?? '');
        }
    }
}
