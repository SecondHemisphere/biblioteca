<?php
class PublisherController
{
    private $db;
    private $publisherModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->publisherModel = new Publisher($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    // Muestra el listado de editoriales
    public function index()
    {
        $publishers = $this->publisherModel->getAll();

        $data = [
            'title' => 'Listado de Editoriales',
            'publishers' => $publishers,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'publishers'
        ];

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        $view = __DIR__ . '/../views/publishers/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Muestra el formulario de registro
    public function create()
    {
        $data = [
            'title' => 'Registrar Nueva Editorial',
            'publisher' => new stdClass(),
            'errors' => [],
            'form_action' => '/publishers/store',
            'current_page' => 'publishers'
        ];

        $view = __DIR__ . '/../views/publishers/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el registro
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'editorial' => trim($_POST['editorial']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $validation = $this->publisherModel->validatePublisherData($dataInput);

            if ($validation === true) {
                $this->publisherModel->create($dataInput);
                $_SESSION['success_message'] = 'Editorial registrada correctamente';
                header('Location: /publishers');
                exit;
            } else {
                $data = [
                    'title' => 'Registrar Nueva Editorial',
                    'publisher' => (object) $dataInput,
                    'errors' => $validation,
                    'form_action' => '/publishers/store',
                    'current_page' => 'publishers'
                ];

                $view = __DIR__ . '/../views/publishers/create.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Muestra el formulario de edición
    public function edit($id)
    {
        $publisher = $this->publisherModel->getById($id);

        if (!$publisher) {
            $_SESSION['error_message'] = 'Editorial no encontrada';
            header('Location: /publishers');
            exit;
        }

        $data = [
            'title' => 'Editar Editorial',
            'publisher' => $publisher,
            'errors' => [],
            'form_action' => "/publishers/update/$id",
            'current_page' => 'publishers'
        ];

        $view = __DIR__ . '/../views/publishers/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa la actualización
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'editorial' => trim($_POST['editorial']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $currentPublisher = $this->publisherModel->getById($id);
            $validation = $this->publisherModel->validateUpdateData($dataInput, $currentPublisher);

            if ($validation === true) {
                $result = $this->publisherModel->update($id, $dataInput);

                $_SESSION['success_message'] = $result
                    ? 'Editorial actualizada correctamente'
                    : 'Error al actualizar la editorial';

                header('Location: /publishers');
                exit;
            } else {
                $originalPublisher = $this->publisherModel->getById($id);

                $data = [
                    'title' => 'Editar Editorial',
                    'publisher' => (object) array_merge((array) $originalPublisher, $dataInput),
                    'errors' => $validation,
                    'form_action' => "/publishers/update/$id",
                    'current_page' => 'publishers'
                ];

                $view = __DIR__ . '/../views/publishers/edit.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Elimina una editorial
    public function delete($id)
    {
        $_SESSION['success_message'] = $this->publisherModel->delete($id)
            ? 'Editorial eliminada correctamente'
            : 'Error al eliminar la editorial';

        header('Location: /publishers');
        exit;
    }

    // Verifica si hay sesión iniciada
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
