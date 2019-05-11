<?php

session_start();

if(isset($_POST["usuario"]))
{
    if($_POST["usuario"] == "usuario" && $_POST["password"] == "password")
        $_SESSION["usuario"] = $_POST["usuario"];
}

include_once "init.html";
include_once "header.html";
include_once "HTML_creation.php";

if(!isset($_GET["acc"]))
    $_GET['acc'] = "";
elseif ($_GET["acc"] != "Catalogo" && $_GET["acc"] != "Pedidos" && $_GET["acc"] != "Tienda" && $_GET["acc"] != "Busqueda"
        && $_GET["acc"] != "Login")
    $_GET['acc'] = "";

Menu_navegacion_login($_GET["acc"]);

$db = mysqli_connect("localhost", "nazaretrogue1819", "kOKKvziN", "nazaretrogue1819");

if(!$db)
{
    echo "<p>Error</p>";
    echo "<p>Código: ".mysqli_connect_errno()."</p>";
    echo "<p>Mensaje: ".mysqli_connect_error()."</p>";
    die("Fin de la ejecución");
}

mysqli_set_charset($db, "utf8");

echo "<div class=\"centro\"><main class=\"logueo\">";

if(isset($_SESSION['usuario']))
{
    if(isset($_POST['accion']))
    {
        switch ($_POST['accion']) {
            case 'crear':
                InsertarLibro();
                break;
            case 'modificar':
                ModificarLibro($db);
                break;
            case 'enviar':
                if(isset($_POST['isbn_mod']))
                    DatosAModificar($db);
                break;
            case 'borrar':
                BorrarLibro();
                break;
        }
    }
}

// echo "<p>ESCUCHAR WARCRY / SARATOGA / STRAVAGANZZA / SKIZOO </p>";
// echo "<p>ANTES DEL SWITCH : ".$ejec."</p>";
if (isset($_POST['ejec'])) {
    switch ($_POST['ejec']) {
        case 'insert':
            if(!ErrorInsercion())
            {
                $isbn = mysqli_real_escape_string($db, $_POST['isbn_nuevo']);
                $tit = mysqli_real_escape_string($db, $_POST['tit_nuevo']);
                $aut = mysqli_real_escape_string($db, $_POST['aut_nuevo']);
                $gen = mysqli_real_escape_string($db, $_POST['gen_nuevo']);
                $ed = mysqli_real_escape_string($db, $_POST['edit_nuevo']);
                $pre = (float)$_POST['precio_nuevo'];
                $portada = mysqli_real_escape_string($db, file_get_contents($_FILES['port_nuevo']['tmp_name']));
                $query = mysqli_query($db, "INSERT INTO libros VALUES('$isbn', '$tit', '$aut', $pre, '$gen', '{$portada}', '$ed')")
                or die('Muerto' .mysqli_error($db));

                echo "<p>Introducido correctamente</p>";
            }

            else {
                echo "<p>Los datos no se han introducido</p>";
            }
            break;
        case 'update':
            if(!ErrorModificacion())
            {
                $isbn = mysqli_real_escape_string($db, $_POST['isbn_mod']);
                $tit = mysqli_real_escape_string($db, $_POST['tit_mod']);
                $aut = mysqli_real_escape_string($db, $_POST['aut_mod']);
                $gen = mysqli_real_escape_string($db, $_POST['gen_mod']);
                $ed = mysqli_real_escape_string($db, $_POST['edit_mod']);
                $pre = (float)$_POST['precio_mod'];
                $query = mysqli_query($db, "UPDATE libros SET titulo='$tit', autor='$aut', genero='$gen', editorial='$ed', precio='$pre' WHERE ISBN='$isbn'")
                or die('Muerto' .mysqli_error($db));

                echo "<p>Modificado correctamente</p>";
            }

            else {
                echo "<p>Los datos no se han modificado</p>";
            }
            break;
        case 'delete':
            if(!ErrorBorrado())
            {
                $isbn_borrar = mysqli_real_escape_string($db, $_POST['isbn_elim']);
                $query = mysqli_query($db, "DELETE FROM libros WHERE ISBN='$isbn_borrar'")
                or die('Muerto' .mysqli_error($db));

                echo "<p>Eliminado correctamente</p>";
            }

            else {
                echo "<p>El libro no se ha eliminado</p>";
            }
            break;
    }
}

echo "</main>";

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

function ErrorInsercion()
{
    $error = false;

    if(isset($_POST['ejec']))
    {
        if(!ctype_digit($_POST['isbn_nuevo']))
            $error = true;

        if($_POST['precio_nuevo']<=0)
            $error = true;

        if(!isset($_POST['isbn_nuevo']) || !isset($_POST['tit_nuevo']) || !isset($_POST['aut_nuevo']) || !isset($_POST['gen_nuevo'])
           || !isset($_POST['edit_nuevo']) || !isset($_POST['precio_nuevo']) || !isset($_FILES['port_nuevo']))
            $error = true;
    }

    else{
        $error = true;
        echo "<p class=\"error\">Los datos introducidos no son válidos</p>";
    }

    return $error;
}

function ErrorModificacion()
{
    $error = false;

    if(isset($_POST['ejec']))
    {
        if($_POST['precio_mod']<=0)
            $error = true;

        if(!isset($_POST['tit_mod']) || !isset($_POST['aut_mod']) || !isset($_POST['gen_mod'])
           || !isset($_POST['edit_mod']) || !isset($_POST['precio_mod']))
            $error = true;
    }

    else{
        $error = true;
        echo "<p class=\"error\">Los datos no son válidos</p>";
    }

    return $error;
}

function ErrorBorrado()
{
    $error = false;

    if(isset($_POST['ejec']))
    {
        if(!isset($_POST['isbn_elim']))
            $error = true;

        else
        {
            if(!ctype_digit($_POST['isbn_elim']))
                $error = true;
        }
    }

    else{
        $error = true;
        echo "<p class=\"error\">El ISBN introducido no es válido</p>";
    }

    return $error;
}

?>
