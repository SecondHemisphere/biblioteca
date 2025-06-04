<?php
class BookController
{
    private $db;
    private $bookModel;
    private $authorModel;
    private $publisherModel;
    private $subjectModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->bookModel = new Book($db);
        $this->authorModel = new Author($db);
        $this->publisherModel = new Publisher($db);
        $this->subjectModel = new Subject($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    // Listado de libros
    public function index()
    {
        $books = $this->bookModel->getAllBooks();

        $data = [
            'title' => 'Listado de Libros',
            'books' => $books,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'books'
        ];

        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $view = __DIR__ . '/../views/books/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Formulario para registrar libro
    public function create()
    {
        $data = [
            'title' => 'Registrar Nuevo Libro',
            'book' => new stdClass(),
            'errors' => [],
            'form_action' => '/books/store',
            'current_page' => 'books',
            'autores' => $this->authorModel->getAll(),
            'editoriales' => $this->publisherModel->getAll(),
            'materias' => $this->subjectModel->getAllSubjects()
        ];

        $view = __DIR__ . '/../views/books/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el registro del libro
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'portada' => trim($_POST['portada']),
                'id_autor' => trim($_POST['id_autor']),
                'id_editorial' => trim($_POST['id_editorial']),
                'id_materia' => trim($_POST['id_materia']),
                'anio_edicion' => trim($_POST['anio_edicion']),
                'num_paginas' => trim($_POST['num_paginas']),
                'isbn' => trim($_POST['isbn']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $validation = $this->bookModel->validateBookData($dataInput);

            if ($validation === true) {
                $this->bookModel->register($dataInput);
                $_SESSION['success_message'] = 'Libro registrado correctamente';
                header('Location: /books');
                exit;
            } else {
                $data = [
                    'title' => 'Registrar Nuevo Libro',
                    'book' => (object) $dataInput,
                    'errors' => $validation,
                    'form_action' => '/books/store',
                    'current_page' => 'books',
                    'autores' => $this->authorModel->getAll(),
                    'editoriales' => $this->publisherModel->getAll(),
                    'materias' => $this->subjectModel->getAllSubjects()
                ];

                $view = __DIR__ . '/../views/books/create.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Formulario de edición
    public function edit($id)
    {
        $book = $this->bookModel->getBookById($id);

        $authorModel = new Author($this->db);
        $editorialModel = new Publisher($this->db);
        $materiaModel = new Subject($this->db);

        if (!$book) {
            $_SESSION['error_message'] = 'Libro no encontrado';
            header('Location: /books');
            exit;
        }

        $data = [
            'title' => 'Editar Libro',
            'book' => $book,
            'errors' => [],
            'form_action' => "/books/update/$id",
            'current_page' => 'books',
            'autores' => $this->authorModel->getAll(),
            'editoriales' => $this->publisherModel->getAll(),
            'materias' => $this->subjectModel->getAllSubjects()
        ];

        $view = __DIR__ . '/../views/books/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa actualización
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'portada' => trim($_POST['portada']),
                'id_autor' => trim($_POST['id_autor']),
                'id_editorial' => trim($_POST['id_editorial']),
                'id_materia' => trim($_POST['id_materia']),
                'anio_edicion' => trim($_POST['anio_edicion']),
                'num_paginas' => trim($_POST['num_paginas']),
                'isbn' => trim($_POST['isbn']),
                'estado' => (int) trim($_POST['estado'])
            ];

            $currentBook = $this->bookModel->getBookById($id);
            $validation = $this->bookModel->validateUpdateData($dataInput, $currentBook);

            if ($validation === true) {
                $result = $this->bookModel->update($id, $dataInput);

                $_SESSION['success_message'] = $result['success']
                    ? 'Libro actualizado correctamente'
                    : 'Error al actualizar el libro';

                header('Location: /books');
                exit;
            } else {
                $data = [
                    'title' => 'Editar Libro',
                    'book' => (object) array_merge((array) $currentBook, $dataInput),
                    'errors' => $validation,
                    'form_action' => "/books/update/$id",
                    'current_page' => 'books',
                    'autores' => $this->authorModel->getAll(),
                    'editoriales' => $this->publisherModel->getAll(),
                    'materias' => $this->subjectModel->getAllSubjects()
                ];

                $view = __DIR__ . '/../views/books/edit.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Eliminar libro
    public function delete($id)
    {
        $_SESSION['success_message'] = $this->bookModel->delete($id)
            ? 'Libro eliminado correctamente'
            : 'Error al eliminar el libro';

        header('Location: /books');
        exit;
    }

    // Verifica si está autenticado
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
