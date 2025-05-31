document.querySelector('#form-estudiante').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const mensaje = document.querySelector('#mensaje');

    try {
        const response = await fetch('/students/store', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        mensaje.innerText = result.message;
        mensaje.className = result.success ? 'mensaje-exito' : 'mensaje-error';

        if (result.success) {
            form.reset();
        }

    } catch (error) {
        mensaje.innerText = 'Error en la conexión o en el servidor.';
        mensaje.className = 'mensaje-error';
    }
});