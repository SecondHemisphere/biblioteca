<form action="<?= $form_action ?>" method="POST">
    <div class="form-group">
        <label for="codigo">Código</label>
        <input type="text" name="codigo" id="codigo" class="form-control"
               value="<?= htmlspecialchars($student['codigo'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni" class="form-control"
               value="<?= htmlspecialchars($student['dni'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control"
               value="<?= htmlspecialchars($student['nombre'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="carrera">Carrera</label>
        <select name="carrera" id="carrera" class="form-control" required>
            <option value="">Seleccione una carrera</option>
            <?php foreach ($careers as $career): ?>
                <option value="<?= htmlspecialchars($career) ?>"
                    <?= (isset($student['carrera']) && $student['carrera'] === $career) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($career) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control"
               value="<?= htmlspecialchars($student['direccion'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control"
               value="<?= htmlspecialchars($student['telefono'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="estado">Estado</label>
        <select name="estado" id="estado" class="form-control" required>
            <option value="activo" <?= (isset($student['estado']) && $student['estado'] === 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= (isset($student['estado']) && $student['estado'] === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?= $submit_text ?></button>
    <a href="/students" class="btn btn-secondary">Cancelar</a>
</form>
