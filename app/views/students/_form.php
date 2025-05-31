<?php
// Función helper para mostrar errores de campo (puede ir en un archivo aparte de helpers)
function displayFieldError($field, $errors) {
    if (isset($errors[$field])) {
        return '<span class="error">' . htmlspecialchars($errors[$field]) . '</span>';
    }
    return '';
}

// Función para procesar errores de sesión (puede convertir errores generales en específicos)
function processSessionErrors(&$errors) {
    if (!empty($_SESSION['error_message'])) {
        // Intenta parsear el mensaje para asociarlo a un campo (formato "campo: mensaje")
        $parts = explode(': ', $_SESSION['error_message'], 2);
        
        if (count($parts) === 2) {
            $errors[$parts[0]] = $parts[1]; // Asocia el error al campo
        } else {
            // Si no sigue el formato, muestra como error general
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        }
        
        unset($_SESSION['error_message']);
    }
}

// Procesar errores de sesión antes de mostrar el formulario
processSessionErrors($errors);
?>

<form action="<?= htmlspecialchars($form_action) ?>" method="POST">
    <!-- Grupo 1: Datos de identificación -->
    <div class="grupo-campos">
        <div class="campo <?= isset($errors['codigo']) ? 'error-input' : '' ?>">
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo"
                   value="<?= htmlspecialchars($student->codigo ?? '') ?>" required>
            <?= displayFieldError('codigo', $errors) ?>
        </div>
        
        <div class="campo <?= isset($errors['dni']) ? 'error-input' : '' ?>">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni"
                   value="<?= htmlspecialchars($student->dni ?? '') ?>" required>
            <?= displayFieldError('dni', $errors) ?>
        </div>
        
        <div class="campo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" required>
                <option value="1" <?= (isset($student->estado) && $student->estado == 1) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (isset($student->estado) && $student->estado == 0) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <!-- Grupo 2: Datos personales -->
    <div class="grupo-campos">
        <div class="campo campo-ancho <?= isset($errors['nombre']) ? 'error-input' : '' ?>">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                   value="<?= htmlspecialchars($student->nombre ?? '') ?>" required>
            <?= displayFieldError('nombre', $errors) ?>
        </div>
        
        <div class="campo campo-ancho <?= isset($errors['carrera']) ? 'error-input' : '' ?>">
            <label for="carrera">Carrera</label>
            <select name="carrera" id="carrera" required>
                <option value="">Seleccione una carrera</option>
                <?php foreach ($careers as $career): ?>
                    <option value="<?= htmlspecialchars($career) ?>"
                        <?= (isset($student->carrera) && $student->carrera === $career) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($career) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?= displayFieldError('carrera', $errors) ?>
        </div>
    </div>

    <!-- Grupo 3: Datos de contacto -->
    <div class="grupo-campos">
        <div class="campo campo-ancho <?= isset($errors['direccion']) ? 'error-input' : '' ?>">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion"
                   value="<?= htmlspecialchars($student->direccion ?? '') ?>">
            <?= displayFieldError('direccion', $errors) ?>
        </div>
        
        <div class="campo <?= isset($errors['telefono']) ? 'error-input' : '' ?>">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono"
                   value="<?= htmlspecialchars($student->telefono ?? '') ?>">
            <?= displayFieldError('telefono', $errors) ?>
        </div>
    </div>

    <!-- Botones -->
    <div class="contenedor-botones">
        <a href="/students" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar">Registrar Estudiante</button>
    </div>
</form>