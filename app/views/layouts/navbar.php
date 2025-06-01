<!-- Encabezado-->
<header>
    <!-- Navegación principal -->
    <nav class="navegacion-menu">
        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-book-open"></i>
            <span>Biblioteca</span>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard">
                <i class="fas fa-user"></i>
                Mi Cuenta
            </a>
            <a href="/dashboard">
                <i class="fas fa-house"></i>
                Inicio
            </a>
            <a href="/dashboard">
                <i class="fas fa-question"></i>
                Acerca De
            </a>
            <a href="/logout" class="logout-item">
                Cerrar Sesión
                <i class="fas fa-sign-out-alt"></i>
            </a>
        <?php else: ?>
            <a href="/login">Iniciar Sesión</a>
            <a href="/register">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>