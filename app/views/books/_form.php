<!-- Formulario para crear o editar un libro -->
<form action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" novalidate>

    <!-- Grupo 1: Datos del libro -->
    <div class="grupo-campos">
        <!-- Campo: Título -->
        <div class="campo campo-ancho <?= isset($errors['titulo']) ? 'error-input' : '' ?>">
            <label for="titulo">Título*</label>
            <input type="text" name="titulo" id="titulo"
                   value="<?= htmlspecialchars($book->titulo ?? '') ?>" required>
            <?php if (isset($errors['titulo'])): ?>
                <span class="error"><?= htmlspecialchars($errors['titulo']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Descripción -->
        <div class="campo campo-ancho <?= isset($errors['descripcion']) ? 'error-input' : '' ?>">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($book->descripcion ?? '') ?></textarea>
            <?php if (isset($errors['descripcion'])): ?>
                <span class="error"><?= htmlspecialchars($errors['descripcion']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Portada -->
        <div class="campo <?= isset($errors['portada']) ? 'error-input' : '' ?>">
            <label for="portada">Portada</label>
            <input type="file" name="portada" id="portada" accept="image/*">
            <?php if (isset($errors['portada'])): ?>
                <span class="error"><?= htmlspecialchars($errors['portada']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo 2: Relaciones -->
    <div class="grupo-campos">
        <!-- Campo: Autor -->
        <div class="campo <?= isset($errors['id_autor']) ? 'error-input' : '' ?>">
            <label for="id_autor">Autor*</label>
            <select name="id_autor" id="id_autor" required>
                <option value="">Seleccione un autor</option>
                <?php foreach ($autores as $autor): ?>
                    <option value="<?= $autor->id ?>" <?= (isset($book->id_autor) && $book->id_autor == $autor->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($autor->nombres) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_autor'])): ?>
                <span class="error"><?= htmlspecialchars($errors['id_autor']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Editorial -->
        <div class="campo <?= isset($errors['id_editorial']) ? 'error-input' : '' ?>">
            <label for="id_editorial">Editorial*</label>
            <select name="id_editorial" id="id_editorial" required>
                <option value="">Seleccione una editorial</option>
                <?php foreach ($editoriales as $editorial): ?>
                    <option value="<?= $editorial->id ?>" <?= (isset($book->id_editorial) && $book->id_editorial == $editorial->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($editorial->editorial) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_editorial'])): ?>
                <span class="error"><?= htmlspecialchars($errors['id_editorial']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Materia -->
        <div class="campo <?= isset($errors['id_materia']) ? 'error-input' : '' ?>">
            <label for="id_materia">Materia*</label>
            <select name="id_materia" id="id_materia" required>
                <option value="">Seleccione una materia</option>
                <?php foreach ($materias as $materia): ?>
                    <option value="<?= $materia->id ?>" <?= (isset($book->id_materia) && $book->id_materia == $materia->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($materia->materia) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_materia'])): ?>
                <span class="error"><?= htmlspecialchars($errors['id_materia']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grupo 3: Año, páginas, ISBN y estado -->
    <div class="grupo-campos">
        <!-- Año de edición -->
        <div class="campo <?= isset($errors['anio_edicion']) ? 'error-input' : '' ?>">
            <label for="anio_edicion">Año de edición*</label>
            <input type="number" name="anio_edicion" id="anio_edicion" min="1000" max="2100"
                   value="<?= htmlspecialchars($book->anio_edicion ?? '') ?>" required>
            <?php if (isset($errors['anio_edicion'])): ?>
                <span class="error"><?= htmlspecialchars($errors['anio_edicion']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Número de páginas -->
        <div class="campo <?= isset($errors['num_paginas']) ? 'error-input' : '' ?>">
            <label for="num_paginas">N° de páginas</label>
            <input type="number" name="num_paginas" id="num_paginas" min="1"
                   value="<?= htmlspecialchars($book->num_paginas ?? '') ?>">
            <?php if (isset($errors['num_paginas'])): ?>
                <span class="error"><?= htmlspecialchars($errors['num_paginas']) ?></span>
            <?php endif; ?>
        </div>

        <!-- ISBN -->
        <div class="campo <?= isset($errors['isbn']) ? 'error-input' : '' ?>">
            <label for="isbn">ISBN</label>
            <input type="text" name="isbn" id="isbn"
                   value="<?= htmlspecialchars($book->isbn ?? '') ?>">
            <?php if (isset($errors['isbn'])): ?>
                <span class="error"><?= htmlspecialchars($errors['isbn']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Estado -->
        <div class="campo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" required>
                <option value="1" <?= (isset($book->estado) && $book->estado == 1) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (isset($book->estado) && $book->estado == 0) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <!-- Botones -->
    <div class="contenedor-botones">
        <a href="/books" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar">Guardar</button>
    </div>
</form>
