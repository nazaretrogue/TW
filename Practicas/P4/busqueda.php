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

$query = mysqli_query($db, "SELECT genero FROM libros");

if(mysqli_num_rows($query)>0)
{
    $array_generos = [];

    while($libro=mysqli_fetch_array($query))
    {
        if(!in_array($libro['genero'], $array_generos))
            array_push($array_generos, $libro['genero']);
    }
}

echo <<< HTML
    <div class="centro"><main class="busqueda_libro">
        <class='busqueda'><form action="index.php?acc=Catalogo" method='POST' enctype='multipart/form-data'>
            <label>Palabra clave de búsqueda: <input type="text" name="palabra_clave_busqueda"/></label>
            <p>Género <select name="genero">
                <option selected>- Elija el género -</option>
HTML;

                for($i=0; $i<sizeof($array_generos); $i++)
                    echo "<option>".$array_generos[$i]."</option>";

echo <<< HTML
            </select></p>
            <input type="submit" name='clave_busq' value="Aceptar"/>
        </form></main></div>
HTML;

mysqli_close($db);

?>
