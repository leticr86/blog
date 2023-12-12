<?php
// models/PostModel.php
include_once('./config.php');

class PostModel
{
    //Obtiene todos los post
    public function getAllPosts($page, $postsPerPage, $onlyPublished = true, $category = null)
    {

        $limit = ($page - 1) * $postsPerPage;
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error en la conexión a la base de datos: " . $conn->connect_error);
        }

        // Obtener el total de resultados sin la limitación de la paginación
        if ($onlyPublished === true) {
            $sql = "SELECT COUNT(*) as total FROM publicaciones JOIN categorias on publicaciones.categoria_id = categorias.categoria_id WHERE publicado = 1";
            if ($category) {
                $sql = $sql . " AND categorias.nombre = '$category'";
            }
            $totalResult = $conn->query($sql)->fetch_assoc()['total'];
        } else {
            $totalResult = $conn->query("SELECT COUNT(*) as total FROM publicaciones")->fetch_assoc()['total'];
        }

        $total_pages = ceil($totalResult / $postsPerPage);

        if ($total_pages < $page) {
            $page = $total_pages;

        }

        // Consulta para obtener las publicaciones con paginación y búsqueda
        $sql = "SELECT publicaciones.publicacion_id, publicaciones.titulo, publicaciones.contenido, publicaciones.imagen_url, publicaciones.color_titulo, publicaciones.fuente_titulo, publicaciones.publicado, usuarios.nombre as autor, publicaciones.fecha_publicacion, categorias.nombre as categoria
        FROM publicaciones
        JOIN usuarios ON publicaciones.autor_id = usuarios.usuario_id JOIN categorias on categorias.categoria_id = publicaciones.categoria_id";
        if ($onlyPublished === true) {
            $sql = $sql . "  WHERE publicado = 1";
        }
        if ($category) {
            $sql = $sql . " AND categorias.nombre = '$category'";
        }
        $sql = $sql . " ORDER BY fecha_publicacion DESC LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $postsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();
        return ['content' => $result, 'totalPages' => $total_pages];
    }

    //Agrega un nuevo post a la base de datos.
    public function addPost($titulo, $contenido, $colorFuenteTitulo, $fuenteTitulo, $imagen_path, $categoria_id)
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path);

        // Insertar la información en la base de datos
        $usuario = $_SESSION['usuario'];
        $sql = "INSERT INTO publicaciones (titulo, contenido, imagen_url, autor_id, color_titulo, fuente_titulo, categoria_id) 
                VALUES ('$titulo', '$contenido', '$imagen_path', (SELECT usuario_id FROM usuarios WHERE nombre = '$usuario'), '$colorFuenteTitulo', '$fuenteTitulo', $categoria_id)";
        $result = $conn->query($sql);
        $conn->close();
        if ($result === TRUE) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Publicación añadida con éxito.');
            //header('Location: /');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/" />';
            echo '</noscript>';
            exit;
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Error al publicar el post.');
            //header("location: /newPost");
            echo '<script type="text/javascript">';
            echo 'window.location.href="/newPost";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/newPost" />';
            echo '</noscript>';
            exit;
        }

    }

    public function getPostDetail($postId, $onlyPublished = true)
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Consulta para obtener la información del post
        $sql = "SELECT publicaciones.publicacion_id, publicaciones.titulo, publicaciones.contenido, publicaciones.imagen_url, publicaciones.color_titulo, publicaciones.fuente_titulo, publicaciones.publicado, usuarios.nombre as autor, publicaciones.fecha_publicacion, categorias.nombre as categoria, categorias.categoria_id as categoria_id
        FROM publicaciones
        JOIN usuarios ON publicaciones.autor_id = usuarios.usuario_id JOIN categorias on categorias.categoria_id = publicaciones.categoria_id WHERE publicacion_id = $postId";
        if ($onlyPublished === true) {
            $sql = $sql . " AND publicaciones.publicado = 1";
        }
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'No se ha encontrado el post');
            //header('Location: /');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/" />';
            echo '</noscript>';
            exit;
        }
    }

    //Edita la información de un post existente.
    public function editPost($postId, $titulo, $contenido, $imagen_path, $categoria_id)
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Verifica si se ha cargado una nueva imagen
        if (isset($_FILES['imagen']['name']) && !empty($_FILES['imagen']['name'])) {
            // Sube la nueva imagen al servidor
            $imagen_path = 'uploads/' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path);

            // Actualiza la información del post en la base de datos, incluyendo la nueva imagen
            $update_sql = "UPDATE publicaciones SET titulo = '$titulo', contenido = '$contenido', publicado = 1, imagen_url = '$imagen_path', categoria_id ='$categoria_id' WHERE publicacion_id = $postId";
        } else {
            // Actualiza la información del post en la base de datos sin cambiar la imagen
            $update_sql = "UPDATE publicaciones SET titulo = '$titulo', contenido = '$contenido', publicado = 1, categoria_id ='$categoria_id' WHERE publicacion_id = $postId";
        }
        $result = $conn->query($update_sql);
        $conn->close();

        if ($result === TRUE) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Publicación actualizada con éxito.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'Error al guardar los cambios.');
        }
        //header('Location: /');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=/" />';
        echo '</noscript>';
        exit;
    }

    public function deletePost($postId)
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Consulta para obtener información sobre el post
        $sql = "SELECT * FROM publicaciones WHERE publicacion_id = $postId";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Elimina el post de la base de datos
            $delete_sql = "DELETE FROM publicaciones WHERE publicacion_id = $postId";

            if ($conn->query($delete_sql) === TRUE) {
                $_SESSION['mensaje'] = array('tipo' => 'success', 'contenido' => 'Publicación eliminada con éxito.');
                //header("location: /posts");
                echo '<script type="text/javascript">';
                echo 'window.location.href="/posts";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=/posts" />';
                echo '</noscript>';
                exit;
            } else {
                $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'No se ha podido borrar el post.');
                //header('Location: /');
                echo '<script type="text/javascript">';
                echo 'window.location.href="/";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=/" />';
                echo '</noscript>';
                exit;
            }
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'error', 'contenido' => 'El post indicado no existe.');
            //header('Location: /');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=/" />';
            echo '</noscript>';
            exit;
        }

        // Cerrar la conexión
        $conn->close();
    }
    public function getAllCategories()
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        $sql = "SELECT * FROM categorias";
        $result = $conn->query($sql);
        $conn->close();
        return $result->fetch_all();
    }

    //Realiza una búsqueda de post por un término específico.
    public function getPostSearch($searchTerm, $page, $postsPerPage)
    {
        $limit = ($page - 1) * $postsPerPage;
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPassword = DB_PASSWORD;
        $dbName = DB_NAME;
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error en la conexión a la base de datos: " . $conn->connect_error);
        }

        // Obtener el total de resultados sin la limitación de la paginación
        $sql = "SELECT COUNT(*) as total FROM publicaciones JOIN categorias on publicaciones.categoria_id = categorias.categoria_id WHERE publicado = 1 AND (publicaciones.titulo LIKE '%" . htmlspecialchars($searchTerm) . "%' OR publicaciones.contenido LIKE '%" . htmlspecialchars($searchTerm) . "%')";
        $totalResult = $conn->query($sql)->fetch_assoc()['total'];


        $total_pages = ceil($totalResult / $postsPerPage);

        if ($total_pages < $page) {
            $page = $total_pages;

        }

        // Consulta para obtener las publicaciones con paginación y búsqueda
        $sql = "SELECT publicaciones.publicacion_id, publicaciones.titulo, publicaciones.contenido, publicaciones.imagen_url, publicaciones.color_titulo, publicaciones.fuente_titulo, publicaciones.publicado, usuarios.nombre as autor, publicaciones.fecha_publicacion, categorias.nombre as categoria
        FROM publicaciones
        JOIN usuarios ON publicaciones.autor_id = usuarios.usuario_id JOIN categorias on categorias.categoria_id = publicaciones.categoria_id WHERE publicaciones.titulo LIKE '%" . htmlspecialchars($searchTerm) . "%' OR publicaciones.contenido LIKE '%" . htmlspecialchars($searchTerm) . "%' ORDER BY fecha_publicacion DESC LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $postsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();
        return ['content' => $result, 'totalPages' => $total_pages];
    }
}
?>