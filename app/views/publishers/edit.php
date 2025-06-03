<!-- Contenedor del formulario de edición -->
<div class="formulario">
    <!-- Título del formulario -->
    <h2><?= $data['title'] ?></h2>

    <?php
    // Datos de la editorial a editar
    $publisher = $data['publisher'];

    // Errores de validación (si existen)
    $errors = $errors ?? [];

    // Ruta a la que se enviará el formulario
    $form_action = "/publishers/update/{$publisher->id}";

    // Texto del botón de envío
    $submit_text = "Actualizar";

    // Inclusión del formulario reutilizable
    include __DIR__ . '/_form.php';
    ?>
</div>