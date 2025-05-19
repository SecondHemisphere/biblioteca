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
    // Configuración de paginación
        $por_pagina = 10;
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Obtener todos los estudiantes
        $students = $this->studentModel->getAllStudents();
        
        $data = [
            'title' => 'Listado de Estudiantes',
            'students' => $students,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null
        ];

        // Limpiar mensajes flash
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);
        
        $current_page = 'students';
        
        require_once __DIR__ . '/../views/layouts/navbar.php';
        require_once __DIR__ . '/../views/students/index.php';
        require_once __DIR__ . '/../views/layouts/sidebar.php';
    }

    public function create() {
        $data = [
            'title' => 'Registrar Nuevo Estudiante',
            'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración']
        ];
        
        $current_page = 'students';
        
        require_once __DIR__ . '/../views/layouts/navbar.php';
        require_once __DIR__ . '/../views/students/create.php';
        require_once __DIR__ . '/../views/layouts/sidebar.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar DNI primero
            if (!$this->studentModel->validateDni($_POST['dni'])) {
                $_SESSION['error_message'] = 'El DNI debe tener 8 dígitos numéricos';
                header('Location: /students/create');
                exit;
            }

            $data = [
                'codigo' => trim($_POST['codigo']),
                'dni' => trim($_POST['dni']),
                'nombre' => trim($_POST['nombre']),
                'carrera' => trim($_POST['carrera']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono']),
                'estado' => trim($_POST['estado'])
            ];
            
            // Verificar si el DNI ya existe
            if ($this->studentModel->findStudentByDni($data['dni'])) {
                $_SESSION['error_message'] = 'El DNI ya está registrado';
                header('Location: /students/create');
                exit;
            }
            
            if ($this->studentModel->register($data)) {
                $_SESSION['success_message'] = 'Estudiante registrado correctamente';
            } else {
                $_SESSION['error_message'] = 'Error al registrar el estudiante';
            }
            
            header('Location: /students');
            exit;
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
            'careers' => ['Ingeniería de sistemas', 'Ingeniería', 'Medicina', 'Derecho', 'Administración']
        ];
        
        $current_page = 'students';
        
        require_once __DIR__ . '/../views/layouts/navbar.php';
        require_once __DIR__ . '/../views/students/edit.php';
        require_once __DIR__ . '/../views/layouts/sidebar.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar DNI primero
            if (!$this->studentModel->validateDni($_POST['dni'])) {
                $_SESSION['error_message'] = 'El DNI debe tener 8 dígitos numéricos';
                header("Location: /students/edit/$id");
                exit;
            }

            $data = [
                'codigo' => trim($_POST['codigo']),
                'dni' => trim($_POST['dni']),
                'nombre' => trim($_POST['nombre']),
                'carrera' => trim($_POST['carrera']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono']),
                'estado' => trim($_POST['estado'])
            ];
            
            // Verificar si el DNI ya existe en otro estudiante
            $existingStudent = $this->studentModel->findStudentByDni($data['dni']);
            if ($existingStudent && $existingStudent->id != $id) {
                $_SESSION['error_message'] = 'El DNI ya está registrado por otro estudiante';
                header("Location: /students/edit/$id");
                exit;
            }
            
            if ($this->studentModel->update($id, $data)) {
                $_SESSION['success_message'] = 'Estudiante actualizado correctamente';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el estudiante';
            }
            
            header('Location: /students');
            exit;
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