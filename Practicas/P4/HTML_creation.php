<?php
include_once "State_management.php";

function HTML_menu_nav($activo){
echo <<< HTML
<nav id="paginas_indice">
<ul>
HTML;

    $items = ["Catalogo"=>"Catalogo", "Busqueda"=>"Busqueda", "Tienda"=>"Tienda", "Login"=>"Login"];
    foreach ($items as $key => $value)
    {
        if($key != "Login")
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='index.php?acc=".($key)."'>".$value."</a></li>";

        else
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='login.php'>".$value."</a></li>";
    }

echo <<< HTML
</ul>
</nav>
HTML;
}

function Menu_navegacion($opcion){
    HTML_menu_nav($opcion);

    switch ($opcion) {
        case "Catalogo": include "catalogo.php"; break;
        case "Busqueda": include "busqueda.php"; break;
        case "Tienda": include "tienda.html"; break;
        case "Login": break;
        default:
            echo "<div class=\"centro\"><main><h2>Bienvenidos al Lector de Libros</h2></main>";
        break;
    }
}

function Formulario_busqueda($array_generos){
    echo <<< HTML
        <div class="centro"><main class="busqueda_libro">
            <h2>Indique una palabra clave o el género del libro que desea buscar</h2>
            <form action="index.php?acc=Catalogo" method='POST' enctype='multipart/form-data'>
                <label>Palabra clave de búsqueda: <input type="text" name="palabra_clave_busqueda"/></label>
                <label>Género: <select name="genero">
                    <option selected>- Elija el género -</option>
HTML;

                    for($i=0; $i<sizeof($array_generos); $i++)
                        echo "<option>".htmlentities($array_generos[$i])."</option>";

    echo <<< HTML
                </select></label>
                <input type="submit" name='clave_busq' value="Aceptar"/>
            </form></main>
HTML;
}

function Mostrar($libro)
{
    $data = base64_encode($libro['portada']);

    echo <<< HTML
        <section>
            <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
HTML;
            echo "<h2>".htmlentities($libro['titulo'])."</h2>";
            echo "<p>".htmlentities($libro['autor'])."</p>";
            echo "<p>".htmlentities($libro['editorial'])."</p>";
            echo "<p>".htmlentities($libro['precio'])."</p>";
        echo <<< HTML
            <class='boton_compra'><form action="pedidos.php" method='POST'>
                <input type='hidden' name='accion' value='compra'/>
                <input type='hidden' name='ISBN' value='{$libro['ISBN']}'/>
                <input type="submit" name='comprar' value="Comprar"/>
            </form>
        </section>
HTML;
}

function FormCompra($query)
{
    if(mysqli_num_rows($query)>0)
    {
        echo "<div class=\"centro\"><main class=\"pedidos\">";

        $libro = mysqli_fetch_array($query);
        $data = base64_encode($libro['portada']);

        echo <<< HTML
            <div class="datos_libro">
                <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
HTML;
                echo "<h2>".htmlentities($libro['titulo'])."</h2>";
                echo "<p>".htmlentities($libro['autor'])."</p>";
                echo "<p>".htmlentities($libro['editorial'])."</p>";
                echo "<p>".htmlentities($libro['precio'])."</p>";
        echo <<< HTML
            </div>
            <div class="datos_cli">
                <class='boton_compra'><form action="pedidos.php" method='POST' enctype='multipart/form-data'>
                    <label>Nombre<input type="text" name="cliente"/></label>
                    <label>Dirección<input type="text" name="direc"/></label>
                    <label>Email<input type="text" name="email"/></label>
                    <label>Tarjeta<input type="text" name="tarjeta"/></label>
                    <label>Caducidad(mm/aa)<input type="text" name="caduc"/></label>
                    <label>CVC<input type="text" name="cvc"/></label>
                    <input type='hidden' name='accion' value='confirma'/>
                    <input type='hidden' name='ISBN' value='{$libro['ISBN']}'/>
                    <input type="submit" name='accept' value="Confirmar compra"/>
                </form>
            </div>
HTML;

        echo "</main>";
    }
}

