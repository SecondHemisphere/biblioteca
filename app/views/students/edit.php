<div class="formulario">
    <h2><?= $data['title'] ?></h2>

    <?php
        $student = $data['student'];
        $errors = $errors ?? [];
        $form_action = "/students/update/{$student->id}";
        $submit_text = "Actualizar";
        $careers = $data['careers'];
        include __DIR__ . '/_form.php';
    ?>
</div>
