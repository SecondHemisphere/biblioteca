<?php
class Book
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllBooks()
    {
        $this->db->query("
        SELECT
            l.*,
            CONCAT(au.nombres, ' ', au.apellidos) AS autor,
            ed.editorial AS editorial,
            m.materia AS materia
        FROM libros l
        JOIN autores au ON l.id_autor = au.id
        JOIN editoriales ed ON l.id_editorial = ed.id
        JOIN materias m ON l.id_materia = m.id
        ORDER BY l.id DESC
    ");

        return $this->db->resultSet();
    }


    // Obtener libro por ID
    public function getBookById($id)
    {
        $this->db->query("SELECT * FROM libros WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Registrar nuevo libro
    public function register($data)
    {
        $validation = $this->validateBookData($data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query("INSERT INTO libros (titulo, descripcion, portada, id_autor, id_editorial, id_materia, anio_edicion, num_paginas, isbn, estado)
                          VALUES (:titulo, :descripcion, :portada, :id_autor, :id_editorial, :id_materia, :anio_edicion, :num_paginas, :isbn, :estado)");

        $this->bindBookParams($data);
        $success = $this->db->execute();

        return [
            'success' => $success,
            'id' => $success ? $this->db->lastInsertId() : null
        ];
    }

    // Actualizar libro existente
    public function update($id, $data)
    {
        $current = $this->getBookById($id);
        if (!$current) {
            return ['success' => false, 'errors' => ['general' => 'Libro no encontrado']];
        }

        $validation = $this->validateUpdateData($data, $current);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sqlSet = implode(', ', $fields);

        $this->db->query("UPDATE libros SET $sqlSet WHERE id = :id");

        $this->bindBookParams($data);
        $this->db->bind(':id', $id);

        return ['success' => $this->db->execute()];
    }

    // Eliminar libro
    public function delete($id)
    {
        $this->db->query("DELETE FROM libros WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Buscar libro por título
    public function findBookByTitle($titulo)
    {
        $this->db->query("SELECT * FROM libros WHERE titulo = :titulo");
        $this->db->bind(':titulo', $titulo);
        return $this->db->single();
    }

    // Buscar libro por ISBN
    public function findBookByIsbn($isbn)
    {
        $this->db->query("SELECT * FROM libros WHERE isbn = :isbn");
        $this->db->bind(':isbn', $isbn);
        return $this->db->single();
    }

    // Obtener total de libros
    public function getTotalBooks()
    {
        $this->db->query("SELECT COUNT(*) as total FROM libros");
        $result = $this->db->single();
        return $result->total;
    }

    // Validar datos para nuevo libro
    public function validateBookData($data)
    {
        $errors = [];

        if (empty($data['titulo'])) {
            $errors['titulo'] = 'El título es obligatorio';
        } elseif (strlen($data['titulo']) > 255) {
            $errors['titulo'] = 'El título no puede exceder 255 caracteres';
        }

        if (empty($data['id_autor'])) {
            $errors['id_autor'] = 'Debe seleccionar un autor';
        }

        if (empty($data['id_editorial'])) {
            $errors['id_editorial'] = 'Debe seleccionar una editorial';
        }

        if (empty($data['id_materia'])) {
            $errors['id_materia'] = 'Debe seleccionar una materia';
        }

        if (empty($data['anio_edicion']) || !preg_match('/^\d{4}$/', $data['anio_edicion'])) {
            $errors['anio_edicion'] = 'Año de edición inválido';
        }

        if (!empty($data['isbn']) && $this->findBookByIsbn($data['isbn'])) {
            $errors['isbn'] = 'El ISBN ya está registrado';
        }

        return empty($errors) ? true : $errors;
    }

    // Validar datos para actualización
    public function validateUpdateData($data, $current)
    {
        $errors = [];

        if (empty($data['titulo'])) {
            $errors['titulo'] = 'El título es obligatorio';
        } elseif (strlen($data['titulo']) > 255) {
            $errors['titulo'] = 'El título no puede exceder 255 caracteres';
        }

        if (empty($data['id_autor'])) {
            $errors['id_autor'] = 'Debe seleccionar un autor';
        }

        if (empty($data['id_editorial'])) {
            $errors['id_editorial'] = 'Debe seleccionar una editorial';
        }

        if (empty($data['id_materia'])) {
            $errors['id_materia'] = 'Debe seleccionar una materia';
        }

        if (empty($data['anio_edicion']) || !preg_match('/^\d{4}$/', $data['anio_edicion'])) {
            $errors['anio_edicion'] = 'Año de edición inválido';
        }

        if (!empty($data['isbn']) && $data['isbn'] !== $current->isbn && $this->findBookByIsbn($data['isbn'])) {
            $errors['isbn'] = 'El ISBN ya está registrado';
        }

        return empty($errors) ? true : $errors;
    }

    // Enlazar parámetros de libro
    private function bindBookParams($data)
    {
        $fields = [
            'titulo',
            'descripcion',
            'portada',
            'id_autor',
            'id_editorial',
            'id_materia',
            'anio_edicion',
            'num_paginas',
            'isbn',
            'estado'
        ];

        foreach ($fields as $field) {
            $this->db->bind(":$field", $data[$field] ?? null);
        }
    }
}
