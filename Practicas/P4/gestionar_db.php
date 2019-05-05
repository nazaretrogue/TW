<?php

session_start();

if(isset($_POST["usuario"]))
{
    if($_POST["usuario"] == "usuario" && $_POST["password"] == "password")
        $_SESSION["usuario"] = $_POST["usuario"];
}

include_once "init.html";
include_once "header.html";

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

$ejec = "";

if(isset($_SESSION['usuario']))
{
    if(isset($_POST['accion']))
    {
        switch ($_POST['accion']) {
            case 'crear':
                $ejec = InsertarLibro();
                break;
            case 'modificar':
                $ejec = ModificarLibro($db);
                if(isset($_POST['isbn_mod']))
                    DatosAModificar($db);
                break;
            case 'borrar':
                $ejec = BorrarLibro();
                break;
        }
    }
}

switch ($ejec) {
    case 'insert':
        if(!ErrorInsercion())
        {
            $isbn = mysqli_real_escape_string($db, $_POST['isbn_nuevo']);
            $tit = mysqli_real_escape_string($db, $_POST['tit_nuevo']);
            $aut = mysqli_real_escape_string($db, $_POST['aut_nuevo']);
            $gen = mysqli_real_escape_string($db, $_POST['gen_nuevo']);
            $ed = mysqli_real_escape_string($db, $_POST['edit_nuevo']);
            $pre = (float)$_POST['precio_nuevo'];
            $portada = addslashes(file_get_contents($_FILES['port_nuevo']));
            $query = mysqli_query($db, "INSERT INTO libros VALUES(\'$isbn\', \'$tit\', \'$aut\', $pre, \'$gen\', $portada, \'$ed\')");
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
            $query = mysqli_query($db, "UPDATE libros SET titulo=\'$tit\', autor=\'$aut\', genero=\'$gen\', editorial=\'$ed\', precio=$pre");
        }
        break;
    case 'delete':
        if(!ErrorBorrado())
        {
            $isbn_borrar = mysqli_real_escape_string($db, $_POST['isbn_elim']);
            echo "<h1>$isbn_borrar</h1>";
            $query = mysqli_query($db, "DELETE FROM libros WHERE ISBN=\'$isbn_borrar\'");
        }
        break;
}

echo "</main>";

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

function InsertarLibro()
{
    echo <<< HTML
        <div class="datos_libro"><form action="gestionar_db.php" method="POST" enctype="multipart/form-data">
            <h2>Introduce los datos del nuevo libro</h2>
            <label>ISBN<input type="text" name="isbn_nuevo"/></label>
            <label>Título<input type="text" name="tit_nuevo"/></label>
            <label>Autor<input type="text" name="aut_nuevo"/></label>
            <label>Editorial<input type="text" name="edit_nuevo"/></label>
            <label>Género<input type="text" name="gen_nuevo"/></label>
            <label>Precio<input type="text" name="precio_nuevo"/></label>
            <label>Portada<input type="file" name="port_nuevo" accept="image/png, image/jpeg"/></label>
            <input type="hidden" name="nuevo_libro" value="nuevo"/>
            <input type="submit" name='nuevo' value="Añadir"/>
        </div>
HTML;

    return "insert";
}

function ErrorInsercion()
{
    $error = false;

    if(isset($_POST['nuevo_libro']))
    {
        if(!ctype_digit($_POST['isbn_nuevo']))
            $error = true;

        if($_POST['precio_nuevo']<=0)
            $error = true;

        if(!isset($_POST['isbn_nuevo']) || !isset($_POST['tit_nuevo']) || !isset($_POST['aut_nuevo']) || !isset($_POST['gen_nuevo'])
           || !isset($_POST['edit_nuevo']) || !isset($_POST['precio_nuevo']) || !isset($_POST['port_nuevo']))
            $error = true;
    }

    else{
        $error = true;
        echo "<p class=\"error\">Los datos introducidos no son válidos</p>";
    }

    return $error;
}

function ModificarLibro($db)
{
    echo <<< HTML
        <div class="datos_libro"><form action="gestionar_db.php" method="POST" enctype="multipart/form-data">
            <h2>Introduce el ISBN del libro a modificar</h2>
            <label>ISBN<input type="text" name="isbn_mod"/></label>
            <input type="hidden" name="accion" value="envio"/>
            <input type="submit" name='envio_isbn' value="Aceptar"/>
        </div>
HTML;

    return 'update';
}

function DatosAModificar($db)
{
    $isbn_modificar = mysqli_real_escape_string($db, $_POST['isbn_mod']);

    $consulta = mysqli_query($db, "SELECT * FROM libros WHERE ISBN=\"$isbn_modificar\"");

    if($libro = mysqli_fetch_array($consulta))
    {
        echo <<< HTML
            <section>
                <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
HTML;
                echo "<label>ISBN<input type=\"text\" value='".$isbn_modificar."' readonly></label>";

        echo <<< HTML
                <label>Título<input type="text" name="tit_mod"/></label>
                <label>Autor<input type="text" name="aut_mod"/></label>
                <label>Editorial<input type="text" name="edit_mod"/></label>
                <label>Género<input type="text" name="gen_mod"/></label>
                <label>Precio<input type="text" name="precio_mod"/></label>
                <input type="hidden" name="mod_libro" value="mod"/>
                <input type="submit" name='modif' value="Modificar"/>
HTML;
    }

    else {
        echo "<p class=\"error\">No existe el libro especificado</p>";
    }
}

function ErrorModificacion()
{
    $error = false;

    if(isset($_POST['mod_libro']))
    {
        if($_POST['precio_mod']<=0)
            $error = true;

        if(!isset($_POST['tit_mod']) || !isset($_POST['aut_mod']) || !isset($_POST['gen_mod'])
           || !isset($_POST['edit_mod']) || !isset($_POST['precio_mod']))
            $error = true;
    }

    else
        $error = true;

    return $error;
}

function BorrarLibro()
{
    echo <<< HTML
        <div class="datos_libro"><form action="gestionar_db.php" method="POST" enctype="multipart/form-data">
            <h2>Introduce el ISBN del libro a borrar</h2>
            <label>ISBN<input type="text" name="isbn_elim"/></label>
            <input type="hidden" name="elim_libro" value="eliminar"/>
            <input type="submit" name='elim' value="Eliminar"/>
        </div>
HTML;

    return "delete";
}

function ErrorBorrado()
{
    $error = false;

    if(isset($_POST['elim_libro']))
    {
        if(!isset($_POST['isbn_elim']))
            $error = true;

        else
        {
            if(!ctype_digit($_POST['isbn_elim']))
                $error = true;
        }
    }

    return $error;
}

?>
