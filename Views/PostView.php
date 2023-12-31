<?php
// views/PostView.php
class PostView
{
    //Renderizar vista prinicpal con los post
    public function renderView($posts, $page, $searchTerm = null)
    {

        if ($posts['content']->num_rows > 0) {
            echo "<div class='post-container'>";
            while ($row = $posts['content']->fetch_assoc()) {
                $postId = $row['publicacion_id'];
                $imgSrc = htmlspecialchars($row["imagen_url"]);

                // Enlace para seleccionar el post
                echo "<div class='post' >";
                echo "<a href='/showPost/" . $postId . "'><h3 class='title'>" . htmlspecialchars($row["titulo"]) . "</h3>";
                ?>
                <img src='/<?php echo ($imgSrc) ?>' alt='Imagen de la publicación'
                    onerror="this.onerror=null; this.src='/images/imgNoDisponible.gif'; this.alt='Imagen no encontrada'">
                <?php
                echo "<p>Publicado por " . htmlspecialchars($row["autor"]) . " | " . date('d-M-Y', strtotime(htmlspecialchars($row["fecha_publicacion"]))) . "</p>";
                echo "</a>";

                echo "<div class='post-actions'>";
                if ($row["publicado"] == 0) {
                    echo "<a href='/editPost/" . $row["publicacion_id"] . "'><span class='material-symbols-outlined'>preview</span>Revisar</a>";
                } else {
                    echo "<a href='/editPost/" . $row["publicacion_id"] . "'><span class='material-symbols-outlined'>edit_note</span>Editar</a>";
                }
                echo "<a href='#' onclick='abrirModal(\"/deletePost/{$row["publicacion_id"]}\")'><span class='material-symbols-outlined'>delete</span>Borrar</a>";
                echo "</div>";
                echo "</div>";
                ?>
                <script>
                    function abrirModal(url) {
                        showModal(url);
                    }
                </script>
                <?php

            }
            echo "</div>";
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $posts['totalPages']; $i++) {
                $class = $i === $page ? "current" : "";
                $class = intval($i) === intval($page) ? "current" : "";
                $link = $searchTerm ? "/searchPosts/$searchTerm/" : '/posts/';
                echo "<a href='" . $link . $i . "' class='" . $class . "'>" . $i . "</a>";

            }
            echo "</div>";
        } else {
            echo "<div class='no-publicaciones'>";
            echo "<img src='/images/noHayPublicaciones.gif' alt='No hay publicaciones disponibles.'>";
            echo "</div>";
        }
    }

    //Renderizar la vista para agregar una nueva publicación
    public function newPostView($categorias)
    {
        ?>
        <main>
            <div class="post-form">
                <form action="/newPost" method="post" enctype="multipart/form-data" class="new-post-form">
                    <legend>
                        <h3>Añadir Post</h3>
                    </legend>
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" maxlength="150" required>
                    <!-- Agregar campos para opciones de color y fuente -->
                    <label for="colorFuenteTitulo">Color del Título:</label>
                    <input type="color" id="colorFuenteTitulo" name="colorFuenteTitulo">

                    <label for="tipoLetraTitulo">Fuente del Título:</label>
                    <select id="tipoLetraTitulo" name="tipoLetraTitulo">
                        <!-- Opciones de Google Fonts-->
                        <option value="Kalam" class="kalam">Kalam</option>
                        <option value="Orbiton" class="orbiton">Orbiton</option>
                        <option value="Parisienne" class="parsienne">Parisienne</option>
                        <option value="Audiowide" class="audiowide">Audiowide</option>
                        <option value="Lato" class="lato">Lato</option>
                        <option value="Pacifico" class="pacifico">Pacifico</option>
                        <option value="Comfortaa" class="comfortaa">Comfortaa</option>
                        <option value="Arvo" class="arvo">Arvo</option>
                        <option value="Nova Square" class="novaSquare">Nova Square</option>
                    </select>
                    <?php
                    echo "<label for='categoria'>Categoria:</label>";
                    echo "<select id='categoria' name='categoria'>";
                    foreach ($categorias as $cat) {
                        echo "<option value='$cat[0]'>$cat[1]</option>";
                    }
                    echo "</select>";
                    ?>
                    <label for="contenido">Contenido:</label>
                    <textarea id="contenido" name="contenido" rows="5" required></textarea>

                    <label for="imagen">Imagen:</label>
                    <img id='currentImage' src='' class='img-edit-post-form' />
                    <label for="imagen"></label>

                    <input type="file" id="imagen" name="imagen" accept="image/*" required onchange='previewImage()'>

                    <button type="submit"><span class="material-symbols-outlined">publish</span>Publicar</button>
                </form>
            </div>
        </main>
        <script>
            function previewImage() {
                var input = document.getElementById('imagen');
                var currentImage = document.getElementById('currentImage');

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        currentImage.src = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
        <?php
    }


    //Renderizar la vista para editar una publicación
    public function editPostView($post, $categorias)
    {
        $id = $post['publicacion_id'];
        $titulo = $post['titulo'];
        $contenido = $post['contenido'];
        $categoria_id = $post['categoria_id'];
        $imgSrc = htmlspecialchars($post["imagen_url"]);
        ?>
        <main>
            <div class="post-form">
                <?php
                echo "<form action='/editPost/$id' method='post' enctype='multipart/form-data' class='edit-post-form'>";
                echo "<h3>Editar Post</h3>";
                echo "<label for='titulo'>Título:</label>";
                echo "<input type='text' id='titulo' name='titulo' value='$titulo' required>";
                echo "<label for='categoria'>Categoria:</label>";
                echo "<select id='categoria' name='categoria'>";
                foreach ($categorias as $cat) {
                    $selected = ($categoria_id == $cat[0]) ? 'selected' : '';
                    echo "<option value='$cat[0]' $selected>$cat[1]</option>";
                }
                echo "</select>";
                echo "<label for='contenido'>Contenido:</label>";
                echo "<textarea id='contenido' name='contenido' rows='5' required>$contenido</textarea>";


                echo "<label for='imagen'>Imagen actual:</label>";
                echo "<img id='currentImage' src='../" . $imgSrc . "' class='img-edit-post-form' />";

                echo "<label for='imagen'>Cambiar Imagen:</label>";
                echo "<input type='file' id='imagen' name='imagen' accept='image/*' onchange='previewImage()'>";


                if ($post["publicado"] == 0) {
                    echo "<button type='submit'><span class='material-symbols-outlined'>publish</span>Publicar</button>";
                } else {
                    echo "<button type='submit'><span class='material-symbols-outlined'>save</span>Guardar Cambios</button>";
                }
                echo "</form>";
                ?>
            </div>
        </main>
        <script>
            function previewImage() {
                var input = document.getElementById('imagen');
                var currentImage = document.getElementById('currentImage');

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        currentImage.src = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
        <?php
    }

    // Función para renderizar la vista detallada de una sola publicación
    public function renderPostDetail($post)
    {
        ?>
        <main>
            <div class='post-list'>
                <?php
                $imgSrc = htmlspecialchars($post["imagen_url"]);
                echo "<div class='post-list-big'>";
                // Mostrar el título con la fuente y color elegidos
                echo "<h3 style='color: " . $post["color_titulo"] . "; font-family: " . $post["fuente_titulo"] . "'>" . $post["titulo"] . "</h3>";
                echo "<p>" . $post["contenido"] . "</p>";
                ?>
                <img src='/<?php echo ($imgSrc) ?>' alt='Imagen de la publicación' onerror="this.onerror=null; 
                    this.src='/images/imgNoDisponible.gif'; this.alt='Imagen no encontrada'">
                <?php
                echo "<p>Publicado por " . htmlspecialchars($post["autor"]) . " | " . date('d-M-Y', strtotime(htmlspecialchars($post["fecha_publicacion"]))) . "</p>";
                echo "<div class='post-actions'>";
                echo "<a href='/' ><span class='material-symbols-outlined'>arrow_back</span>Volver</a>";
                echo "</div>";
                echo "</div>";
                ?>
            </div>
        </main>
        <?php
    }
}
?>