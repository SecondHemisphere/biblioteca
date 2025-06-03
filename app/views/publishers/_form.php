<!-- Formulario para crear o editar una editorial -->
<form action="<?= $form_action ?>" method="POST" novalidate>

    <!-- Grupo 1: Datos de identificación -->
    <div class="grupo-campos">
        <!-- Campo: Nombre de la editorial -->
        <div class="campo <?= isset($errors['editorial']) ? 'error-input' : '' ?>">
            <label for="editorial">Nombre</label>
            <input type="text" name="editorial" id="editorial"
                value="<?= htmlspecialchars($publisher->editorial ?? '') ?>" required>
            <?php if (isset($errors['editorial'])): ?>
                <span class="error"><?= htmlspecialchars($errors['editorial']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Estado de la editorial (activa/inactiva) -->
        <div class="campo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" required>
                <option value="1" <?= (isset($publisher->estado) && $publisher->estado == 1) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (isset($publisher->estado) && $publisher->estado == 0) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <!-- Grupo de botones -->
    <div class="contenedor-botones">
        <a href="/publishers" class="boton boton-cancelar">Cancelar</a>
        <button type="submit" class="boton boton-registrar">Guardar</button>
    </div>
</form>