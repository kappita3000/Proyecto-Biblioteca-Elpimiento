// Obtener elementos del DOM
const modal = document.getElementById("myModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.querySelector(".close");

// Abrir el modal al hacer clic en el botón
openModalBtn.addEventListener("click", () => {
    modal.style.display = "block";
});

// Cerrar el modal al hacer clic en el botón de cerrar
closeModalBtn.addEventListener("click", () => {
    modal.style.display = "none";
});

// Cerrar el modal al hacer clic fuera del contenido del modal
window.addEventListener("click", (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
    }
});
