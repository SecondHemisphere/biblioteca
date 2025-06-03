<?php
class StudentController
{
    private $db;
    private $studentModel;

    // Constructor: inicializa el modelo y redirige al login si el usuario no está autenticado
    public function __construct($db)
    {
        $this->db = $db;
        $this->studentModel = new Student($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    // Muestra la lista de estudiantes
    public function index()
    {
        $students = $this->studentModel->getAllStudents();

        $data = [
            'title' => 'Listado de Estudiantes',
            'students' => $students,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'students'
        ];

        // Limpia los mensajes flash de sesión
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        $view = __DIR__ . '/../views/students/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Muestra el formulario para registrar un nuevo estudiante
    public function create()
    {
        $data = [
            'title' => 'Registrar Nuevo Estudiante',
            'careers' => require_once __DIR__ . '../../../config/carreras.php',
            'student' => new stdClass(), // Estudiante vacío
            'errors' => [],
            'form_action' => '/students/store',
            'current_page' => 'students'
        ];

        $view = __DIR__ . '/../views/students/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el formulario de registro de estudiante
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura y limpia los datos del formulario
            $dataInput = [
                'codigo'    => trim($_POST['codigo']),
                'dni'       => trim($_POST['dni']),
                'nombre'    => trim($_POST['nombre']),
                'carrera'   => trim($_POST['carrera']),
                'direccion' => trim($_POST['direccion']),
                'telefono'  => trim($_POST['telefono']),
                'estado'    => (int) trim($_POST['estado'])
            ];

            // Valida los datos ingresados
            $validation = $this->studentModel->validateStudentData($dataInput);

            if ($validation === true) {
                $this->studentModel->register($dataInput);
                $_SESSION['success_message'] = 'Estudiante registrado correctamente';
                header('Location: /students');
                exit;
            } else {
                // Retorna al formulario con los errores y datos ingresados
                $data = [
                    'title' => 'Registrar Nuevo Estudiante',
                    'student' => (object) $dataInput,
                    'errors' => $validation,
                    'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración'],
                    'form_action' => '/students/store',
                    'current_page' => 'students'
                ];

                $view = __DIR__ . '/../views/students/create.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Muestra el formulario de edición de un estudiante
    public function edit($id)
    {
        $student = $this->studentModel->getStudentById($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Estudiante no encontrado';
            header('Location: /students');
            exit;
        }

        $data = [
            'title' => 'Editar Estudiante',
            'student' => $student,
            'careers' => require_once __DIR__ . '../../../config/carreras.php',
            'errors' => [],
            'form_action' => "/students/update/$id",
            'current_page' => 'students'
        ];

        $view = __DIR__ . '/../views/students/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa la actualización de un estudiante
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'codigo'    => trim($_POST['codigo']),
                'dni'       => trim($_POST['dni']),
                'nombre'    => trim($_POST['nombre']),
                'carrera'   => trim($_POST['carrera']),
                'direccion' => trim($_POST['direccion']),
                'telefono'  => trim($_POST['telefono']),
                'estado'    => (int) trim($_POST['estado'])
            ];

            $currentStudent = $this->studentModel->getStudentById($id);
            $validation = $this->studentModel->validateUpdateData($dataInput, $currentStudent);

            if ($validation === true) {
                $result = $this->studentModel->update($id, $dataInput);

                $_SESSION['success_message'] = $result
                    ? 'Estudiante actualizado correctamente'
                    : 'Error al actualizar el estudiante';

                header('Location: /students');
                exit;
            } else {
                // Combina el original con los datos ingresados para repoblar el formulario
                $originalStudent = $this->studentModel->getStudentById($id);

                $data = [
                    'title' => 'Editar Estudiante',
                    'student' => (object) array_merge((array) $originalStudent, $dataInput),
                    'errors' => $validation,
                    'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración'],
                    'form_action' => "/students/update/$id",
                    'current_page' => 'students'
                ];

                $view = __DIR__ . '/../views/students/edit.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Elimina un estudiante
    public function delete($id)
    {
        $_SESSION['success_message'] = $this->studentModel->delete($id)
            ? 'Estudiante eliminado correctamente'
            : 'Error al eliminar el estudiante';

        header('Location: /students');
        exit;
    }

    // Verifica si el usuario está autenticado
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
