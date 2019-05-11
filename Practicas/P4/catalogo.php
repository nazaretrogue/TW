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
$busqueda_palabra = false;

if(isset($_POST['palabra_clave_busqueda']) && !isset($_POST['genero']))
{
    $palabra = strip_tags($_POST['palabra_clave_busqueda']);
    $query = mysqli_query($db, "SELECT * FROM libros");
    $busqueda_palabra = true;

    if(mysqli_num_rows($query)>0)
    {
        echo "<div class=\"centro\"><main class=\"catal_libros\">";

        while($libro=mysqli_fetch_array($query))
        {
            if((strpos($libro['titulo'], $palabra) !== false) || (strpos($libro['autor'], $palabra) !== false))
                Mostrar($libro);
        }

        echo "</main>";
    }
}

elseif(isset($_POST['genero']))
{
    $gen = $_POST['genero'];
    $query = mysqli_query($db, "SELECT * FROM libros WHERE genero=\"$gen\"");
}

else
    $query = mysqli_query($db, "SELECT * FROM libros");

if(mysqli_num_rows($query)>0 && !$busqueda_palabra)
{
    echo "<div class=\"centro\"><main class=\"catal_libros\">";

    while($libro=mysqli_fetch_array($query))
        Mostrar($libro);
}

echo "</main>";

mysqli_close($db);

?>
