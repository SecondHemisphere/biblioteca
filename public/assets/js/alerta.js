function mostrarAlerta(mensaje) {
    const alerta = document.getElementById("customAlert");
    const mensajeAlerta = document.getElementById("mensajeAlerta");
    mensajeAlerta.textContent = mensaje;
    alerta.classList.remove("oculto");
}

function cerrarAlerta() {
    document.getElementById("customAlert").classList.add("oculto");
}
