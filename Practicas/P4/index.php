<?php
include_once "init.html";
include_once "header.html";

if(!isset($_GET["acc"]))
    $_GET['acc'] = "";
elseif ($_GET["acc"] != "Catalogo" && $_GET["acc"] != "Pedidos" && $_GET["acc"] != "Tienda" && $_GET["acc"] != "Busqueda")
    $_GET['acc'] = "";

HTML_menu_nav($_GET["acc"]);

switch ($_GET['acc']) {
    case "Catalogo": include "catalogo.php"; break;
    case "Busqueda": include "busqueda.php"; break;
    case "Tienda": include "tienda.html"; break;
    default:
        echo "<div><main class=\"centro\"><h2>Bienvenidos al Lector de Libros</h2>";
    break;
}

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

function HTML_menu_nav($activo){
echo <<< HTML
<nav id="paginas_indice">
<ul>
HTML;

    $items = ["Catalogo"=>"Catalogo", "Busqueda"=>"Busqueda", "Tienda"=>"Tienda"];
    foreach ($items as $key => $value)
        echo "<li".($key==$activo?" class='activo'":"").">"."<a href='index.php?acc=".($key)."'>".$value."</a></li>";

echo <<< HTML
</ul>
</nav>
HTML;
}

?>
