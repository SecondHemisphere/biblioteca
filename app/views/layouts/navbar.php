<!-- Encabezado-->
<header>
    <!-- Navegación principal -->
    <nav class="navegacion-menu">
        <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/logout" class="logout-item">
                    Cerrar Sesión
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <a href="/dashboard">
                <i class="fas fa-question"></i>
                Acerca De
            </a>
            <a href="/dashboard">
                <i class="fas fa-house"></i>
                Inicio
            </a>
            <a href="/dashboard">
                <i class="fas fa-user"></i>
                Mi Cuenta
            </a>
        <?php else: ?>
            <a href="/login">Iniciar Sesión</a>
            <a href="/register">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>