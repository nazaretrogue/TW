<?php
include_once "init.html";
include_once "header.html";

if(!isset($_GET["acc"]))
    $_GET['acc'] = "";
elseif ($_GET["acc"] != "Catalogo" && $_GET["acc"] != "Pedidos")
    $_GET['acc'] = "";

HTML_menu_nav($_GET["acc"]);

switch ($_GET['acc']) {
    case "Catalogo": include "catalogo.php"; break;
    case "Pedidos": include "pedidos.php"; break;
    case "Tienda": include "tienda.html"; break;
    default: break;
}

include_once "footer.html";
include_once "end.html";

function HTML_menu_nav($activo){
echo <<< HTML
<nav>
<h1> indice </h1>
<ul>
HTML;

    $items = ["Catalogo"=>"Catalogo", "Pedidos"=>"Pedidos", "Tienda"=>"Tienda"];
    foreach ($items as $key => $value)
        echo "<li".($key==$activo?" class='activo'":"").">"."<a href='index.php?acc=".($key)."'>".$value."</a></li>";

echo <<< HTML
</ul>
</nav>
HTML;
}

?>
