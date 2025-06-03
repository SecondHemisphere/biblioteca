<!-- Contenedor del formulario de edición -->
<div class="formulario">
    <!-- Título del formulario -->
    <h2><?= $data['title'] ?></h2>

    <?php
    // Datos del estudiante a editar
    $student = $data['student'];

    // Errores de validación (si existen)
    $errors = $errors ?? [];

    // Ruta a la que se enviará el formulario
    $form_action = "/students/update/{$student->id}";

    // Texto del botón de envío
    $submit_text = "Actualizar";

    // Inclusión del formulario reutilizable
    include __DIR__ . '/_form.php';
    ?>
</div>