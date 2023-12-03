<?php
// controllers/UserController.php

include_once("./Models/UserModel.php");
include_once("./Views/UsersView.php");

class UserController {

    //Muestra el formulario para crear un nuevo usuario.
    public function getNewUser() {
        $this->checkAdmin();
        $usersView = new UsersView();
        $usersView->renderForm();
    }

    //Muestra la lista de usuarios.
    public function getUsers() {
        $this->checkAdmin();
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        $usersView = new UsersView();
        $usersView->renderView($users);
    }

    //Procesa el formulario para crear un nuevo usuario.
    public function postNewUser() {
        $this->checkAdmin();
        $username = $_POST['nombre'];
        $password = $_POST['contrasena'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];
        $userModel = new UserModel();
        $userModel->addUser($username, $password, $email, $rol);
    }

    //Procesa el formulario para actualizar la información de un usuario existente.
    public function postUpdateUser() {
        $this->checkAdmin();
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $userId = end($url_segments);
        $username = $_POST['nombre'];
        $rol = $_POST['rol'];
        $email = $_POST['email'];
        $userModel = new UserModel();
        $userModel->updateUser($userId, $username, $email, $rol);
    }

    //Elimina un usuario existente.
    public function getDeleteUser() {
        $this->checkAdmin();
        $url_segments = explode('/', $_SERVER['REQUEST_URI']);
        $userId = end($url_segments);
        $userModel = new UserModel();
        $userModel->deleteUser($userId);
    }

    //Verifica si el usuario tiene privilegios de administrador.
     //Si no tiene privilegios, redirige al usuario al inicio de la aplicación.
    private function checkAdmin() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'ADMIN') {
        //header('Location: /');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=/" />';
        echo '</noscript>'; exit;
        }
    }

}
?>