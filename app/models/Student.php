<?php
class Student
{
    private $db;

    /**
     * Constructor de la clase Student.
     * @param Database $db Instancia de la conexión a base de datos.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Obtiene todos los estudiantes (sin paginación)
     * @return array Lista de estudiantes
     */
    public function getAllStudents()
    {
        $this->db->query('SELECT * FROM estudiantes ORDER BY id DESC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene estudiantes paginados con posibilidad de búsqueda
     * @param int $page Página actual
     * @param int $perPage Items por página
     * @param string|null $search Término de búsqueda
     * @return array ['data' => estudiantes, 'total' => total registros]
     */
    public function getPaginatedStudents($page = 1, $perPage = 10, $search = null)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM estudiantes";
        $params = [];

        if ($search) {
            $sql .= " WHERE nombre LIKE :search OR dni LIKE :search OR codigo LIKE :search";
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
     * Obtiene el conteo total de estudiantes filtrados
     * @param string|null $search Término de búsqueda
     * @return int Total de registros
     */
    private function getFilteredCount($search = null)
    {
        $sql = "SELECT COUNT(*) as total FROM estudiantes";

        if ($search) {
            $sql .= " WHERE nombre LIKE :search OR dni LIKE :search OR codigo LIKE :search";
            $this->db->query($sql);
            $this->db->bind(':search', "%$search%");
        } else {
            $this->db->query($sql);
        }

        $result = $this->db->single();
        return $result->total;
    }

    /**
     * Obtiene un estudiante por su ID
     * @param int $id ID del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function getStudentById($id)
    {
        $this->db->query('SELECT * FROM estudiantes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Registra un nuevo estudiante con validación
     * @param array $data Datos del estudiante
     * @return array Resultado de la operación
     */
    public function register($data)
    {
        $validation = $this->validateStudentData($data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('INSERT INTO estudiantes (codigo, dni, nombre, carrera, direccion, telefono, estado)
                         VALUES (:codigo, :dni, :nombre, :carrera, :direccion, :telefono, :estado)');

        $this->db->bind(':codigo', $data['codigo']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':carrera', $data['carrera']);
        $this->db->bind(':direccion', $data['direccion']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':estado', $data['estado']);

        $success = $this->db->execute();
        return [
            'success' => $success,
            'id' => $success ? $this->db->lastInsertId() : null
        ];
    }

    /**
     * Actualiza un estudiante existente
     * @param int $id ID del estudiante
     * @param array $data Datos actualizados
     * @return array Resultado de la operación
     */
    public function update($id, $data)
    {
        $currentStudent = $this->getStudentById($id);
        if (!$currentStudent) {
            return ['success' => false, 'errors' => ['general' => 'Estudiante no encontrado']];
        }

        $validation = $this->validateUpdateData($data, $currentStudent);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        // Construimos dinámicamente el SQL SET
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sqlSet = implode(', ', $fields);

        $this->db->query("UPDATE estudiantes SET $sqlSet WHERE id = :id");

        // Enlazamos parámetros
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        $this->db->bind(':id', $id);

        $success = $this->db->execute();
        return ['success' => $success];
    }

    /**
     * Elimina un estudiante
     * @param int $id ID del estudiante
     * @return bool True si se eliminó correctamente
     */
    public function delete($id)
    {
        $this->db->query('DELETE FROM estudiantes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Busca estudiante por nombre
     * @param string $nombre Nombre del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function findStudentByName($nombre)
    {
        $this->db->query('SELECT * FROM estudiantes WHERE nombre = :nombre');
        $this->db->bind(':nombre', $nombre);
        return $this->db->single();
    }

    /**
     * Busca estudiante por código
     * @param string $codigo Código del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function findStudentByCode($codigo)
    {
        $this->db->query('SELECT * FROM estudiantes WHERE codigo = :codigo');
        $this->db->bind(':codigo', $codigo);
        return $this->db->single();
    }

    /**
     * Busca estudiante por DNI
     * @param string $dni DNI del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function findStudentByDni($dni)
    {
        $this->db->query('SELECT * FROM estudiantes WHERE dni = :dni');
        $this->db->bind(':dni', $dni);
        return $this->db->single();
    }

    /**
     * Valida los datos de un nuevo estudiante
     * @param array $data Datos a validar
     * @return array|true Lista de errores o true si es válido
     */
    public function validateStudentData($data)
    {
        $errors = [];

        // Validar código
        if (empty($data['codigo'])) {
            $errors['codigo'] = 'El código es requerido';
        } elseif (!$this->validateCode($data['codigo'])) {
            $errors['codigo'] = 'El código debe tener 10 caracteres';
        } elseif ($this->findStudentByCode($data['codigo'])) {
            $errors['codigo'] = 'El código ya está registrado';
        }

        // Validar DNI
        if (empty($data['dni'])) {
            $errors['dni'] = 'El DNI es requerido';
        } elseif (!$this->validateDni($data['dni'])) {
            $errors['dni'] = 'El DNI debe tener 10 dígitos numéricos';
        } elseif ($this->findStudentByDni($data['dni'])) {
            $errors['dni'] = 'El DNI ya está registrado';
        }

        // Validar nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) > 150) {
            $errors['nombre'] = 'El nombre no puede exceder los 150 caracteres';
        } elseif (!$this->validateName($data['nombre'])) {
            $errors['nombre'] = 'El nombre solo debe contener letras y espacios';
        } elseif ($this->findStudentByName($data['nombre'])) {
            $errors['nombre'] = 'El nombre ya está registrado';
        }

        // Validar carrera
        if (empty($data['carrera'])) {
            $errors['carrera'] = 'La carrera es requerida';
        } elseif (strlen($data['carrera']) > 150) {
            $errors['carrera'] = 'La carrera no puede exceder los 150 caracteres';
        }

        // Validar dirección
        if (empty($data['direccion'])) {
            $errors['direccion'] = 'La dirección es requerida';
        }

        // Validar teléfono
        if (empty($data['telefono'])) {
            $errors['telefono'] = 'El teléfono es requerido';
        } elseif (!$this->validatePhone($data['telefono'])) {
            $errors['telefono'] = 'El teléfono debe tener entre 7 y 15 dígitos numéricos';
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Valida los datos para actualización
     * @param array $data Datos a validar
     * @param object $currentStudent Estudiante actual (objeto)
     * @return array|true Lista de errores o true si es válido
     */
    public function validateUpdateData($data, $currentStudent)
    {
        $errors = [];

        // Validar código
        if (empty($data['codigo'])) {
            $errors['codigo'] = 'El código es requerido';
        } elseif (!$this->validateCode($data['codigo'])) {
            $errors['codigo'] = 'El código debe tener 10 caracteres';
        } elseif ($data['codigo'] !== $currentStudent->codigo && $this->findStudentByCode($data['codigo'])) {
            $errors['codigo'] = 'El código ya está registrado';
        }

        // Validar DNI
        if (empty($data['dni'])) {
            $errors['dni'] = 'El DNI es requerido';
        } elseif (!$this->validateDni($data['dni'])) {
            $errors['dni'] = 'El DNI debe tener 10 dígitos numéricos';
        } elseif ($data['dni'] !== $currentStudent->dni && $this->findStudentByDni($data['dni'])) {
            $errors['dni'] = 'El DNI ya está registrado';
        }

        // Validar nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) > 150) {
            $errors['nombre'] = 'El nombre no puede exceder los 150 caracteres';
        } elseif (!$this->validateName($data['nombre'])) {
            $errors['nombre'] = 'El nombre solo debe contener letras y espacios';
        } elseif ($data['nombre'] !== $currentStudent->nombre && $this->findStudentByName($data['nombre'])) {
            $errors['nombre'] = 'El nombre ya está registrado';
        }

        // Validar carrera
        if (empty($data['carrera'])) {
            $errors['carrera'] = 'La carrera es requerida';
        } elseif (strlen($data['carrera']) > 150) {
            $errors['carrera'] = 'La carrera no puede exceder los 150 caracteres';
        }

        // Validar dirección
        if (empty($data['direccion'])) {
            $errors['direccion'] = 'La dirección es requerida';
        }

        // Validar teléfono
        if (empty($data['telefono'])) {
            $errors['telefono'] = 'El teléfono es requerido';
        } elseif (!$this->validatePhone($data['telefono'])) {
            $errors['telefono'] = 'El teléfono debe tener entre 7 y 15 dígitos numéricos';
        }

        return empty($errors) ? true : $errors;
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
     * Valida que el DNI tenga el formato correcto
     * @param string $dni DNI a validar
     * @return bool True si es válido
     */
    public function validateDni($dni)
    {
        return preg_match('/^[0-9]{10}$/', $dni);
    }

    /**
     * Valida que el código tenga el formato correcto
     * @param string $code Código a validar
     * @return bool True si es válido
     */
    public function validateCode($code)
    {
        return preg_match('/^[A-Za-z0-9]{10}$/', $code);
    }

    /**
     * Valida que el teléfono tenga el formato correcto
     * @param string $phone Teléfono a validar
     * @return bool True si es válido
     */
    public function validatePhone($phone)
    {
        return preg_match('/^\d{7,15}$/', $phone);
    }

    /**
     * Obtiene el total de estudiantes registrados
     * @return int Total de estudiantes
     */
    public function getTotalStudents()
    {
        $this->db->query('SELECT COUNT(*) as total FROM estudiantes');
        $result = $this->db->single();
        return $result->total;
    }
}
