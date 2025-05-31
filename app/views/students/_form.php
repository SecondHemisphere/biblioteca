<form action="<?= $form_action ?>" method="POST" novalidate>
    <!-- Grupo 1: Datos de identificación -->
    <div class="grupo-campos">
        <div class="campo <?= isset($errors['codigo']) ? 'error-input' : '' ?>">
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo"
                   value="<?= htmlspecialchars($student->codigo ?? '') ?>" required>
            <?php if (isset($errors['codigo'])): ?>
                <span class="error"><?= htmlspecialchars($errors['codigo']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="campo <?= isset($errors['dni']) ? 'error-input' : '' ?>">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni"
                   value="<?= htmlspecialchars($student->dni ?? '') ?>" required>
            <?php if (isset($errors['dni'])): ?>
                <span class="error"><?= htmlspecialchars($errors['dni']) ?></span>
            <?php endif; ?>
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
            <?php if (isset($errors['nombre'])): ?>
                <span class="error"><?= htmlspecialchars($errors['nombre']) ?></span>
            <?php endif; ?>
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
            <?php if (isset($errors['carrera'])): ?>
                <span class="error"><?= htmlspecialchars($errors['carrera']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo 3: Datos de contacto -->
    <div class="grupo-campos">
        <div class="campo campo-ancho <?= isset($errors['direccion']) ? 'error-input' : '' ?>">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion"
                   value="<?= htmlspecialchars($student->direccion ?? '') ?>">
            <?php if (isset($errors['direccion'])): ?>
                <span class="error"><?= htmlspecialchars($errors['direccion']) ?></span>
            <?php endif; ?>
        </div>
        
        <div class="campo <?= isset($errors['telefono']) ? 'error-input' : '' ?>">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono"
                   value="<?= htmlspecialchars($student->telefono ?? '') ?>">
            <?php if (isset($errors['telefono'])): ?>
                <span class="error"><?= htmlspecialchars($errors['telefono']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Botones -->
    <div class="contenedor-botones">
        <a href="/students" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar">Registrar Estudiante</button>
    </div>
</form>