<?php
// controllers/HomeController.php

include_once("./Models/PostModel.php");
include_once("./Views/HomeView.php");

class HomeController
{

    //Muestra la página principal con una lista de posts.
    public function index($page = 1)
    {
        $postsPerPage = 3;

        // Instancia el modelo
        $postModel = new PostModel();

        // Obtiene el listado de posts desde el modelo
        $posts = $postModel->getAllPosts($page, $postsPerPage);

        // Instancia la vista
        $homeView = new HomeView();

        // Renderiza los posts en la vista
        $homeView->renderView($posts, $page);
    }

    //Obtiene el número de página de la URL y redirige a la página correspondiente.
    public function getPage()
    {
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $page = end($url_segments);

        $this->index(intval($page));
        exit();
    }

    // Obtiene la categoría de la URL y muestra los posts de esa categoría.
    public function getCategory()
    {
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $page = 1;

        $category = $url_segments[2];
        if (count($url_segments) > 3) {
            $page = intval($url_segments[3]);
        }
        $postsPerPage = 3;

        // Instancia el modelo
        $postModel = new PostModel();

        // Obtiene el listado de posts desde el modelo
        $posts = $postModel->getAllPosts($page, $postsPerPage, true, $category);

        // Instancia la vista
        $homeView = new HomeView();

        // Renderiza los posts en la vista
        $homeView->renderView($posts, $page, $category);
    }

    //Realiza una búsqueda en los posts según el término de búsqueda proporcionado.
    public function getSearch()
    {
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $page = 1;
        if (count($url_segments) > 3) {
            $page = intval($url_segments[3]);
        }
        $searchTerm = urldecode($url_segments[2]);
        $postsPerPage = 3;

        // Instancia el modelo
        $postModel = new PostModel();
        $homeView = new HomeView();

        // Obtiene los posts de la búsqueda desde el modelo
        $post = $postModel->getPostSearch($searchTerm, $page, $postsPerPage);

        // Renderiza los posts de la búsqueda en la vista
        $homeView->renderView($post, $page, null, $searchTerm);


    }
}
?>