<?php
class StudentController {
    private $db;
    private $studentModel;

    public function __construct($db) {
        $this->db = $db;
        $this->studentModel = new Student($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $students = $this->studentModel->getAllStudents();

        $data = [
            'title' => 'Listado de Estudiantes',
            'students' => $students,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'students'
        ];

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        $view = __DIR__ . '/../views/students/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    public function create() {
        $data = [
            'title' => 'Registrar Nuevo Estudiante',
            'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración'],
            'student' => new stdClass(),  // Evita error de variable no definida en el formulario
            'errors' => [],
            'form_action' => '/students/store',
            'current_page' => 'students'
        ];

        $view = __DIR__ . '/../views/students/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    public function store() {
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

            $validation = $this->studentModel->validateStudentData($dataInput);

            if ($validation === true) {
                $this->studentModel->register($dataInput);
                $_SESSION['success_message'] = 'Estudiante registrado correctamente';
                header('Location: /students');
                exit;
            } else {
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

    public function edit($id) {
        $student = $this->studentModel->getStudentById($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Estudiante no encontrado';
            header('Location: /students');
            exit;
        }

        $data = [
            'title' => 'Editar Estudiante',
            'student' => $student,
            'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración'],
            'errors' => [],
            'form_action' => "/students/update/$id",
            'current_page' => 'students'
        ];

        $view = __DIR__ . '/../views/students/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Recoger y limpiar datos
            $dataInput = [
                'codigo'    => trim($_POST['codigo'] ?? ''),
                'dni'       => trim($_POST['dni'] ?? ''),
                'nombre'    => trim($_POST['nombre'] ?? ''),
                'carrera'   => trim($_POST['carrera'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'telefono'  => trim($_POST['telefono'] ?? ''),
                'estado'    => (int) ($_POST['estado'] ?? 0)
            ];

            // 2. Depuración (puedes eliminar esto después)
            error_log("Datos recibidos para actualización: " . print_r($dataInput, true));

            // 3. Validación
            $validation = $this->studentModel->validateUpdateData($id, $dataInput);

            if ($validation === true) {
                // 4. Si la validación es exitosa
                $result = $this->studentModel->update($id, $dataInput);
                
                if ($result) {
                    $_SESSION['success_message'] = 'Estudiante actualizado correctamente';
                } else {
                    $_SESSION['error_message'] = 'Error al actualizar el estudiante';
                }
                
                header('Location: /students');
                exit;
            } else {
                // 5. Si hay errores de validación
                error_log("Errores de validación: " . print_r($validation, true));
                
                // Cargar los datos originales para mantener consistencia
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

    public function delete($id) {
        if ($this->studentModel->delete($id)) {
            $_SESSION['success_message'] = 'Estudiante eliminado correctamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el estudiante';
        }

        header('Location: /students');
        exit;
    }

    private function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
