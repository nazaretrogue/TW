<?php
include_once "init.html";
include_once "header.html";
include_once "HTML_creation.php";

if(!isset($_GET["acc"]))
    $_GET['acc'] = "";
elseif ($_GET["acc"] != "Catalogo" && $_GET["acc"] != "Pedidos" && $_GET["acc"] != "Tienda" && $_GET["acc"] != "Busqueda"
        && $_GET["acc"] != "Login")
    $_GET['acc'] = "";

Menu_navegacion($_GET['acc']);


if($_GET["acc"] != "Login"){
    include_once "aside.php";
    include_once "footer.html";
    include_once "end.html";
}

?>
