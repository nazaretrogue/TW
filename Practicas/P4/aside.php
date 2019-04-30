<?php

echo "<aside id=\"rankings\">";

$autor1 = "";
$autor2 = "";
$autor3 = "";

$db = mysqli_connect("localhost", "nazaretrogue1819", "kOKKvziN", "nazaretrogue1819");

if(!$db)
{
    echo "<p>Error</p>";
    echo "<p>Código: ".mysqli_connect_errno()."</p>";
    echo "<p>Mensaje: ".mysqli_connect_error()."</p>";
    die("Fin de la ejecución");
}

mysqli_set_charset($db, "utf8");

// $query = mysqli_query($db, "SELECT autor FROM libros AS I WHERE count(autor) > ANY (SELECT count(autor) from libros AS L WHERE L.autor != I.autor)");

if(!empty($query) && mysqli_num_rows($query)>0)
{
    $autor1 = mysqli_fetch_array($query);
    $autor2 = $autor1['autor'];

    echo "$autor2";
}

echo "</aside></div>";

mysqli_close($db);

?>
