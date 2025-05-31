function abrirModal(url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      const contenedor = document.getElementById("modal-container");
      contenedor.innerHTML = html;
      contenedor.style.display = "block";

      configurarCierreModal(contenedor);
    });
}

function configurarCierreModal(modal) {
  // Cierra con la X
  const cerrar = modal.querySelector(".close");
  if (cerrar) {
    cerrar.onclick = () => modal.style.display = "none";
  }

  // Cierra al hacer clic fuera del contenido
  window.onclick = function(event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };
}
