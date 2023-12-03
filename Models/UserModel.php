<?php
// models/UserModel.php

include_once('./config.php');

class UserModel {
    public function authenticate($username, $password) {
        // Realiza la lógica de autenticación
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        $sql = "SELECT * FROM usuarios WHERE nombre = '$username'";
        $result = $conn->query($sql);
        $conn->close();
        $msg = [];
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['contraseña'];
        
            // Verificar si la contraseña proporcionada coincide con el hash almacenado
            if (password_verify($password, $hashed_password)) {
                // Las contraseñas coinciden, iniciar sesión
                $_SESSION['usuario'] = $username; // Guarda el nombre de usuario en la sesión
                $_SESSION['rol'] = $row['rol']; // Guarda el rol en la sesión
                return true;
            } else {
                // Las contraseñas no coinciden
                return false;
            }
        } else {
            // Usuario no encontrado
            return false;
        }

    }

    //Obtiene todos los usuarios de la base de datos.
    public function getAllUsers() {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die("Error en la conexión a la base de datos: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM usuarios ORDER BY nombre ASC";
        $result = $conn->query($sql);
        $conn->close();

        return ['content' => $result  ];
    }

    //Añade el usuario

    public function addUser($username, $password, $email, $rol) {
        // Hash de la contraseña antes de almacenarla en la base de datos
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Formato de correo electrónico no válido.');
            //header("Location: /newUser");
            echo '<script type="text/javascript">';
            echo 'window.location.href="/newUser";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/newUser" />';
            echo '</noscript>'; exit;
        }
        // Verificar si el usuario ya existe (por ejemplo, por su dirección de correo electrónico)
        $stmt_check_user = $conn->prepare("SELECT * FROM usuarios WHERE correo_electronico = ?");
        $stmt_check_user->bind_param('s', $email);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();
        // Cerrar la sentencia de verificación
        $stmt_check_user->close();
        if ($result_check_user->num_rows > 0) {
            // El usuario ya existe, puedes manejar esto de acuerdo a tus necesidades
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'El usuario ya existe.');
            //header("Location: /newUser");
            echo '<script type="text/javascript">';
            echo 'window.location.href="/newUser";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/newUser" />';
            echo '</noscript>'; exit;
        }
        $url ="/users";

        // Insertar el nuevo usuario en la base de datos
        if ($this->isValidPassword($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt_insert_user = $conn->prepare("INSERT INTO usuarios (nombre, correo_electronico, contraseña, rol) VALUES (?, ?, ?, ?)");
        $stmt_insert_user->bind_param('ssss', $username, $email, $hashedPassword, $rol);

        if ($stmt_insert_user->execute()) {
            // Éxito al agregar el nuevo usuario
            $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Usuario agregado con éxito.');
        } else {
            // Error al agregar el nuevo usuario
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Error al agregar el nuevo usuario.');
            $url = "/newUser";
        }
                // Cerrar la sentencia de inserción
        $stmt_insert_user->close();
        } else {
            $url = "/newUser";
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'La contraseña debe tener al menos 8 caracteres, incluir letras mayúsculas, minúsculas, números y caracteres especiales.');
        }

        
        // Cerrar la conexión
        $conn->close();

        // Redirigir de nuevo a la página de listado de usuarios
            //header("Location: /users");
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/users" />';
            echo '</noscript>'; exit;

    }

    public function deleteUSer($userId) {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        $stmt_delete_user = $conn->prepare("DELETE FROM usuarios WHERE usuario_id = ?");
        $stmt_delete_user->bind_param('i', $userId);
        if ($stmt_delete_user->execute()) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Usuario eliminado correctamente.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Error al eliminar el usuario.');
        }

        // Cerrar la sentencia de eliminación
        $stmt_delete_user->close();
        // Cerrar la conexión
        $conn->close();
        //header('Location: /users');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/users";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=/users" />';
        echo '</noscript>'; exit;

    }
     public function updateUser($userId, $username, $email, $rol) {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Formato de correo electrónico no válido.');
            //header('Location: /users');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/users";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/users" />';
            echo '</noscript>'; exit;
        }

        // Actualizar los datos del usuario en la base de datos
        $stmt_update_user = $conn->prepare("UPDATE usuarios SET nombre = ?, correo_electronico = ?, rol = ? WHERE usuario_id = ?");
        $stmt_update_user->bind_param('sssi', $username, $email, $rol, $userId);

        if ($stmt_update_user->execute()) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Usuario actualizado exitosamente.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Error al actualizar el usuario.');
        }

        // Cerrar la sentencia de actualización
        $stmt_update_user->close();
        // Cerrar la conexión
        $conn->close();

        //header('Location: /users');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/users";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=/users" />';
        echo '</noscript>'; exit;
     }

    private function isValidPassword($password) {
        // Validar la contraseña
        $strongRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/';
        return preg_match($strongRegex, $password);
    }
        
}
?>