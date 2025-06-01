<!DOCTYPE html>
<html lang="es">
    <!-- Head -->
    <?php require_once __DIR__ . '/head.php'; ?>
<body>
    <!-- Navegación -->
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <!-- Layout Principal -->
    <div class="layout-principal">
        <!-- Barra Lateral -->
        <?php if (empty($hideSidebar)): ?>
        <aside class="sidebar">
            <?php require_once __DIR__ . '/sidebar.php'; ?>
        </aside>
        <?php endif; ?>
        <!-- Contenido Principal -->
        <main class="contenedor-principal">
            <!-- Vista -->
            <?php
            if (isset($view)) {
                if (isset($data)) extract($data);
                require_once $view;
            } else {
                echo "<p>Error: vista no especificada.</p>";
            }
            ?>
        </main>
    </div>
    <!-- Scripts -->
    <script src="/assets/js/alerta.js"></script>
</body>
</html>
