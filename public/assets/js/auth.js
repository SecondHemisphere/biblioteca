document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                mostrarAlerta('Por favor complete todos los campos');
            }
        });
    }
    
    // Validación del formulario de registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            
            if (!name || !email || !password || !confirmPassword) {
                e.preventDefault();
                mostrarAlerta('Por favor complete todos los campos');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                mostrarAlerta('Las contraseñas no coinciden');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                mostrarAlerta('La contraseña debe tener al menos 8 caracteres');
            }
        });
    }
});