function FormConfirma($query)
{
    if(!isset($_POST["cliente"]) || !NombreValido(strip_tags($_POST["cliente"])))
    {
        echo "<p class=\"error\">El nombre debe estar relleno con caracteres alfabéticos</p>";
        $err['cliente'] = true;
    }

    if(!isset($_POST["direc"]))
    {
        echo "<p class=\"error\">La dirección no puede estar vacía</p>";
        $err['direc'] = true;
    }

    if(!isset($_POST["email"]) || !EmailValido(strip_tags($_POST["email"])))
    {
        echo "<p class=\"error\">El e-mail debe tener la forma usuario@servidor.extension</p>";
        $err['email'] = true;
    }

    if(!isset($_POST["tarjeta"]) || !TarjetaValida(strip_tags($_POST["tarjeta"])))
    {
        echo "<p class=\"error\">Número de tarjeta erróneo</p>";
        $err['tarjeta'] = true;
    }

    if(!isset($_POST["caduc"]) || !CaducidadValida(strip_tags($_POST["caduc"])))
    {
        echo "<p class=\"error\">Fecha no válida</p>";
        $err['caduc'] = true;
    }

    if(!isset($_POST["cvc"]) || !CVCValido(strip_tags($_POST["cvc"])))
    {
        echo "<p class=\"error\">El CVC debe constar de 3 dígitos</p>";
        $err['cvc'] = true;
    }

    if(empty($err))
    {
        if(mysqli_num_rows($query)>0)
        {
            echo "<div class=\"centro\"><main class=\"catal_libros\">";

            $libro = mysqli_fetch_array($query);
            $data = base64_encode($libro['portada']);
            $cli = strip_tags($_POST['cliente']);
            $dir = strip_tags($_POST['direc']);
            $email = strip_tags($_POST['email']);
            $tarj = strip_tags($_POST['tarjeta']);
            $cad = strip_tags($_POST['caduc']);
            $cvc = strip_tags($_POST['cvc']);

            echo <<< HTML
                <div class="datos_libro">
                    <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
                    <h2>{$libro['titulo']}</h2>
                    <p>{$libro['autor']}</p>
                    <p>{$libro['editorial']}</p>
                    <p>{$libro['precio']}€</p>
                </div>
                <div class="datos_cli">
                    <p>{$cli}</p>
                    <p>{$dir}</p>
                    <p>{$email}</p>
                    <p>{$tarj}</p>
                    <p>{$cad}</p>
                    <p>{$cvc}</p>
                </div>
HTML;

            echo "</main>";
        }
    }

    else
    {
        echo "<div class=\"centro\"><main class=\"catal_libros\">";

        $libro = mysqli_fetch_array($query);
        $data = base64_encode($libro['portada']);

        echo <<< HTML
            <div class="datos_libro">
                <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
HTML;
                echo "<h2>".htmlentities($libro['titulo'])."</h2>";
                echo "<p>".htmlentities($libro['autor'])."</p>";
                echo "<p>".htmlentities($libro['editorial'])."</p>";
                echo "<p>".htmlentities($libro['precio'])."</p>";
        echo <<< HTML
            </div>
            <div class="datos_cli">
                <class='boton_compra'><form action="pedidos.php" method='POST' enctype='multipart/form-data'>
HTML;

        echo "<label>Nombre y apellidos<input type=\"text\" name=\"cliente\"";
        if(isset($err) && array_key_exists('cliente', $err))
            echo "/></label><p class=\"error\">El nombre debe estar relleno con caracteres alfabéticos</p>";
        else
            echo " value='".strip_tags($_POST['cliente'])."'/></label>";


        echo "<label>Dirección<input type=\"text\" name=\"direc\"";
        if(isset($err) && array_key_exists('direc', $err))
            echo "/></label><p class=\"error\">La dirección no puede estar vacía</p>";
        else
            echo " value='".strip_tags($_POST['direc'])."'/></label>";


        echo "<label>E-mail<input type=\"text\" name=\"email\"";
        if(isset($err) && array_key_exists('email', $err))
            echo "/></label><p class=\"error\">El e-mail debe tener la forma usuario@servidor.extension</p>";
        else
            echo " value='".strip_tags($_POST['email'])."'/></label>";


        echo "<label>Nº de tarjeta<input type=\"text\" name=\"tarjeta\"";
        if(isset($err) && array_key_exists('tarjeta', $err))
            echo "/></label><p class=\"error\">Número de tarjeta erróneo</p>";
        else
            echo " value='".strip_tags($_POST['tarjeta'])."'/></label>";


        echo "<label>Fecha de caducidad(mm/aa)<input type=\"text\" name=\"caduc\"";
        if(isset($err) && array_key_exists('caduc', $err))
            echo "/><p class=\"error\">Fecha no válida</p>";
        else
            echo " value='".strip_tags($_POST['caduc'])."'/></label>";


        echo "<label>CVC<input type=\"text\" name=\"cvc\"";
        if(isset($err) && array_key_exists('cvc', $err))
            echo "/></label><p class=\"error\">El CVC debe constar de 3 dígitos</p>";
        else
            echo " value='".strip_tags($_POST['cvc'])."'/></label>";

        echo "</form></main>";
    }
}

