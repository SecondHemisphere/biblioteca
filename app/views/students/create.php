<div class="formulario">
    <h2><?= $data['title'] ?></h2>

    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php
        $form_action = "/students/store";
        $submit_text = "Registrar";
        $student = [];  // vacío
        $careers = $data['careers'];
        include __DIR__ . '/_form.php';
    ?>
</div>
