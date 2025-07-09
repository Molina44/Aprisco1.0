<?php
// src/controllers/DocumentosCabrasController.php

require_once __DIR__ . '/../models/DocumentosCabras.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

class DocumentosCabrasController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new DocumentosCabras($this->db);
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
        $index = array_search('documentos', $segments);
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

        $id_cabra = $_POST['id_cabra'] ?? null;
        $tipo = $_POST['tipo_documento'] ?? '';
        $archivo = $_FILES['ruta_archivo'] ?? null;

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token CSRF inválido';
            return $this->redirectToCabra($id_cabra);
        }

        $filename = null;
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            $dir = __DIR__ . '/../../public/uploads/documentos/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = uniqid('doc_') . '_' . basename($archivo['name']);
            move_uploaded_file($archivo['tmp_name'], $dir . $filename);
        }

        $this->model->create([
            'id_cabra' => $id_cabra,
            'tipo_documento' => $tipo,
            'ruta_archivo' => 'documentos/' . $filename,
            'subido_por' => $_SESSION['user_id']
        ]);

        $_SESSION['success'] = 'Documento subido correctamente';
        $this->redirectToCabra($id_cabra);
    }

    public function delete() {
        if (!$this->isLoggedIn()) $this->redirectToLogin();

        $id = $this->getIdFromUrl();
        $doc = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Documento eliminado correctamente';
        }

        $this->redirectToCabra($doc['id_cabra']);
    }

    private function loadView($view, $data = []) {
        extract($data);
        $views = [
            'create_edit' => __DIR__ . '/../views/documentos/create_edit_documento.php'
        ];
        if (isset($views[$view]) && file_exists($views[$view])) {
            include $views[$view];
        } else {
            $_SESSION['error'] = 'Vista no encontrada';
            $this->redirectToCabra($data['id_cabra'] ?? '');
        }
    }
}
