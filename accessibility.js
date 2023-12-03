//Aumentar y disminuir la fuente al pulsar los botones hasta un máximo y con un mínimo.
var tamanioMaximo = 30; // tamaño máximo de fuente en px
var tamanioMinimo = 8; // tamaño mínimo de fuente en px
var tamanioActual = 16; // tamaño inicial de fuente en px

//Aumentar fuente
function aumentarFuente() {
  if (tamanioActual < tamanioMaximo) {
    tamanioActual += 2;
    document.body.style.fontSize = tamanioActual + "px";
  }
}

//Disminuir fuente
function disminuirFuente() {
  if (tamanioActual > tamanioMinimo) {
    tamanioActual -= 2;
    document.body.style.fontSize = tamanioActual + "px";
  }
}

//Cambiar el color de todo el body a grises
function cambiarGrises() {
  document.body.classList.toggle("grises");
}

//Cambiar el tipo de fuente de todo el body
function cambiarFuente() {
  document.body.classList.toggle("fuente");
}

//Mostrar botones de accesibilidad
function mostrarBotones() {
  document
    .getElementsByClassName("contenedorBotones")[0]
    .classList.toggle("active");
}
