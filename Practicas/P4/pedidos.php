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

$db = mysqli_connect("localhost", "nazaretrogue1819", "kOKKvziN", "nazaretrogue1819");

if(!$db)
{
    echo "<p>Error</p>";
    echo "<p>Código: ".mysqli_connect_errno()."</p>";
    echo "<p>Mensaje: ".mysqli_connect_error()."</p>";
    die("Fin de la ejecución");
}

mysqli_set_charset($db, "utf8");

if(isset($_POST['accion']) && isset($_POST['ISBN']))
{
    $buscado = strip_tags($_POST['ISBN']);
    $query = mysqli_query($db, "SELECT * FROM libros WHERE ISBN=$buscado");

    switch ($_POST['accion']) {
        case 'compra':
            FormCompra($query);
            break;
        case 'confirma':
            FormConfirma($query);
            break;
    }
}

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

?>
