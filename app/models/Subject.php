<?php
class Subject
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
     * Obtiene todas las materias
     * @return array Lista de materias
     */
    public function getAllSubjects()
    {
        $this->db->query('SELECT * FROM materias ORDER BY id DESC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene materias paginadas con búsqueda opcional
     * @param int $page Página actual
     * @param int $perPage Elementos por página
     * @param string|null $search Término de búsqueda
     * @return array Datos y total
     */
    public function getPaginatedSubjects($page = 1, $perPage = 10, $search = null)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM materias";
        $params = [];

        if ($search) {
            $sql .= " WHERE materia LIKE :search";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $perPage;
        $params[':offset'] = $offset;

        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return [
            'data' => $this->db->resultSet(),
            'total' => $this->getFilteredCount($search)
        ];
    }

    /**
     * Obtiene el total de materias con filtro
     * @param string|null $search
     * @return int
     */
    private function getFilteredCount($search = null)
    {
        $sql = "SELECT COUNT(*) as total FROM materias";
        if ($search) {
            $sql .= " WHERE materia LIKE :search";
            $this->db->query($sql);
            $this->db->bind(':search', "%$search%");
        } else {
            $this->db->query($sql);
        }

        $result = $this->db->single();
        return $result->total;
    }

    /**
     * Obtiene una materia por ID
     * @param int $id
     * @return object|false
     */
    public function getSubjectById($id)
    {
        $this->db->query('SELECT * FROM materias WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Crea una nueva materia
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        $validation = $this->validateSubjectData($data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('INSERT INTO materias (materia, estado) VALUES (:materia, :estado)');
        $this->db->bind(':materia', $data['materia']);
        $this->db->bind(':estado', $data['estado'] ?? 1);

        $success = $this->db->execute();
        return [
            'success' => $success,
            'id' => $success ? $this->db->lastInsertId() : null
        ];
    }

    /**
     * Actualiza una materia
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        $subject = $this->getSubjectById($id);
        if (!$subject) {
            return ['success' => false, 'errors' => ['general' => 'Materia no encontrada']];
        }

        $validation = $this->validateSubjectData($data, $id);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('UPDATE materias SET materia = :materia, estado = :estado WHERE id = :id');
        $this->db->bind(':materia', $data['materia']);
        $this->db->bind(':estado', $data['estado']);
        $this->db->bind(':id', $id);

        $success = $this->db->execute();
        return ['success' => $success];
    }

    /**
     * Elimina una materia
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->query('DELETE FROM materias WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Busca materia por nombre
     * @param string $nombre Nombre de la materia
     * @return object|false Objeto materia o false si no existe
     */
    public function findByName($nombre)
    {
        $this->db->query('SELECT * FROM materias WHERE materia = :materia');
        $this->db->bind(':materia', $nombre);
        return $this->db->single();
    }

    /**
     * Valida datos de materia
     * @param array $data
     * @param int|null $id ID opcional para actualización
     * @return array|true
     */
    public function validateSubjectData($data, $id = null)
    {
        $errors = [];

        if (empty($data['materia'])) {
            $errors['materia'] = 'El nombre de la materia es requerido';
        } elseif (strlen($data['materia']) > 100) {
            $errors['materia'] = 'El nombre no puede exceder los 100 caracteres';
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Valida los datos para actualizar una materia
     * @param array $data Datos a validar
     * @param object $currentSubject Materia actual (de la base de datos)
     * @return array|true Lista de errores o true si es válido
     */
    public function validateUpdateData($data, $currentSubject)
    {
        $errors = [];

        if (empty($data['materia'])) {
            $errors['materia'] = 'El nombre de la materia es requerido';
        } elseif (strlen($data['materia']) > 100) {
            $errors['materia'] = 'El nombre no puede exceder los 100 caracteres';
        } elseif (
            $data['materia'] !== $currentSubject->materia &&
            $this->findByName($data['materia'])
        ) {
            $errors['materia'] = 'La materia ya está registrada';
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Obtiene estadísticas por estado
     * @return array
     */
    public function getStatistics()
    {
        $this->db->query('
            SELECT
                estado,
                COUNT(*) as total
            FROM materias
            GROUP BY estado
        ');
        return $this->db->resultSet();
    }

    /**
     * Obtiene el total de materias
     * @return int
     */
    public function getTotalSubjects()
    {
        $this->db->query('SELECT COUNT(*) as total FROM materias');
        $result = $this->db->single();
        return $result->total;
    }
}
