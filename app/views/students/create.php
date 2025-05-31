<div class="formulario">
    <h2><?= $data['title'] ?></h2>
    
    <?php
        $student = [];
        $errors = $errors ?? [];
        $form_action = "/students/store";
        $submit_text = "Registrar";
        $careers = $data['careers'];
        include __DIR__ . '/_form.php';
    ?>
</div>

