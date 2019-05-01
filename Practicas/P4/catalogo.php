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

$query = mysqli_query($db, "SELECT * FROM libros");

if(mysqli_num_rows($query)>0)
{
    echo "<div id=\"centro\"><main id=\"catal_libros\">";

    while($libro=mysqli_fetch_array($query))
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
}

echo "</main>";

mysqli_close($db);

?>
