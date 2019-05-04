<?php

$db = mysqli_connect("localhost", "nazaretrogue1819", "kOKKvziN", "nazaretrogue1819");

if(!$db)
{
    echo "<p>Error</p>";
    echo "<p>Código: ".mysqli_connect_errno()."</p>";
    echo "<p>Mensaje: ".mysqli_connect_error()."</p>";
    die("Fin de la ejecución");
}

mysqli_set_charset($db, "utf8");

if(isset($_SESSION['usuario']))
{
    if(isset($_POST['accion']))
    {
        switch ($_POST['accion']) {
            case 'crear':
                InsertarLibro();
                if(!ErrorInsercion())
                {
                    $pre = (float)$_POST['precio'];
                    $portada = addslashes(file_get_contents($_FILES['port_nuevo']));
                    $query = mysqli_query($db, "INSERT INTO libros VALUES(\'$_POST[\'isbn_nuevo\']\',
                                          \'$_POST[\'tit_nuevo\']\', \'$_POST[\'aut_nuevo\']\', $pre,
                                          \'$_POST[\'gen_nuevo\']\', $portada, \'$_POST[\'edit_nuevo\']\')");
                }

                break;
            case 'modificar':
                ModificarLibro();
                break;
            case 'borrar':
                BorrarLibro();
                break;
        }
    }
}

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
            <input type="submit" name='nuevo_libro' value="Añadir"/>
        </div>
HTML;
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

        if(!isset($_POST['isbn_nuevo']) || !isset($_POST['tit_nuevo']) || !isset($_POST['aut_nuevo'] || !isset($_POST['gen_nuevo']))
           || !isset($_POST['edit_nuevo']) || !isset($_POST['precio_nuevo']) || !isset($_POST['port_nuevo']))
            $error = true;
    }

    else
    {
        echo "<p class=\"error\">Los datos introducidos no son válidos</p>";
        InsertarLibro();
    }

    return $error;
}

function ModificarLibro()
{

}

function BorrarLibro()
{

}

?>
