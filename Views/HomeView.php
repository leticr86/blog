<?php
// views/HomeView.php

class HomeView {

    //Obtiene los iconos en función a la categoría
    private function getIcon($category) {
        $icon = "";
        switch ($category) {
            case "Juegos":
                $icon = "<span class='material-symbols-outlined'>toys_and_games</span>";
                break;
            case "Robótica":
                $icon = "<span class='material-symbols-outlined'>smart_toy</span>";
                break;            
            case "Libros":
                $icon = "<span class='material-symbols-outlined'>menu_book</span>";
                break;
            default:
           $icon = "<span class='material-symbols-outlined'>hallway</span>";
        }
        return $icon;   
    }

    //Renderiza la vista de la vista principal con los post
    public function renderView($posts, $page, $category = null, $searchTerm = null) {
        if ($posts['content']->num_rows > 0) {
            echo "<div class='post-container'>";
            while ($row = $posts['content']->fetch_assoc()) {
                $postId = $row['publicacion_id'];
                $imgSrc = htmlspecialchars($row["imagen_url"]);
        
                // Enlace para seleccionar el post

                echo "<div class='post'>";
                echo "<a href='/showPost/".$postId."'><h3 class='title'>"  . htmlspecialchars($row["titulo"]) . "</h3>";echo "</a>";
                echo "<h4>" . $this->getIcon(htmlspecialchars($row["categoria"])) . "Publicado por " . htmlspecialchars($row["autor"]) ;
                echo "</h4>";
                ?>
<img src='/<?php echo($imgSrc) ?>' alt='Imagen de la publicación' onerror="this.onerror=null; 
                this.src='/images/imgNodisponible.gif'; 
                this.alt='Imagen no encontrada'">
<?php
                 //Fecha
               echo "<p>" . date('d-M-Y', strtotime(htmlspecialchars($row["fecha_publicacion"]))) . "</p>";
                echo "</div>";
            
            }
            echo "</div>";
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $posts['totalPages']; $i++) {
                $class = intval($i) === intval($page) ? "current" : "";
                $link = $category ? "/category/$category/" : "/page/";
                $link = $searchTerm ? "/search/$searchTerm/" : $link;
                echo "<a href='".$link . $i . "' class='". $class ."'>" . $i . "</a>";

            }echo"</div>";
        } else {
            echo"<div class='no-publicaciones'>";
            echo "<img src='/images/noHayPublicaciones.gif' alt='No hay publicaciones disponibles.'>";
            echo"</div>";
        }
    }
}
?>