function HTML_menu_nav_login($activo){
echo <<< HTML
<nav id="paginas_indice">
<ul>
HTML;

    $items = ["Catalogo"=>"Catalogo", "Busqueda"=>"Busqueda", "Tienda"=>"Tienda",
              "Login"=>"Login", "Nuevo libro"=>"Nuevo libro", "Modificar libro"=>"Modificar libro",
              "Eliminar libro"=>"Eliminar libro"];
    foreach ($items as $key => $value)
    {
        if($key != "Login" && $key != "Nuevo libro" && $key != "Modificar libro" && $key != "Eliminar libro")
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='index.php?acc=".($key)."'>".$value."</a></li>";

        else if($key == "Login")
            echo "<li".($key==$activo?" class='activo'":"").">"."<a href='login.php'>".$value."</a></li>";

        else
        {
            if(isset($_SESSION['usuario']))
                echo "<li".($key==$activo?" class='activo'":"").">"."<a href='login.php?acc=".($key)."'>".$value."</a></li>";
        }
    }

echo <<< HTML
</ul>
</nav>
HTML;
}

function Menu_navegacion_login($opcion){
    HTML_menu_nav_login($opcion);

    switch ($opcion) {
        case "Catalogo": include "catalogo.php"; break;
        case "Busqueda": include "busqueda.php"; break;
        case "Tienda": include "tienda.html"; break;
        case "Nuevo libro": break;
        case "Modificar libro": break;
        case "Eliminar libro": break;
        case "Login": break;
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
            <input type="hidden" name="ejec" value="insert"/>
            <input type="submit" name='nuevo' value="Añadir"/>
        </form></div>
HTML;
}

function ModificarLibro($db)
{
    echo <<< HTML
        <div class="datos_libro"><form action="gestionar_db.php" method="POST" enctype="multipart/form-data">
            <h2>Introduce el ISBN del libro a modificar</h2>
            <label>ISBN<input type="text" name="isbn_mod"/></label>
            <input type="hidden" name="accion" value="enviar"/>
            <input type="submit" name='envio_isbn' value="Aceptar"/>
        </form></div>
HTML;
}

function DatosAModificar($db)
{
    $isbn_modificar = mysqli_real_escape_string($db, $_POST['isbn_mod']);

    if(isset($isbn_modificar))
    {
        echo <<< HTML
            <div><form action="gestionar_db.php" method="POST">
                <label>ISBN<input type="text" name="isbn_mod" value={$isbn_modificar} readonly/></label>
                <label>Título<input type="text" name="tit_mod"/></label>
                <label>Autor<input type="text" name="aut_mod"/></label>
                <label>Editorial<input type="text" name="edit_mod"/></label>
                <label>Género<input type="text" name="gen_mod"/></label>
                <label>Precio<input type="text" name="precio_mod"/></label>
                <input type="hidden" name="ejec" value="update"/>
                <input type="submit" name='modif' value="Modificar"/>
            </form></div>
HTML;
    }

    else {
        echo "<p class=\"error\">No existe el libro especificado</p>";
    }
}

function BorrarLibro()
{
    echo <<< HTML
        <div class="datos_libro"><form action="gestionar_db.php" method="POST">
            <h2>Introduce el ISBN del libro a borrar</h2>
            <label>ISBN<input type="text" name="isbn_elim"/></label>
            <input type="hidden" name="ejec" value="delete"/>
            <input type="submit" name='elim' value="Eliminar"/>
        </form></div>
HTML;
}

?>
