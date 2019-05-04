<?php

session_start();

if(isset($_POST["usuario"]))
{
    if($_POST["usuario"] == "usuario" && $_POST["password"] == "password")
        $_SESSION["usuario"] = $_POST["usuario"];
}

elseif(isset($_POST["logout"]))
    Desloguearse();

include_once "index.php";

echo "<div class=\"centro\"><main class=\"busqueda_libro\">";

if(isset($_SESSION["usuario"]))
{
    echo <<< HTML
        <h2>Bienvenido {$_SESSION["usuario"]}, ¿qué necesitas?</h2>
        <class='accion'><form action="gestionar_db.php" method='POST'>
            <input type="submit" name="crear" value="Añadir libro"/>
            <input type="submit" name="modificar" value="Modificar libro"/>
            <input type="submit" name="borrar" value="Eliminar libro"/>
        </form>
        <class='form_log'><form action="index.php" method='POST'>
            <input type="submit" name="logout" value="Logout"/>
        </form>
HTML;
}

else
    Loguearse();

echo "</main></div>";

function Loguearse()
{
    echo <<< HTML
        <form action="login.php" method='POST'>
            <label>Usuario<input type="text" name="usuario"></label>
            <label>Contraseña<input type="password" name="password"></label>
            <input type="submit" name="login" value="Login">
        </form>
HTML;
}

function Desloguearse()
{
    if(session_status() == PHP_SESSION_NONE)
        session_start();

    session_unset();

    $data = session_get_cookie_params();

    setcookie(session_name(), $_COOKIE[session_name()], time()-2592000, $data['path'],
              $data['domain'], $data['secure'], $data['httponly']);

    session_destroy();
}

?>
