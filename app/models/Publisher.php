<?php
class Publisher
{
    private $db;

    /**
     * Constructor
     * @param Database $db Instancia de conexión a base de datos
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Obtiene todas las editoriales
     * @return array Lista de editoriales
     */
    public function getAll()
    {
        $this->db->query('SELECT * FROM editoriales ORDER BY id DESC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene una editorial por ID
     * @param int $id
     * @return object|false
     */
    public function getById($id)
    {
        $this->db->query('SELECT * FROM editoriales WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Crea una nueva editorial
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        $validation = $this->validatePublisherData($data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('INSERT INTO editoriales (editorial, estado) VALUES (:editorial, :estado)');
        $this->db->bind(':editorial', $data['editorial']);
        $this->db->bind(':estado', $data['estado'] ?? 1);

        $success = $this->db->execute();
        return [
            'success' => $success,
            'id' => $success ? $this->db->lastInsertId() : null
        ];
    }

    /**
     * Actualiza una editorial
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        $editorial = $this->getById($id);
        if (!$editorial) {
            return ['success' => false, 'errors' => ['general' => 'Editorial no encontrada']];
        }

        $validation = $this->validatePublisherData($data, $id);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('UPDATE editoriales SET editorial = :editorial, estado = :estado WHERE id = :id');
        $this->db->bind(':editorial', $data['editorial']);
        $this->db->bind(':estado', $data['estado']);
        $this->db->bind(':id', $id);

        $success = $this->db->execute();
        return ['success' => $success];
    }

    /**
     * Elimina una editorial
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->query('DELETE FROM editoriales WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Busca una editorial por nombre exacto
     * @param string $nombre
     * @return object|false
     */
    public function findByName($nombre)
    {
        $this->db->query('SELECT * FROM editoriales WHERE editorial = :editorial');
        $this->db->bind(':editorial', $nombre);
        return $this->db->single();
    }

    /**
     * Valida que el nombre tenga el formato correcto
     * @param string $name Nombre a validar
     * @return bool True si es válido
     */
    public function validateName($name)
    {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $name);
    }

    /**
     * Valida datos de editorial
     * @param array $data
     * @param int|null $id
     * @return array|true
     */
    public function validatePublisherData($data, $id = null)
    {
        $errors = [];

        // Validar nombre
        if (empty($data['editorial'])) {
            $errors['editorial'] = 'El nombre es requerido';
        } elseif (strlen($data['editorial']) > 100) {
            $errors['editorial'] = 'El nombre no puede exceder los 100 caracteres';
        } elseif (!$this->validateName($data['editorial'])) {
            $errors['editorial'] = 'El nombre solo debe contener letras y espacios';
        } elseif ($this->findByName($data['editorial'])) {
            $errors['editorial'] = 'El nombre ya está registrado';
        }

        return empty($errors) ? true : $errors;
    }


    /**
     * Valida los datos para actualizar una editorial
     * @param array $data Datos a validar
     * @param object $currentPublisher Editorial actual (de la base de datos)
     * @return array|true Lista de errores o true si es válido
     */
    public function validateUpdateData($data, $currentPublisher)
    {
        $errors = [];

        // Validar nombre
        if (empty($data['editorial'])) {
            $errors['editorial'] = 'El nombre es requerido';
        } elseif (strlen($data['editorial']) > 100) {
            $errors['editorial'] = 'El nombre no puede exceder los 100 caracteres';
        } elseif (!$this->validateName($data['editorial'])) {
            $errors['editorial'] = 'El nombre solo debe contener letras y espacios';
        } elseif ($data['editorial'] !== $currentPublisher->editorial && $this->findByName($data['editorial'])) {
            $errors['editorial'] = 'El nombre ya está registrado';
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Obtiene el total de editoriales
     * @return int
     */
    public function getTotal()
    {
        $this->db->query('SELECT COUNT(*) as total FROM editoriales');
        $result = $this->db->single();
        return $result->total;
    }
}
