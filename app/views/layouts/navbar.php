<!-- Encabezado de la página -->
<header>
    <!-- Navegación principal -->
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/logout" class="logout-section">
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

<!-- Alerta Personalizada -->
<div id="customAlert" class="modal-alert oculto">
    <div class="contenido-alerta">
        <p id="mensajeAlerta">Mensaje personalizado</p>
        <button onclick="cerrarAlerta()" class="boton-alerta">Cerrar</button>
    </div>
</div>

<!-- Script -->
<script src="/assets/js/alerta.js"></script>