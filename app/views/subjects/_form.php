<!-- Formulario para crear o editar una materia -->
<form action="<?= $form_action ?>" method="POST" novalidate>

    <!-- Grupo 1: Datos de identificación -->
    <div class="grupo-campos">
        <!-- Campo: Nombre de la materia -->
        <div class="campo <?= isset($errors['materia']) ? 'error-input' : '' ?>">
            <label for="materia">Nombre</label>
            <input type="text" name="materia" id="materia"
                value="<?= htmlspecialchars($subject->materia ?? '') ?>" required>
            <?php if (isset($errors['materia'])): ?>
                <span class="error"><?= htmlspecialchars($errors['materia']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Estado de la materia (activa/inactiva) -->
        <div class="campo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" required>
                <option value="1" <?= (isset($subject->estado) && $subject->estado == 1) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (isset($subject->estado) && $subject->estado == 0) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <!-- Grupo de botones -->
    <div class="contenedor-botones">
        <a href="/subjects" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar">Guardar</button>
    </div>
</form>