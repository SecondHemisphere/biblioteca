<!-- Contenedor principal del formulario de autenticación -->
<div class="contenedor-principal">
    <div class="contenedor-autenticacion">
        <h2 class="titulo-autenticacion">Crear Cuenta</h2>

        <!-- Mostrar mensaje de error desde la sesión, si existe -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mensaje-error">
                <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form id="registerForm" action="/auth/register" method="POST" class="formulario">
            
            <!-- Campo: Nombre completo -->
            <div class="grupo-formulario">
                <label for="name" class="etiqueta">Nombre completo</label>
                <input type="text" id="name" name="name" placeholder="Ej: Juan Pérez">
            </div>

            <!-- Campo: Correo electrónico -->
            <div class="grupo-formulario">
                <label for="email" class="etiqueta">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com">
            </div>

            <!-- Campo: Contraseña -->
            <div class="grupo-formulario">
                <label for="password" class="etiqueta">
                    Contraseña
                </label>
                <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres">
            </div>

            <!-- Campo: Confirmar contraseña -->
            <div class="grupo-formulario">
                <label for="confirm_password" class="etiqueta">Confirmar contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite tu contraseña">
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="boton-principal boton-azul">
                <span class="texto-boton">Registrarse</span>
            </button>
        </form>

        <!-- Enlace para usuarios que ya tienen cuenta -->
        <div class="enlace-contenedor">
            <p class="texto-secundario">
                ¿Ya tienes una cuenta?
                <a href="/login" class="enlace">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</div>

<!-- Script -->
<script src="/assets/js/auth.js"></script>
