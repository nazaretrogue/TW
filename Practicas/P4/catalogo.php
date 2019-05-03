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

if(isset($_POST['palabra_clave_busqueda']))
{
    $palabra = $_POST['palabra_clave_busqueda'];
    $query = mysqli_query($db, "SELECT * FROM libros");

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

if(mysqli_num_rows($query)>0)
{
    echo "<div class=\"centro\"><main class=\"catal_libros\">";

    while($libro=mysqli_fetch_array($query))
        Mostrar($libro);
}

echo "</main>";

function Mostrar($libro)
{
    $data = base64_encode($libro['portada']);

    echo <<< CONSULTA
        <section>
            <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
            <h2>{$libro['titulo']}</h2>
            <p>{$libro['autor']}</p>
            <p>{$libro['editorial']}</p>
            <p>{$libro['precio']}€</p>
            <class='boton_compra'><form action="pedidos.php" method='POST'>
                <input type='hidden' name='accion' value='compra'/>
                <input type='hidden' name='ISBN' value='{$libro['ISBN']}'/>
                <input type="submit" name='comprar' value="Comprar"/>
            </form>
        </section>
CONSULTA;
}

mysqli_close($db);

?>
