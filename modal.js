// script.js
var modal = document.getElementById("confirmationModal");
var actionToPerform = null;

// Mostrar el modal con la acción configurada
function showModal(action) {
  actionToPerform = action;
  modal.style.display = "block";
}

// Ocultar el modal
function hideModal() {
  modal.style.display = "none";
}

// Lógica para confirmar la acción
function confirmAction() {
  if (actionToPerform) {
    // Redireccionar a la acción configurada
    window.location.href = actionToPerform;
  }
  hideModal();
}

// Lógica para cancelar la acción
function cancelAction() {
  hideModal();
}

// Cerrar el modal si se hace clic fuera de él
window.addEventListener("click", function (event) {
  if (event.target == modal) {
    hideModal();
  }
});
