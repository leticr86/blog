<?php
// Verificar si hay un mensaje en la sesión
if (isset($_SESSION['mensaje'])) {
  $mensaje = $_SESSION['mensaje'];
  // Mostrar el mensaje con el tipo correspondiente
  echo "<script>
            setTimeout(function() {
                document.getElementById('mensaje').style.display = 'none';
            }, 5000);
          </script>";

  echo "<div id='mensaje' class='" . $mensaje['tipo'] . "'>
            <p>" . $mensaje['contenido'] . "</p>
          </div>";

  // Eliminar el mensaje de la sesión para que no se muestre en futuras visitas
  unset($_SESSION['mensaje']);
}