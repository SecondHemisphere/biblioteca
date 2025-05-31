<div class="formulario">
    <h2><?= $data['title'] ?></h2>

    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php
        $student = $data['student'];
        $form_action = "/students/update/{$student->id}";
        $submit_text = "Actualizar";
        $careers = $data['careers'];
        include __DIR__ . '/_form.php';
    ?>
</div>
