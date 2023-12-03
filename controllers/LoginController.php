<?php
// controllers/LoginController.php

include_once("./Models/UserModel.php"); // Incluir el modelo de usuario

class LoginController {

    //Muestra la página de inicio de sesión y procesa el formulario de inicio de sesión.
    public function index() {
        // Verifica si se ha enviado el formulario de inicio de sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene las credenciales del formulario
            $username = $_POST['usuario'];
            $password = $_POST['contrasena'];

            // Realiza la lógica de autenticación (puedes hacerlo en el modelo de usuario)
            $userModel = new UserModel();
            $authenticated = $userModel->authenticate($username, $password);

            if ($authenticated) {
                // Inicio de sesión exitoso, redirige a la página de inicio
                //header('Location: /');
                echo '<script type="text/javascript">';
                echo 'window.location.href="/";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=/" />';
                echo '</noscript>'; exit;
            } else {
                // Inicio de sesión fallido, muestra un mensaje de error o redirige a la página de inicio de sesión con un mensaje de error
                $msg = array('tipo' => 'error', 'contenido' => 'Usuario o contraseña incorrectos');
                $_SESSION['mensaje'] = $msg;
                //header('Location: /login');
                echo '<script type="text/javascript">';
                echo 'window.location.href="/login";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=/login" />';
                echo '</noscript>'; exit;
            }
        }

        ?>
<main>
    <h3>Login</h3>
    <div class="login-form">
        <form action="/login" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</main>
<?php
    }

    public function getLogout() {
        // Destruye la sesión
        session_start();
        session_destroy();

        // Redirige al usuario al inicio de la aplicación
        //header('Location: /');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=/" />';
        echo '</noscript>'; exit;
    }
}
?>