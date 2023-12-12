<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Blog de Emma y Marina</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Vina+Sans&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Vina+Sans&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto:wght@300;400&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Open+Sans&family=Vina+Sans&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Kalam&family=Open+Sans&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Kalam&family=Open+Sans&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Kalam&family=Open+Sans&family=Parisienne&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Kalam&family=Open+Sans&family=Parisienne&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Kalam&family=Open+Sans&family=Pacifico&family=Parisienne&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Comfortaa&family=Inconsolata&family=Kalam&family=Open+Sans&family=Pacifico&family=Parisienne&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Arvo&family=Comfortaa&family=Inconsolata&family=Kalam&family=Open+Sans&family=Pacifico&family=Parisienne&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nova+Square&family=Nunito&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/images/cursor.png">


    <script src="/accessibility.js"></script>

</head>

<body>
    <header>
        <img src="/images/cabecera2.png" class='img-header' alt='EMMA Y MARINA - BIENVENID@S A NUESTRO BLOG'
            onerror="this.onerror=null; this.src='/images/imgNoDisponible.gif'">
        <img src="/images/cabecerapeq.png" class='img-header-movil' alt='EMMA Y MARINA - BIENVENID@S A NUESTRO BLOG'
            onerror="this.onerror=null; this.src='/images/imgNoDisponible.gif'">
        <nav>
            <?php
            if (isset($_SESSION['usuario'])) {
                if ($_SESSION['rol'] === "ADMIN") {
                    //Sección de navegación para usuarios administradores
                    echo '<a href="/"><span class="material-symbols-outlined">home</span>Inicio</a> | 
                <a href="/newPost"><span class="material-symbols-outlined">post_add</span>Añadir un Post</a> | 
                <a href="/posts"><span class="material-symbols-outlined">auto_awesome_motion</span>Gestionar Posts</a> | 
                <a href="/users"><span class="material-symbols-outlined">manage_accounts</span>Gestionar Usuarios</a> | 
                <a href="/logout"><span class="material-symbols-outlined">person_cancel</span>Cerrar Sesión</a>';
                    ?>

                    <form class='form-container'>
                        <input type="text" placeholder="Buscar..." name="searchTerm" id="searchTerm">

                        <button type="button" onclick="submitForm('searchPosts');"><span class=" material-symbols-outlined">
                                search
                            </span></button>
                    </form>

                    <?php
                } else {
                    //Sección de navegación para usuarios no administradores
                    echo '<a href="/"><span class="material-symbols-outlined">home</span>Inicio</a> | 
                <a href="/newPost"><span class="material-symbols-outlined">post_add</span>Añadir un Post</a>  | 
                <a href="/logout"><span class="material-symbols-outlined">person_cancel</span>Cerrar Sesión</a>';
                }
            } else {
                echo '<a href="/"><span class="material-symbols-outlined">home</span>Inicio</a> | 
            <a href="/category/dibujos"><span class="material-symbols-outlined">hallway</span>Dibujos</a> | 
            <a href="/category/robotica"><span class="material-symbols-outlined">smart_toy</span>Robótica</a> | 
            <a href="/category/juegos"><span class="material-symbols-outlined">toys_and_games</span>Juegos</a> | 
            <a href="/category/libros"><span class="material-symbols-outlined">menu_book</span>Libros</a>';
                ?>

                <!-- Formulario de búsqueda -->
                <form class='form-container'>
                    <input type="text" placeholder="Buscar..." name="searchTerm" id="searchTerm">

                    <button type="button" onclick="submitForm('search');"><span class=" material-symbols-outlined">
                            search
                        </span></button>
                </form>

                <?php
            }
            ?>
        </nav>
        <!-- Menú de accesibilidad -->
        <div class="contenedorAccesibilidad">
            <button type="button" id="menuAccesibilidad" onclick="mostrarBotones()">
                <span class="material-symbols-outlined">
                    settings_accessibility
                </span>Accesibilidad
            </button>
            <div class="contenedorBotones">
                <button type="button" id="aumentar" class="botonAcc" onclick="aumentarFuente()">
                    <span class="material-symbols-outlined">
                        text_increase
                    </span>Aumentar letra
                </button>
                <button type="button" id="disminuir" class="botonAcc" onclick="disminuirFuente()">
                    <span class="material-symbols-outlined">
                        text_decrease
                    </span>Disminuir letra
                </button>
                <button type="button" id="cambiarGris" class="botonAcc" onclick="cambiarGrises()">
                    <span class="material-symbols-outlined">
                        format_color_reset
                    </span>Cambiar a gris
                </button>
                <button type="button" id="cambiarFuente" class="botonAcc" onclick="cambiarFuente()">
                    <span class="material-symbols-outlined">
                        font_download
                    </span>Cambiar fuente
                </button>
            </div>
        </div>
    </header>

    <!-- modal.php -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>¿Estás seguro de que deseas realizar esta acción?</p>
            <button id="confirmButton" onclick="confirmAction()">Confirmar</button>
            <button id="cancelButton" onclick="cancelAction()">Cancelar</button>
        </div>
    </div>
    <script src="/modal.js"></script>
    <script>
        function submitForm(url) {
            // Obtén el valor del campo searchTerm
            var searchTerm = document.getElementById("searchTerm").value;

            // Construye la URL con el valor de searchTerm
            var url = "/" + url + "/" + encodeURIComponent(searchTerm);

            // Redirige a la nueva URL
            window.location.href = url;
        }
    </script>