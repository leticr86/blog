<?php
// views/UsersView.php

class UsersView
{

    // Función para renderizar la vista principal de usuarios
    public function renderView($users)
    {
        ?>
<main>
    <div class="user-container">
        <h3>Usuarios</h3>
        <a href="/newUser"><button><span class="material-symbols-outlined">person_add</span>Nuevo Usuario</button></a>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                        if ($users['content']->num_rows > 0) {
                            while ($row = $users['content']->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['nombre']}</td>";
                                echo "<td>{$row['correo_electronico']}</td>";
                                echo "<td>{$row['rol']}</td>";
                                echo "<td>
                        <a href='#' onclick='mostrarFormulario({$row['usuario_id']})'>Editar</a> |
                        <a href='#' onclick='abrirModal(\"/deleteUser/{$row["usuario_id"]}\")'>Borrar</a>
                      </td>";
                                echo "</tr>";

                                // Formulario de edición oculto
                                echo "<tr id='formulario_{$row['usuario_id']}' style='display:none;'>";
                                echo "<td colspan='4'>";
                                echo "<form action='/updateUser/{$row['usuario_id']}' method='post'>";
                                echo "<input type='hidden' name='usuario_id' value='{$row['usuario_id']}'>";
                                echo "<label for='nombre'>Nombre:</label>";
                                echo "<input type='text' id='nombre' name='nombre' value='{$row['nombre']}' required>";

                                echo "<label for='email'>Email:</label>";
                                echo "<input type='email' id='email' name='email' value='{$row['correo_electronico']}' required>";

                                echo "<label for='rol'>Rol:</label>";
                                echo "<select id='rol' name='rol' required>";
                                echo "<option value='USER' " . (($row['rol'] === 'USER') ? 'selected' : '') . ">USER</option>";
                                echo "<option value='ADMIN' " . (($row['rol'] === 'ADMIN') ? 'selected' : '') . ">ADMIN</option>";
                                echo "</select>";

                                echo "<button type='submit'><span class='material-symbols-outlined'>save</span>Guardar Cambios</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay usuarios</td></tr>";
                        }
                        ?>
            </tbody>
        </table>
    </div>
</main>

<script>
//Script para mostrar u ocultar el formulario de edición
function mostrarFormulario(usuarioId) {
    var formulario = document.getElementById('formulario_' + usuarioId);
    if (formulario.style.display === 'none' || formulario.style.display === '') {
        formulario.style.display = 'table-row';
    } else {
        formulario.style.display = 'none';
    }
}

// Función para abrir el modal de confirmación
function abrirModal(url) {
    showModal(url);
}
</script>
<?php
    }

    // Función para renderizar el formulario de creación de un nuevo usuario
    public function renderForm()
    {
        ?>
<main>
    <div>
        <form action="/newUser" method="post" class="new-user-form">
            <h3>Nuevo Usuario</h3>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="USER">USER</option>
                <option value="ADMIN">ADMIN</option>
            </select>

            <button type="submit"><span class="material-symbols-outlined">
                    add
                </span>Registrar Usuario</button>
        </form>
    </div>
</main>
<?php
    }
}
?>