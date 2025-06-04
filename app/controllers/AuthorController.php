<?php
class AuthorController
{
    private $db;
    private $authorModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->authorModel = new Author($db);

        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    // Muestra el listado de autores
    public function index()
    {
        $authors = $this->authorModel->getAll();

        $data = [
            'title' => 'Listado de Autores',
            'authors' => $authors,
            'success_message' => $_SESSION['success_message'] ?? null,
            'error_message' => $_SESSION['error_message'] ?? null,
            'current_page' => 'authors'
        ];

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        $view = __DIR__ . '/../views/authors/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Muestra el formulario de registro
    public function create()
    {
        $data = [
            'title' => 'Registrar Nuevo Autor',
            'author' => new stdClass(),
            'errors' => [],
            'form_action' => '/authors/store',
            'current_page' => 'authors'
        ];

        // Inicializar propiedades vacías para evitar warnings en la vista
        $data['author']->nombres = '';
        $data['author']->apellidos = '';
        $data['author']->fecha_nacimiento = '';
        $data['author']->fecha_fallecimiento = '';
        $data['author']->nacionalidad = '';
        $data['author']->campo_estudio = '';
        $data['author']->biografia = '';
        $data['author']->estado = 1;

        $view = __DIR__ . '/../views/authors/create.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el registro
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'nombres' => trim($_POST['nombres']),
                'apellidos' => trim($_POST['apellidos']),
                'fecha_nacimiento' => !empty($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : null,
                'fecha_fallecimiento' => !empty($_POST['fecha_fallecimiento']) ? trim($_POST['fecha_fallecimiento']) : null,
                'nacionalidad' => !empty($_POST['nacionalidad']) ? trim($_POST['nacionalidad']) : null,
                'campo_estudio' => !empty($_POST['campo_estudio']) ? trim($_POST['campo_estudio']) : null,
                'biografia' => !empty($_POST['biografia']) ? trim($_POST['biografia']) : null,
                'estado' => isset($_POST['estado']) ? (int) $_POST['estado'] : 1,
                'imagen' => $_FILES['imagen'] ?? null,
                'eliminar_imagen' => $_POST['eliminar_imagen'] ?? 0
            ];

            $validation = $this->authorModel->validateAuthorData($dataInput);

            if ($validation === true) {
                $result = $this->authorModel->create($dataInput);

                if ($result['success']) {
                    $_SESSION['success_message'] = 'Autor registrado correctamente';
                    header('Location: /authors');
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Error al registrar el autor';
                    header('Location: /authors/create');
                    exit;
                }
            } else {
                $data = [
                    'title' => 'Registrar Nuevo Autor',
                    'author' => (object) $dataInput,
                    'errors' => $validation,
                    'form_action' => '/authors/store',
                    'current_page' => 'authors'
                ];

                $view = __DIR__ . '/../views/authors/create.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Muestra el formulario de edición
    public function edit($id)
    {
        $author = $this->authorModel->getById($id);

        if (!$author) {
            $_SESSION['error_message'] = 'Autor no encontrado';
            header('Location: /authors');
            exit;
        }

        $data = [
            'title' => 'Editar Autor',
            'author' => $author,
            'errors' => [],
            'form_action' => "/authors/update/$id",
            'current_page' => 'authors'
        ];

        $view = __DIR__ . '/../views/authors/edit.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa la actualización
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInput = [
                'nombres' => trim($_POST['nombres']),
                'apellidos' => trim($_POST['apellidos']),
                'fecha_nacimiento' => !empty($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : null,
                'fecha_fallecimiento' => !empty($_POST['fecha_fallecimiento']) ? trim($_POST['fecha_fallecimiento']) : null,
                'nacionalidad' => !empty($_POST['nacionalidad']) ? trim($_POST['nacionalidad']) : null,
                'campo_estudio' => !empty($_POST['campo_estudio']) ? trim($_POST['campo_estudio']) : null,
                'biografia' => !empty($_POST['biografia']) ? trim($_POST['biografia']) : null,
                'estado' => isset($_POST['estado']) ? (int) $_POST['estado'] : 1,
                'imagen' => $_FILES['imagen']['size'] > 0 ? $_FILES['imagen'] : null
            ];

            $currentAuthor = $this->authorModel->getById($id);

            $eliminarImagen = isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == 1;

            $validation = $this->authorModel->validateUpdateData($dataInput, $currentAuthor);

            if ($validation === true) {
                $result = $this->authorModel->update($id, $dataInput, $eliminarImagen);

                $_SESSION['success_message'] = $result['success']
                    ? 'Autor actualizado correctamente'
                    : 'Error al actualizar el autor';

                header('Location: /authors');
                exit;
            } else {
                $originalAuthor = $this->authorModel->getById($id);

                // Combinar datos originales con los nuevos para mostrar en el formulario
                $authorData = (array) $originalAuthor;
                foreach ($dataInput as $key => $value) {
                    $authorData[$key] = $value;
                }

                $data = [
                    'title' => 'Editar Autor',
                    'author' => (object) $authorData,
                    'errors' => $validation,
                    'form_action' => "/authors/update/$id",
                    'current_page' => 'authors'
                ];

                $view = __DIR__ . '/../views/authors/edit.php';
                require_once __DIR__ . '/../views/layouts/layout.php';
            }
        }
    }

    // Elimina un autor
    public function delete($id)
    {
        $result = $this->authorModel->delete($id);

        $_SESSION['success_message'] = $result
            ? 'Autor eliminado correctamente'
            : 'Error al eliminar el autor';

        header('Location: /authors');
        exit;
    }

    // Verifica si hay sesión iniciada
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
