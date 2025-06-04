<!-- Formulario para crear o editar un autor -->
<form action="<?= $form_action ?>" method="POST" novalidate enctype="multipart/form-data">

    <!-- Grupo 1: Datos básicos del autor -->
    <div class="grupo-campos">
        <!-- Campo: Nombres -->
        <div class="campo <?= isset($errors['nombres']) ? 'error-input' : '' ?>">
            <label for="nombres">Nombres*</label>
            <input type="text" name="nombres" id="nombres"
                   value="<?= htmlspecialchars($author->nombres ?? '') ?>" required>
            <?php if (isset($errors['nombres'])): ?>
                <span class="error"><?= htmlspecialchars($errors['nombres']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Apellidos -->
        <div class="campo <?= isset($errors['apellidos']) ? 'error-input' : '' ?>">
            <label for="apellidos">Apellidos*</label>
            <input type="text" name="apellidos" id="apellidos"
                   value="<?= htmlspecialchars($author->apellidos ?? '') ?>" required>
            <?php if (isset($errors['apellidos'])): ?>
                <span class="error"><?= htmlspecialchars($errors['apellidos']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Estado -->
        <div class="campo">
            <label for="estado">Estado*</label>
            <select name="estado" id="estado" required>
                <option value="1" <?= (isset($author->estado) && $author->estado == 1) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (isset($author->estado) && $author->estado == 0) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <!-- Grupo 2: Fechas importantes -->
    <div class="grupo-campos">
        <!-- Campo: Fecha de nacimiento -->
        <div class="campo <?= isset($errors['fecha_nacimiento']) ? 'error-input' : '' ?>">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                   value="<?= htmlspecialchars($author->fecha_nacimiento ?? '') ?>">
            <?php if (isset($errors['fecha_nacimiento'])): ?>
                <span class="error"><?= htmlspecialchars($errors['fecha_nacimiento']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Fecha de fallecimiento -->
        <div class="campo <?= isset($errors['fecha_fallecimiento']) ? 'error-input' : '' ?>">
            <label for="fecha_fallecimiento">Fecha de Fallecimiento</label>
            <input type="date" name="fecha_fallecimiento" id="fecha_fallecimiento"
                   value="<?= htmlspecialchars($author->fecha_fallecimiento ?? '') ?>">
            <?php if (isset($errors['fecha_fallecimiento'])): ?>
                <span class="error"><?= htmlspecialchars($errors['fecha_fallecimiento']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo 3: Información adicional -->
    <div class="grupo-campos">
        <!-- Campo: Nacionalidad -->
        <div class="campo <?= isset($errors['nacionalidad']) ? 'error-input' : '' ?>">
            <label for="nacionalidad">Nacionalidad</label>
            <input type="text" name="nacionalidad" id="nacionalidad"
                   value="<?= htmlspecialchars($author->nacionalidad ?? '') ?>">
            <?php if (isset($errors['nacionalidad'])): ?>
                <span class="error"><?= htmlspecialchars($errors['nacionalidad']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Campo de estudio -->
        <div class="campo <?= isset($errors['campo_estudio']) ? 'error-input' : '' ?>">
            <label for="campo_estudio">Campo de Estudio</label>
            <input type="text" name="campo_estudio" id="campo_estudio"
                   value="<?= htmlspecialchars($author->campo_estudio ?? '') ?>">
            <?php if (isset($errors['campo_estudio'])): ?>
                <span class="error"><?= htmlspecialchars($errors['campo_estudio']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo 4: Biografía e imagen -->
    <div class="grupo-campos">
        <!-- Campo: Biografía -->
        <div class="campo campo-ancho <?= isset($errors['biografia']) ? 'error-input' : '' ?>">
            <label for="biografia">Biografía</label>
            <textarea name="biografia" id="biografia" rows="4"><?= htmlspecialchars($author->biografia ?? '') ?></textarea>
            <?php if (isset($errors['biografia'])): ?>
                <span class="error"><?= htmlspecialchars($errors['biografia']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Imagen -->
        <div class="campo <?= isset($errors['imagen']) ? 'error-input' : '' ?>">
            <label for="imagen">Imagen del Autor</label>
            <input type="file" name="imagen" id="imagen" accept="image/*">
            
            <?php if (isset($author->imagen) && !empty($author->imagen)): ?>
                <div class="imagen-actual">
                    <p>Imagen actual:</p>
                    <img src="<?= htmlspecialchars($author->imagen) ?>" width="100">
                    <label>
                        <input type="checkbox" name="eliminar_imagen" value="1"> Eliminar imagen
                    </label>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['imagen'])): ?>
                <span class="error"><?= htmlspecialchars($errors['imagen']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo de botones -->
    <div class="contenedor-botones">
        <a href="/authors" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar"><?= isset($author->id) ? 'Actualizar' : 'Guardar' ?></button>
    </div>
</form>