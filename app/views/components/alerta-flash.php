<?php if (!empty($mensaje_exito) || !empty($mensaje_error)): ?>
    <div id="customAlert" class="custom-alert" style="display: flex;">
        <div class="alert-content <?= $mensaje_error ? 'error' : 'success' ?>">
            <div class="alert-icon"><?= $mensaje_error ? '✕' : '✓' ?></div>
            <h3><?= $mensaje_error ? 'Error' : '¡Correcto!' ?></h3>
            <p><?= htmlspecialchars($mensaje_error ?: $mensaje_exito) ?></p>
            <button id="alertConfirmBtn" class="alert-button">Aceptar</button>
        </div>
    </div>
<?php endif; ?>
