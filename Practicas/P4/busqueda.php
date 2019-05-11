<?php
include_once "HTML_creation.php";

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

Formulario_busqueda($array_generos);

mysqli_close($db);

?>
