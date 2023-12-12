<?php
// controllers/PostController.php

include_once("./Models/PostModel.php");
include_once("./Views/PostView.php");

class PostController
{

    public function index()
    {
        $this->checkAdmin();
        $postsPerPage = 3;
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $end = intval(end($url_segments));
        $page = $end > 0 ? $end : 1;
        // Instancia el modelo
        $postModel = new PostModel();

        // Obtiene el listado de posts desde el modelo
        $posts = $postModel->getAllPosts($page, $postsPerPage, false);

        // Instancia la vista
        $postView = new PostView();

        // Renderiza los posts en la vista
        $postView->renderView($posts, $page);
    }

    //Muestra la página para crear un nuevo post.
    public function getNewPost()
    {
        $this->checkLogin();
        // Instancia la vista
        $postModel = new PostModel();
        $postView = new PostView();
        $categorias = $postModel->getAllCategories();
        $postView->newPostView($categorias);
    }

    //Procesa el formulario para crear un nuevo post.
    public function postNewPost()
    {
        $this->checkLogin();
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $colorFuenteTitulo = $_POST['colorFuenteTitulo']; // Nuevo campo para el color del título
        $fuenteTitulo = $_POST['tipoLetraTitulo']; // Nuevo campo para la fuente del título
        $imagen_path = 'uploads/' . basename($_FILES['imagen']['name']);
        $categoria_id = $_POST['categoria'];
        // Instancia el modelo
        $postModel = new PostModel();

        // Obtiene el listado de posts desde el modelo
        $posts = $postModel->addPost($titulo, $contenido, $colorFuenteTitulo, $fuenteTitulo, $imagen_path, intval($categoria_id));
    }

    //Muestra la página para editar un post existente.
    public function getEditPost()
    {
        $this->checkAdmin();
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $postId = end($url_segments);
        // Instancia el modelo
        $postModel = new PostModel();
        $postView = new PostView();

        $post = $postModel->getPostDetail(intval($postId), false);
        $categorias = $postModel->getAllCategories();
        $postView->editPostView($post, $categorias);

    }

    //Procesa el formulario para editar un post existente.
    public function postEditPost()
    {
        $this->checkAdmin();
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $postId = end($url_segments);
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $categoria_id = $_POST['categoria'];
        $imagen_path = 'uploads/' . basename($_FILES['imagen']['name']);
        $postModel = new PostModel();
        $postModel->editPost($postId, $titulo, $contenido, $imagen_path, intval($categoria_id));
    }

    //Elimina un post existente.
    public function getDeletePost()
    {
        $this->checkAdmin();
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $postId = end($url_segments);
        $postModel = new PostModel();
        $postModel->deletePost($postId);
    }

    //Muestra la página de detalle de un post.
    public function getShowPost()
    {
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $postId = end($url_segments);
        $postView = new PostView();
        $postModel = new PostModel();
        $postDetail = $postModel->getPostDetail($postId);
        $postView->renderPostDetail($postDetail);
    }

    //Verifica si el usuario tiene privilegios de administrador.
    //Si no tiene privilegios, redirige al usuario al inicio de la aplicación.
    private function checkAdmin()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'ADMIN') {
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

    //Verifica si el usuario ha iniciado sesión.
    //Si no ha iniciado sesión, redirige al usuario al inicio de la aplicación.
    private function checkLogin()
    {
        if (!isset($_SESSION['usuario'])) {
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

    //Realiza una búsqueda de posts según el término de búsqueda proporcionado.

    public function getSearchPosts()
    {
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $page = 1;
        if (count($url_segments) > 3) {
            $page = intval($url_segments[3]);
        }
        $searchTerm = urldecode($url_segments[2]);
        $postsPerPage = 3;

        $postModel = new PostModel();
        $postView = new PostView();
        $posts = $postModel->getPostSearch($searchTerm, $page, $postsPerPage);
        $postView->renderView($posts, $page, $searchTerm);


    }
}
?>