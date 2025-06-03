<!-- Contenedor del formulario de registro -->
<div class="formulario">
    <!-- Título del formulario -->
    <h2><?= $data['title'] ?></h2>

    <?php
    // Estudiante vacío (formulario de creación)
    $student = [];

    // Errores de validación (si existen)
    $errors = $errors ?? [];

    // Ruta a la que se enviará el formulario
    $form_action = "/students/store";

    // Texto del botón de envío
    $submit_text = "Registrar";

    // Lista de carreras disponibles
    $careers = $data['careers'];

    // Inclusión del formulario reutilizable
    include __DIR__ . '/_form.php';
    ?>
</div>