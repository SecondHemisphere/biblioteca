<?php
class SubjectController
{
    private $db;
    private $subjectModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->subjectModel = new Subject($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    // Muestra el listado de materias
    public function index()
    {
        $subjects = $this->subjectModel->getAllSubjects();

        $data = [
            'title' => 'Listado de Materias',
            'subjects' => $subjects,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'subjects'
        ];

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        $view = __DIR__ . '/../views/subjects/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Muestra el formulario de registro
    public function create()
    {
        $data = [
            'title' => 'Registrar Nueva Materia',
            'subject' => new stdClass(),
            'errors' => [],
            'form_action' => '/subjects/store',
            'current_page' => 'subjects'
        ];

        $view = __DIR__ . '/../views/subjects/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el registro
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'materia' => trim($_POST['materia']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $validation = $this->subjectModel->validateSubjectData($dataInput);

            if ($validation === true) {
                $this->subjectModel->create($dataInput);
                $_SESSION['success_message'] = 'Materia registrada correctamente';
                header('Location: /subjects');
                exit;
            } else {
                $data = [
                    'title' => 'Registrar Nueva Materia',
                    'subject' => (object) $dataInput,
                    'errors' => $validation,
                    'form_action' => '/subjects/store',
                    'current_page' => 'subjects'
                ];

                $view = __DIR__ . '/../views/subjects/create.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Muestra el formulario de edición
    public function edit($id)
    {
        $subject = $this->subjectModel->getSubjectById($id);

        if (!$subject) {
            $_SESSION['error_message'] = 'Materia no encontrada';
            header('Location: /subjects');
            exit;
        }

        $data = [
            'title' => 'Editar Materia',
            'subject' => $subject,
            'errors' => [],
            'form_action' => "/subjects/update/$id",
            'current_page' => 'subjects'
        ];

        $view = __DIR__ . '/../views/subjects/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa la actualización
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'materia' => trim($_POST['materia']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $currentSubject = $this->subjectModel->getSubjectById($id);
            $validation = $this->subjectModel->validateUpdateData($dataInput, $currentSubject);

            if ($validation === true) {
                $result = $this->subjectModel->update($id, $dataInput);

                $_SESSION['success_message'] = $result
                    ? 'Materia actualizada correctamente'
                    : 'Error al actualizar la materia';

                header('Location: /subjects');
                exit;
            } else {
                $originalSubject = $this->subjectModel->getSubjectById($id);

                $data = [
                    'title' => 'Editar Materia',
                    'subject' => (object) array_merge((array) $originalSubject, $dataInput),
                    'errors' => $validation,
                    'form_action' => "/subjects/update/$id",
                    'current_page' => 'subjects'
                ];

                $view = __DIR__ . '/../views/subjects/edit.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Elimina una materia
    public function delete($id)
    {
        $_SESSION['success_message'] = $this->subjectModel->delete($id)
            ? 'Materia eliminada correctamente'
            : 'Error al eliminar la materia';

        header('Location: /subjects');
        exit;
    }

    // Verifica si hay sesión iniciada
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
