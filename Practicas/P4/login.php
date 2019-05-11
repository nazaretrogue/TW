<?php
include_once "HTML_creation.php";

session_start();

if(isset($_POST["usuario"]))
{
    if(strip_tags($_POST["usuario"]) == "usuario" && strip_tags($_POST["password"]) == "password")
        $_SESSION["usuario"] = strip_tags($_POST["usuario"]);
}

elseif(isset($_POST["logout"]))
    Desloguearse();

include_once "init.html";
include_once "header.html";

if(!isset($_GET["acc"]))
    $_GET['acc'] = "";
elseif ($_GET["acc"] != "Catalogo" && $_GET["acc"] != "Pedidos" && $_GET["acc"] != "Tienda" && $_GET["acc"] != "Busqueda"
        && $_GET["acc"] != "Login")
    $_GET['acc'] = "";

HTML_menu_nav_login($_GET["acc"]);

echo "<div class=\"centro\"><main class=\"logueo\">";

if(isset($_SESSION["usuario"]))
{
    echo <<< HTML
        <h2>Bienvenido {$_SESSION["usuario"]}, ¿qué necesitas?</h2>

        <class='bot_ges'><form action="gestionar_db.php" method='POST'>
            <input type="hidden" name="accion" value="crear"/>
            <input type="submit" name="crear" value="Añadir libro"/>
        </form>

        <class='bot_ges'><form action="gestionar_db.php" method='POST'>
            <input type="hidden" name="accion" value="modificar"/>
            <input type="submit" name="modificar" value="Editar libro"/>
        </form>

        <class='bot_ges'><form action="gestionar_db.php" method='POST'>
            <input type="hidden" name="accion" value="borrar"/>
            <input type="submit" name="borrar" value="Eliminar libro"/>
        </form>

        <class='form_log'><form action="index.php" method='POST'>
            <input type="submit" name="logout" value="Logout"/>
        </form>
HTML;
}

else
    Loguearse();

echo "</main>";

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

function Loguearse()
{
    echo <<< HTML
        <form action="login.php" method='POST' enctype='multipart/form-data'>
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
