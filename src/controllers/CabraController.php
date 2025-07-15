<?php
require_once __DIR__ . '/../models/Parto.php';
require_once __DIR__ . '/../models/Cabras.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../services/PDFService.php';

class CabraController
{
    private $cabra;
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cabra = new Cabra($this->db);
    }

    private function isLoggedIn()
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;

        if ($isLoginPage) {
            return false;
        }

        return isset($_SESSION['user_id']) &&
            isset($_SESSION['login_time']) &&
            (time() - $_SESSION['login_time'] < SESSION_TIMEOUT);
    }

    private function redirectToLogin()
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        $isLoginPage = strpos($currentUrl, '/login') !== false;

        if (!$isLoginPage) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }

    private function redirectToCabras()
    {
        header("Location: " . BASE_URL . "/cabras");
        exit();
    }

    private function redirectToCabraEdit($id)
    {
        header("Location: " . BASE_URL . "/cabras/$id/edit");
        exit();
    }

    private function redirectToCabraShow($id)
    {
        header("Location: " . BASE_URL . "/cabras/$id");
        exit();
    }

    public function generarPDF()
    {
        if (!isset($_GET['id'])) return;

        $id = (int)$_GET['id'];
        $pdfService = new PDFService($this->db);
        $pdfService->generarFichaCabra($id);
    }

    // NUEVA FUNCIÓN: Extraer ID de la URL
    private function getIdFromUrl()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // Buscar el segmento después de 'cabras'
        $cabrasIndex = array_search('cabras', $segments);
        if ($cabrasIndex !== false && isset($segments[$cabrasIndex + 1])) {
            $id = (int)$segments[$cabrasIndex + 1];
            return $id > 0 ? $id : null;
        }

        // Fallback a $_GET['id']
        return isset($_GET['id']) ? (int)$_GET['id'] : null;
    }

    // Mostrar lista de cabras
    public function index()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6;
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
    public function create()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        // Debug: Verificar qué datos se están obteniendo
        $males = $this->cabra->getBySex('MACHO');
        $females = $this->cabra->getBySex('HEMBRA');

        // Debug temporal - puedes eliminarlo después
        error_log("Males encontrados: " . count($males));
        error_log("Females encontradas: " . count($females));

        $data = [
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $males,
            'females' => $females
        ];

        $this->loadView('create', $data);
    }

    // Procesar creación de cabra
    public function store()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToCabras();
            return;
        }

        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido o expirado";
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/cabras/create');
            exit();
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
    public function show()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = $this->getIdFromUrl();

        if (!$id || $id <= 0) {
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

        $partoModel = new Parto($this->db);
        $partos = $partoModel->getByCabra($id);

        $genealogia = $this->cabra->getAncestros($id);

        $data = [
            'cabra' => $cabra,
            'partos' => $partos,
            'genealogia' => $genealogia
        ];

        $this->loadView('show', $data);
    }


    // Mostrar formulario de edición
    public function edit()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        $id = $this->getIdFromUrl();

        if (!$id || $id <= 0) {
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

        // Obtener machos y hembras, excluyendo la cabra actual
        $males = $this->cabra->getBySexExcluding('MACHO', $id);
        $females = $this->cabra->getBySexExcluding('HEMBRA', $id);

        // Debug temporal
        error_log("Editando cabra ID: $id");
        error_log("Males disponibles: " . count($males));
        error_log("Females disponibles: " . count($females));

        $data = [
            'cabra' => $cabra,
            'breeds' => $this->getBreeds(),
            'owners' => $this->getOwners(),
            'males' => $males,
            'females' => $females
        ];

        $this->loadView('edit', $data);
    }

    // MÉTODO CORREGIDO: Procesar actualización de cabra
    public function update()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToCabras();
            return;
        }

        // CORRECCIÓN: Obtener ID de la URL, no del POST
        $id = $this->getIdFromUrl();

        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido para actualización';
            $this->redirectToCabras();
            return;
        }

        // Verificar que la cabra existe antes de proceder
        $existingCabra = $this->cabra->getById($id);
        if (!$existingCabra) {
            $_SESSION['error'] = 'La cabra que intenta actualizar no existe';
            $this->redirectToCabras();
            return;
        }

        // Verificar token CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token de seguridad inválido o expirado";
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . BASE_URL . '/cabras/' . $id . '/edit');
            exit();
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
    public function delete()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        // Obtener ID de la URL (para GET) o del POST
        $id = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si viene por POST, obtener del body
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        } else {
            // Si viene por GET, obtener de la URL
            $id = $this->getIdFromUrl();
        }

        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID de cabra inválido';
            $this->redirectToCabras();
            return;
        }

        // Verificar que la cabra existe
        $existingCabra = $this->cabra->getById($id);
        if (!$existingCabra) {
            $_SESSION['error'] = 'La cabra que intenta eliminar no existe';
            $this->redirectToCabras();
            return;
        }

        // Si es GET, mostrar confirmación
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = ['cabra' => $existingCabra];
            $this->loadView('delete', $data);
            return;
        }

        // Si es POST, proceder con la eliminación
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar token CSRF si se envía
            if (isset($_POST['csrf_token']) && !verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Token de seguridad inválido o expirado";
                $this->redirectToCabras();
                return;
            }

            if ($this->cabra->delete($id, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'Cabra eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la cabra';
            }
        }

        $this->redirectToCabras();
    }



    // Buscar cabras
    public function search()
    {
        $term = isset($_GET['term']) ? trim($_GET['term']) : '';

        if (empty($term)) {
            header('Location: ' . BASE_URL . '/cabras');
            exit();
        }

        $cabras = $this->cabra->search($term);
        if (!is_array($cabras)) $cabras = [];

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
    public function stats()
    {
        $stats = $this->cabra->getStats();
        $data = ['stats' => $stats];
        $this->loadView('stats', $data);
    }

    // Métodos auxiliares
    private function getBreeds()
    {
        try {
            $query = "SELECT * FROM razas ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    private function getOwners()
    {
        try {
            $query = "SELECT * FROM propietarios ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    private function validateCabraData($data)
    {
        $errors = [];

        if (empty($data['nombre']) || strlen(trim($data['nombre'])) < 3) {
            $errors[] = 'El nombre es obligatorio y debe tener al menos 3 caracteres.';
        }

        if (empty($data['sexo']) || !in_array($data['sexo'], ['MACHO', 'HEMBRA'])) {
            $errors[] = 'El sexo debe ser MACHO o HEMBRA.';
        }

        if (!empty($data['fecha_nacimiento'])) {
            $fecha = DateTime::createFromFormat('Y-m-d', $data['fecha_nacimiento']);
            if (!$fecha || $fecha->format('Y-m-d') !== $data['fecha_nacimiento']) {
                $errors[] = 'Fecha de nacimiento inválida.';
            } elseif ($fecha > new DateTime()) {
                $errors[] = 'La fecha de nacimiento no puede ser futura.';
            }
        } else {
            $errors[] = 'La fecha de nacimiento es obligatoria.';
        }

        if (!empty($data['color']) && strlen($data['color']) < 3) {
            $errors[] = 'El color debe tener al menos 3 caracteres.';
        }

        if (!empty($data['id_raza']) && !is_numeric($data['id_raza'])) {
            $errors[] = 'La raza seleccionada no es válida.';
        }

        if (!empty($data['id_propietario_actual']) && !is_numeric($data['id_propietario_actual'])) {
            $errors[] = 'El propietario seleccionado no es válido.';
        }

        if (!empty($data['madre']) && !empty($data['padre']) && $data['madre'] == $data['padre']) {
            $errors[] = 'La madre y el padre no pueden ser la misma cabra.';
        }

        $madreId = !empty($data['madre']) ? $data['madre'] : null;
        $padreId = !empty($data['padre']) ? $data['padre'] : null;
        $parentError = $this->cabra->validarParentesco($madreId, $padreId);
        if ($parentError) {
            $errors[] = $parentError;
        }


        // Validación de edad de padres respecto al hijo
        if (!empty($data['fecha_nacimiento'])) {
            $fechaHijo = DateTime::createFromFormat('Y-m-d', $data['fecha_nacimiento']);

            if ($madreId) {
                $madre = $this->cabra->getById($madreId);
                if (!empty($madre['fecha_nacimiento'])) {
                    $fechaMadre = new DateTime($madre['fecha_nacimiento']);
                    if ($fechaMadre >= $fechaHijo) {
                        $errors[] = 'La madre no puede ser más joven que el hijo o tener la misma edad.';
                    }
                }
            }

            if ($padreId) {
                $padre = $this->cabra->getById($padreId);
                if (!empty($padre['fecha_nacimiento'])) {
                    $fechaPadre = new DateTime($padre['fecha_nacimiento']);
                    if ($fechaPadre >= $fechaHijo) {
                        $errors[] = 'El padre no puede ser más joven que el hijo o tener la misma edad.';
                    }
                }
            }
            // Validación de consanguinidad: evitar que madre y padre estén relacionados por descendencia
        if ($madreId && $padreId) {
            $ancestrosMadre = $this->cabra->getAncestors($madreId);
            $ancestrosPadre = $this->cabra->getAncestors($padreId);
            if (in_array($padreId, $ancestrosMadre)) {
                $errors[] = 'No se puede seleccionar como padre a un hijo de la madre (relación consanguínea).';
            }
            if (in_array($madreId, $ancestrosPadre)) {
                $errors[] = 'No se puede seleccionar como madre a una hija del padre (relación consanguínea).';
            }

            $madreData = $this->cabra->getById($madreId);
            $padreData = $this->cabra->getById($padreId);

            if (
                ($madreData['madre'] && $madreData['madre'] === $padreData['madre']) ||
                ($madreData['padre'] && $madreData['padre'] === $padreData['padre'])
            ) {
                $errors[] = 'La madre y el padre no pueden ser hermanos (comparten al menos uno de los padres).';
            }

            // Validar que madre no sea sobrina del padre o viceversa (tío ↔ sobrina)
            $padreAncestros = $this->cabra->getAncestors($padreId);
            if (in_array($madreData['madre'], $padreAncestros) || in_array($madreData['padre'], $padreAncestros)) {
                $errors[] = 'La madre no puede ser sobrina del padre.';
            }

            $madreAncestros = $this->cabra->getAncestors($madreId);
            if (in_array($padreData['madre'], $madreAncestros) || in_array($padreData['padre'], $madreAncestros)) {
                $errors[] = 'El padre no puede ser sobrino de la madre.';
            }
        }
        }

        return $errors;
    }


    private function handlePhotoUpload($file)
    {
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



    private function loadView($view, $data = [])
    {
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
