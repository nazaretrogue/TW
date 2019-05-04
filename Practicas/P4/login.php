<?php

session_start();

if(isset($_POST["usuario"]))
{
    if($_POST["usuario"] == "usuario" && $_POST["password"] == "password")
        $_SESSION["usuario"] = $_POST["usuario"];
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

HTML_menu_nav($_GET["acc"]);

switch ($_GET['acc']) {
    case "Catalogo": include "catalogo.php"; break;
    case "Busqueda": include "busqueda.php"; break;
    case "Tienda": include "tienda.html"; break;
    case "Nuevo libro":
        echo <<< HTML
        <class='bot_ges'><id='formulario_crear'><form action="gestionar_db.php" method='POST' enctype='multipart/form-data'>
            <input type="hidden" name="accion" value="crear"/>
            <input type="submit" name="conf_insert" value="Añadir libro"/>
        </form>
HTML;
        include_once "gestionar_db.php"; break;
    case "Modificar libro":
        echo <<< HTML
        <class='bot_ges'><form action="gestionar_db.php" method='POST' enctype='multipart/form-data'>
            <input type="hidden" name="accion" value="modificar"/>
        </form>
HTML;
        break;
    case "Eliminar libro":
    echo <<< HTML
    <class='bot_ges'><form action="gestionar_db.php" method='POST' enctype='multipart/form-data'>
        <input type="hidden" name="accion" value="borrar"/>
    </form>
HTML;
        break;
    case "Login": break;
}

echo "<div class=\"centro\"><main class=\"busqueda_libro\">";

if(isset($_SESSION["usuario"]))
{
    echo <<< HTML
        <h2>Bienvenido {$_SESSION["usuario"]}, ¿qué necesitas?</h2>

        <class='form_log'><form action="index.php" method='POST' enctype='multipart/form-data'>
            <input type="submit" name="logout" value="Logout"/>
        </form>
HTML;
}

else
    Loguearse();

echo "</main></div>";

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

function HTML_menu_nav($activo){
echo <<< HTML
<nav id="paginas_indice">
<ul>
HTML;

    $items = ["Catalogo"=>"Catalogo", "Busqueda"=>"Busqueda", "Tienda"=>"Tienda",
              "Login"=>"Login", "Nuevo libro"=>"Nuevo libro", "Modificar libro"=>"Modificar libro",
              "Eliminar libro"=>"Eliminar libro"];
    foreach ($items as $key => $value)
    {
        if($key != "Login" && $key != "Nuevo libro" && $key != "Modificar libro" && $key != "Eliminar libro")
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='index.php?acc=".($key)."'>".$value."</a></li>";

        else if($key == "Login")
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='login.php'>".$value."</a></li>";

        else
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='login.php?acc=".($key)."'>".$value."</a></li>";
    }

echo <<< HTML
</ul>
</nav>
HTML;
}

?>
