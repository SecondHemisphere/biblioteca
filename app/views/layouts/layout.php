<!DOCTYPE html>
<html lang="es">
<?php require_once __DIR__ . '/head.php'; ?>
<body>
    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="layout-principal">
        <aside class="sidebar">
            <?php require_once __DIR__ . '/sidebar.php'; ?>
        </aside>

        <main class="contenedor-principal">
            <?php
            if (isset($view)) {
                require_once $view;
            } else {
                echo "<p>Error: vista no especificada.</p>";
            }
            ?>
        </main>
    </div>
</body>
</html>
