   <div class="contenedor-autenticacion">
        <h2 class="titulo-autenticacion">Iniciar Sesión</h2>

        <!-- Mensaje de error (si existe) -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mensaje-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Mensaje de éxito (si existe) -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mensaje-exito">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form id="loginForm" action="/auth/login" method="POST" class="formulario">
            <!-- Campo: Correo electrónico -->
            <div class="grupo-formulario">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email">
            </div>

            <!-- Campo: Contraseña -->
            <div class="grupo-formulario">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password">
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="boton-principal">Ingresar</button>
        </form>

        <!-- Enlace a la página de registro -->
        <p class="texto-secundario">
            ¿No tienes una cuenta?
            <a href="/register" class="enlace">Regístrate aquí</a>
        </p>
    </div>

<!-- Script de autenticación -->
<script src="/assets/js/auth.js"></script>
