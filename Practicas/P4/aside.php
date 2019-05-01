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

$query = mysqli_query($db, "SELECT autor, COUNT(*) FROM libros GROUP BY autor ORDER BY COUNT(*) DESC");

if(!empty($query) && mysqli_num_rows($query)>0)
{
    $data = "";

    while($autor3 == "")
    {
        $data = mysqli_fetch_array($query);

        if($autor1 == "")
            $autor1 = $data['autor'];

        elseif($autor2 == "" && $data['autor'] != $autor1)
            $autor2 = $data['autor'];

        elseif($autor3 == "" && $data['autor'] != $autor1 && $data['autor'] != $autor2)
            $autor3 = $data['autor'];
    }
}

echo <<< HTML
<p>Más populares</p>
<ol>
    <li>$autor1</li>
    <li>$autor2</li>
    <li>$autor3</li>
</ol>
</aside></div>
HTML;

mysqli_close($db);

?>
