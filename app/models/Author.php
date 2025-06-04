<?php
class Author
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Obtiene todos los autores
     * @return array Lista de autores
     */
    public function getAll()
    {
        $this->db->query('SELECT * FROM autores ORDER BY id DESC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene un autor por ID
     * @param int $id
     * @return object|false
     */
    public function getById($id)
    {
        $this->db->query('SELECT * FROM autores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Crea un nuevo autor
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        $validation = $this->validateAuthorData($data);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        $this->db->query('INSERT INTO autores (nombres, apellidos, fecha_nacimiento, fecha_fallecimiento, nacionalidad, campo_estudio, biografia, imagen, estado) 
                         VALUES (:nombres, :apellidos, :fecha_nacimiento, :fecha_fallecimiento, :nacionalidad, :campo_estudio, :biografia, :imagen, :estado)');

        $this->db->bind(':nombres', $data['nombres']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':fecha_nacimiento', $data['fecha_nacimiento'] ?? null);
        $this->db->bind(':fecha_fallecimiento', $data['fecha_fallecimiento'] ?? null);
        $this->db->bind(':nacionalidad', $data['nacionalidad'] ?? null);
        $this->db->bind(':campo_estudio', $data['campo_estudio'] ?? null);
        $this->db->bind(':biografia', $data['biografia'] ?? null);
        $this->db->bind(':imagen', $data['imagen'] ?? null);
        $this->db->bind(':estado', $data['estado'] ?? 1);

        $success = $this->db->execute();
        return [
            'success' => $success,
            'id' => $success ? $this->db->lastInsertId() : null
        ];
    }

    /**
     * Actualiza un autor
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        $currentAuthor = $this->getById($id);
        if (!$currentAuthor) {
            return ['success' => false, 'errors' => ['general' => 'Autor no encontrado']];
        }

        $validation = $this->validateAuthorData($data, $id);
        if ($validation !== true) {
            return ['success' => false, 'errors' => $validation];
        }

        // Construimos dinámicamente el SQL SET
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sqlSet = implode(', ', $fields);

        $this->db->query("UPDATE autores SET $sqlSet WHERE id = :id");

        // Enlazamos parámetros
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        $this->db->bind(':id', $id);

        $success = $this->db->execute();
        return ['success' => $success];
    }

    /**
     * Elimina un autor
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->query('DELETE FROM autores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Busca un autor por nombre y apellido exacto
     * @param string $nombres
     * @param string $apellidos
     * @return object|false
     */
    public function findByFullName($nombres, $apellidos)
    {
        $this->db->query('SELECT * FROM autores WHERE nombres = :nombres AND apellidos = :apellidos');
        $this->db->bind(':nombres', $nombres);
        $this->db->bind(':apellidos', $apellidos);
        return $this->db->single();
    }

    /**
     * Valida datos de autor con reglas estrictas
     * @param array $data
     * @param int|null $id
     * @return array|true
     */
    public function validateAuthorData($data, $id = null)
    {
        $errors = [];

        // Validar nombres (solo letras, espacios y apóstrofes)
        if (empty($data['nombres'])) {
            $errors['nombres'] = 'Los nombres son requeridos';
        } elseif (strlen($data['nombres']) > 100) {
            $errors['nombres'] = 'Los nombres no pueden exceder los 100 caracteres';
        } elseif (!$this->validateStrictName($data['nombres'])) {
            $errors['nombres'] = 'Los nombres solo deben contener letras, espacios y apóstrofes';
        }

        // Validar apellidos (solo letras, espacios, apóstrofes y guiones)
        if (empty($data['apellidos'])) {
            $errors['apellidos'] = 'Los apellidos son requeridos';
        } elseif (strlen($data['apellidos']) > 100) {
            $errors['apellidos'] = 'Los apellidos no pueden exceder los 100 caracteres';
        } elseif (!$this->validateStrictLastName($data['apellidos'])) {
            $errors['apellidos'] = 'Los apellidos solo deben contener letras, espacios, apóstrofes o guiones';
        }

        // Validar fechas con formato YYYY-MM-DD
        if (!empty($data['fecha_nacimiento'])) {
            if (!$this->validateDate($data['fecha_nacimiento'])) {
                $errors['fecha_nacimiento'] = 'Formato de fecha inválido (debe ser AAAA-MM-DD)';
            } elseif ($this->isFutureDate($data['fecha_nacimiento'])) {
                $errors['fecha_nacimiento'] = 'La fecha de nacimiento no puede ser futura';
            }
        }

        if (!empty($data['fecha_fallecimiento'])) {
            if (!$this->validateDate($data['fecha_fallecimiento'])) {
                $errors['fecha_fallecimiento'] = 'Formato de fecha inválido (debe ser AAAA-MM-DD)';
            } elseif ($this->isFutureDate($data['fecha_fallecimiento'])) {
                $errors['fecha_fallecimiento'] = 'La fecha de fallecimiento no puede ser futura';
            } elseif (
                !empty($data['fecha_nacimiento']) &&
                strtotime($data['fecha_fallecimiento']) <= strtotime($data['fecha_nacimiento'])
            ) {
                $errors['fecha_fallecimiento'] = 'La fecha de fallecimiento debe ser posterior a la de nacimiento';
            }
        }

        // Validar nacionalidad (solo letras y espacios)
        if (!empty($data['nacionalidad'])) {
            if (strlen($data['nacionalidad']) > 100) {
                $errors['nacionalidad'] = 'La nacionalidad no puede exceder los 100 caracteres';
            } elseif (!$this->validateCountryName($data['nacionalidad'])) {
                $errors['nacionalidad'] = 'La nacionalidad solo debe contener letras y espacios';
            }
        }

        // Validar campo de estudio
        if (!empty($data['campo_estudio'])) {
            if (strlen($data['campo_estudio']) > 100) {
                $errors['campo_estudio'] = 'El campo de estudio no puede exceder los 100 caracteres';
            } elseif (!$this->validateFieldOfStudy($data['campo_estudio'])) {
                $errors['campo_estudio'] = 'El campo de estudio contiene caracteres no permitidos';
            }
        }

        // Validar biografía (longitud máxima 2000 caracteres)
        if (!empty($data['biografia'])) {
            if (strlen($data['biografia']) > 2000) {
                $errors['biografia'] = 'La biografía no puede exceder los 2000 caracteres';
            }
        }

        // Validar que no exista otro autor con el mismo nombre y apellido
        if ($id === null) {
            if ($this->findByFullName($data['nombres'], $data['apellidos'])) {
                $errors['general'] = 'Ya existe un autor con estos nombres y apellidos';
            }
        } else {
            $existingAuthor = $this->findByFullName($data['nombres'], $data['apellidos']);
            if ($existingAuthor && $existingAuthor->id != $id) {
                $errors['general'] = 'Ya existe otro autor con estos nombres y apellidos';
            }
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Valida los datos para actualizar un autor
     * @param array $data Datos a validar
     * @param object $currentAuthor Autor actual (de la base de datos)
     * @return array|true Lista de errores o true si es válido
     */
    public function validateUpdateData($data, $currentAuthor)
    {
        $errors = [];

        // Validar nombres (solo letras, espacios y apóstrofes)
        if (empty($data['nombres'])) {
            $errors['nombres'] = 'Los nombres son requeridos';
        } elseif (strlen($data['nombres']) > 100) {
            $errors['nombres'] = 'Los nombres no pueden exceder los 100 caracteres';
        } elseif (!$this->validateStrictName($data['nombres'])) {
            $errors['nombres'] = 'Los nombres solo deben contener letras, espacios y apóstrofes';
        }

        // Validar apellidos (solo letras, espacios, apóstrofes y guiones)
        if (empty($data['apellidos'])) {
            $errors['apellidos'] = 'Los apellidos son requeridos';
        } elseif (strlen($data['apellidos']) > 100) {
            $errors['apellidos'] = 'Los apellidos no pueden exceder los 100 caracteres';
        } elseif (!$this->validateStrictLastName($data['apellidos'])) {
            $errors['apellidos'] = 'Los apellidos solo deben contener letras, espacios, apóstrofes o guiones';
        }

        // Validar que no exista otro autor con el mismo nombre y apellido
        if (($data['nombres'] !== $currentAuthor->nombres || $data['apellidos'] !== $currentAuthor->apellidos) &&
            $this->findByFullName($data['nombres'], $data['apellidos'])
        ) {
            $errors['general'] = 'Ya existe otro autor con estos nombres y apellidos';
        }

        // Validar fechas con formato YYYY-MM-DD
        if (!empty($data['fecha_nacimiento'])) {
            if (!$this->validateDate($data['fecha_nacimiento'])) {
                $errors['fecha_nacimiento'] = 'Formato de fecha inválido (debe ser AAAA-MM-DD)';
            } elseif ($this->isFutureDate($data['fecha_nacimiento'])) {
                $errors['fecha_nacimiento'] = 'La fecha de nacimiento no puede ser futura';
            }
        }

        if (!empty($data['fecha_fallecimiento'])) {
            if (!$this->validateDate($data['fecha_fallecimiento'])) {
                $errors['fecha_fallecimiento'] = 'Formato de fecha inválido (debe ser AAAA-MM-DD)';
            } elseif ($this->isFutureDate($data['fecha_fallecimiento'])) {
                $errors['fecha_fallecimiento'] = 'La fecha de fallecimiento no puede ser futura';
            } elseif (
                !empty($data['fecha_nacimiento']) &&
                strtotime($data['fecha_fallecimiento']) <= strtotime($data['fecha_nacimiento'])
            ) {
                $errors['fecha_fallecimiento'] = 'La fecha de fallecimiento debe ser posterior a la de nacimiento';
            }
        }

        // Validar nacionalidad (solo letras y espacios)
        if (!empty($data['nacionalidad'])) {
            if (strlen($data['nacionalidad']) > 100) {
                $errors['nacionalidad'] = 'La nacionalidad no puede exceder los 100 caracteres';
            } elseif (!$this->validateCountryName($data['nacionalidad'])) {
                $errors['nacionalidad'] = 'La nacionalidad solo debe contener letras y espacios';
            }
        }

        // Validar campo de estudio
        if (!empty($data['campo_estudio'])) {
            if (strlen($data['campo_estudio']) > 100) {
                $errors['campo_estudio'] = 'El campo de estudio no puede exceder los 100 caracteres';
            } elseif (!$this->validateFieldOfStudy($data['campo_estudio'])) {
                $errors['campo_estudio'] = 'El campo de estudio contiene caracteres no permitidos';
            }
        }

        // Validar biografía (longitud máxima 2000 caracteres)
        if (!empty($data['biografia'])) {
            if (strlen($data['biografia']) > 2000) {
                $errors['biografia'] = 'La biografía no puede exceder los 2000 caracteres';
            }
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Valida nombres estrictamente (letras, espacios y apóstrofes)
     */
    private function validateStrictName($name)
    {
        return preg_match('/^[\p{L}\s\']+$/u', $name);
    }

    /**
     * Valida apellidos (letras, espacios, apóstrofes y guiones)
     */
    private function validateStrictLastName($lastName)
    {
        return preg_match('/^[\p{L}\s\'-]+$/u', $lastName);
    }

    /**
     * Valida nombres de países (letras y espacios)
     */
    private function validateCountryName($country)
    {
        return preg_match('/^[\p{L}\s]+$/u', $country);
    }

    /**
     * Valida campos de estudio (letras, números, espacios y algunos caracteres especiales)
     */
    private function validateFieldOfStudy($field)
    {
        return preg_match('/^[\p{L}\p{N}\s\-\.,;:\(\)]+$/u', $field);
    }

    /**
     * Valida que una fecha sea válida
     */
    private function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Verifica si una fecha es futura
     */
    private function isFutureDate($date)
    {
        return strtotime($date) > time();
    }

    /**
     * Obtiene el total de autores
     * @return int
     */
    public function getTotal()
    {
        $this->db->query('SELECT COUNT(*) as total FROM autores');
        $result = $this->db->single();
        return $result->total;
    }
}
