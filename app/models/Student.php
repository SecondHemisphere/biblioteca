<?php
class Student {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Obtiene todos los estudiantes (sin paginación)
     * @return array Lista de estudiantes
     */
    public function getAllStudents() {
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
    public function getPaginatedStudents($page = 1, $perPage = 10, $search = null) {
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
    private function getFilteredCount($search = null) {
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
    public function getStudentById($id) {
        $this->db->query('SELECT * FROM estudiantes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Registra un nuevo estudiante con validación
     * @param array $data Datos del estudiante
     * @return array Resultado de la operación
     */
    public function register($data) {
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
    public function update($id, $data) {
        $validation = $this->validateUpdateData($id, $data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }
        
        $this->db->query('UPDATE estudiantes SET
                         codigo = :codigo,
                         dni = :dni,
                         nombre = :nombre,
                         carrera = :carrera,
                         direccion = :direccion,
                         telefono = :telefono,
                         estado = :estado
                         WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':codigo', $data['codigo']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':carrera', $data['carrera']);
        $this->db->bind(':direccion', $data['direccion']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':estado', $data['estado']);
        
        $success = $this->db->execute();
        return ['success' => $success];
    }
    
    /**
     * Elimina un estudiante
     * @param int $id ID del estudiante
     * @return bool True si se eliminó correctamente
     */
    public function delete($id) {
        $this->db->query('DELETE FROM estudiantes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Busca estudiante por código
     * @param string $codigo Código del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function findStudentByCode($codigo) {
        $this->db->query('SELECT * FROM estudiantes WHERE codigo = :codigo');
        $this->db->bind(':codigo', $codigo);
        return $this->db->single();
    }
    
    /**
     * Busca estudiante por DNI
     * @param string $dni DNI del estudiante
     * @return object|false Objeto estudiante o false si no existe
     */
    public function findStudentByDni($dni) {
        $this->db->query('SELECT * FROM estudiantes WHERE dni = :dni');
        $this->db->bind(':dni', $dni);
        return $this->db->single();
    }
    
    /**
     * Valida los datos de un nuevo estudiante
     * @param array $data Datos a validar
     * @return array|true Lista de errores o true si es válido
     */
    public function validateStudentData($data) {
        $errors = [];
        
        if (empty($data['codigo'])) {
            $errors[] = 'El código es requerido';
        } elseif ($this->findStudentByCode($data['codigo'])) {
            $errors[] = 'El código ya está registrado';
        }
        
        if (empty($data['dni'])) {
            $errors[] = 'El DNI es requerido';
        } elseif (!$this->validateDni($data['dni'])) {
            $errors[] = 'El DNI debe tener 10 dígitos numéricos';
        } elseif ($this->findStudentByDni($data['dni'])) {
            $errors[] = 'El DNI ya está registrado';
        }
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) > 100) {
            $errors[] = 'El nombre no puede exceder los 100 caracteres';
        }
        
        if (empty($data['carrera'])) {
            $errors[] = 'La carrera es requerida';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Valida los datos para actualización
     * @param int $id ID del estudiante
     * @param array $data Datos a validar
     * @return array|true Lista de errores o true si es válido
     */
    public function validateUpdateData($id, $data) {
        $errors = [];
        $currentStudent = $this->getStudentById($id);
        
        if (!$currentStudent) {
            return ['Estudiante no encontrado'];
        }
        
        if (empty($data['codigo'])) {
            $errors[] = 'El código es requerido';
        } elseif ($data['codigo'] !== $currentStudent->codigo) {
            if ($this->findStudentByCode($data['codigo'])) {
                $errors[] = 'El código ya está registrado por otro estudiante';
            }
        }
        
        if (empty($data['dni'])) {
            $errors[] = 'El DNI es requerido';
        } elseif (!$this->validateDni($data['dni'])) {
            $errors[] = 'El DNI debe tener 10 dígitos numéricos';
        } elseif ($data['dni'] !== $currentStudent->dni) {
            if ($this->findStudentByDni($data['dni'])) {
                $errors[] = 'El DNI ya está registrado por otro estudiante';
            }
        }
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($data['carrera'])) {
            $errors[] = 'La carrera es requerida';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    /**
     * Valida que el DNI tenga el formato correcto
     * @param string $dni DNI a validar
     * @return bool True si es válido
     */
    public function validateDni($dni) {
        return preg_match('/^[0-9]{10}$/', $dni);
    }
    
    /**
     * Obtiene estadísticas de estudiantes por carrera
     * @return array Estadísticas organizadas por carrera
     */
    public function getStatistics() {
        $this->db->query('
            SELECT 
                carrera,
                COUNT(*) as total,
                SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as inactivos
            FROM estudiantes
            GROUP BY carrera
        ');
        return $this->db->resultSet();
    }
    
    /**
     * Obtiene el total de estudiantes registrados
     * @return int Total de estudiantes
     */
    public function getTotalStudents() {
        $this->db->query('SELECT COUNT(*) as total FROM estudiantes');
        $result = $this->db->single();
        return $result->total;
    }
}