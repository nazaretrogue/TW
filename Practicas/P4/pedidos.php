<?php
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

if(isset($_POST['accion']) && isset($_POST['ISBN']))
{
    $buscado = $_POST['ISBN'];
    $query = mysqli_query($db, "SELECT * FROM libros WHERE ISBN=$buscado");

    switch ($_POST['accion']) {
        case 'compra':
            FormCompra($query);
            break;
        case 'confirma':
            FormConfirma($query);
            break;
    }
}

include_once "aside.php";
include_once "footer.html";
include_once "end.html";

function FormCompra($query)
{
    if(mysqli_num_rows($query)>0)
    {
        echo "<div class=\"centro\"><main class=\"catal_libros\">";

        $libro = mysqli_fetch_array($query);
        $data = base64_encode($libro['portada']);

        echo <<< HTML
            <div class="datos_libro">
                <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
                <h2>{$libro['titulo']}</h2>
                <p>{$libro['autor']}</p>
                <p>{$libro['editorial']}</p>
                <p>{$libro['precio']}€</p>
            </div>
            <div class="datos_cli">
                <class='boton_compra'><form action="pedidos.php" method='POST'>
                    <label>Nombre y apellidos<input type="text" name="cliente"/></label>
                    <label>Dirección<input type="text" name="direc"/></label>
                    <label>E-mail<input type="text" name="email"/></label>
                    <label>Nº de tarjeta<input type="text" name="tarjeta"/></label>
                    <label>Fecha de caducidad(mm/aa)<input type="text" name="caduc"/></label>
                    <label>CVC<input type="text" name="cvc"/></label>
                    <input type='hidden' name='accion' value='confirma'/>
                    <input type='hidden' name='ISBN' value='{$libro['ISBN']}'/>
                    <input type="submit" name='acept' value="Confirmar compra"/>
                </form>
            </div>
HTML;

        echo "</main>";
    }
}

function FormConfirma($query)
{
    if(!isset($_POST["cliente"]) || !NombreValido($_POST["cliente"]))
    {
        echo "<p class=\"error\">El nombre debe estar relleno con caracteres alfabéticos</p>";
        $err['cliente'] = true;
    }

    if(!isset($_POST["direc"]))
    {
        echo "<p class=\"error\">La dirección no puede estar vacía</p>";
        $err['direc'] = true;
    }

    if(!isset($_POST["email"]) || !EmailValido($_POST["email"]))
    {
        echo "<p class=\"error\">El e-mail debe tener la forma usuario@servidor.extension</p>";
        $err['email'] = true;
    }

    if(!isset($_POST["tarjeta"]) || !TarjetaValida($_POST["tarjeta"]))
    {
        echo "<p class=\"error\">Número de tarjeta erróneo</p>";
        $err['tarjeta'] = true;
    }

    if(!isset($_POST["caduc"]) || !CaducidadValida($_POST["caduc"]))
    {
        echo "<p class=\"error\">Fecha no válida</p>";
        $err['caduc'] = true;
    }

    if(!isset($_POST["cvc"]) || !CVCValido($_POST["cvc"]))
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

            echo <<< HTML
                <div class="datos_libro">
                    <img src='data:image/jpeg;base64,$data' width="200" height="300"/>
                    <h2>{$libro['titulo']}</h2>
                    <p>{$libro['autor']}</p>
                    <p>{$libro['editorial']}</p>
                    <p>{$libro['precio']}€</p>
                </div>
                <div class="datos_cli">
                    <p>{$_POST['cliente']}</p>
                    <p>{$_POST['direc']}</p>
                    <p>{$_POST['email']}</p>
                    <p>{$_POST['tarjeta']}</p>
                    <p>{$_POST['caduc']}</p>
                    <p>{$_POST['cvc']}</p>
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
                <h2>{$libro['titulo']}</h2>
                <p>{$libro['autor']}</p>
                <p>{$libro['editorial']}</p>
                <p>{$libro['precio']}€</p>
            </div>
            <div class="datos_cli">
                <class='boton_compra'><form action="pedidos.php" method='POST'>
HTML;

        echo "<label>Nombre y apellidos<input type=\"text\" name=\"cliente\"";
        if(isset($err) && array_key_exists('cliente', $err))
            echo "/></label><p class=\"error\">El nombre debe estar relleno con caracteres alfabéticos</p>";
        else
            echo " value='".$_POST['cliente']."'/></label>";


        echo "<label>Dirección<input type=\"text\" name=\"direc\"";
        if(isset($err) && array_key_exists('direc', $err))
            echo "/></label><p class=\"error\">La dirección no puede estar vacía</p>";
        else
            echo " value='".$_POST['direc']."'/></label>";


        echo "<label>E-mail<input type=\"text\" name=\"email\"";
        if(isset($err) && array_key_exists('email', $err))
            echo "/></label><p class=\"error\">El e-mail debe tener la forma usuario@servidor.extension</p>";
        else
            echo " value='".$_POST['email']."'/></label>";


        echo "<label>Nº de tarjeta<input type=\"text\" name=\"tarjeta\"";
        if(isset($err) && array_key_exists('tarjeta', $err))
            echo "/></label><p class=\"error\">Número de tarjeta erróneo</p>";
        else
            echo " value='".$_POST['tarjeta']."'/></label>";


        echo "<label>Fecha de caducidad(mm/aa)<input type=\"text\" name=\"caduc\"";
        if(isset($err) && array_key_exists('caduc', $err))
            echo "/><p class=\"error\">Fecha no válida</p>";
        else
            echo " value='".$_POST['caduc']."'/></label>";


        echo "<label>CVC<input type=\"text\" name=\"cvc\"";
        if(isset($err) && array_key_exists('cvc', $err))
            echo "/></label><p class=\"error\">El CVC debe constar de 3 dígitos</p>";
        else
            echo " value='".$_POST['cvc']."'/></label>";

        echo "</form></main>";
    }
}

function NombreValido($nombre)
{
    return preg_match('/^[a-zA-Z ]*$/', $nombre);
}

function EmailValido($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function TarjetaValida($tarjeta)
{
    $impar = true;
    $sum = 0;

    foreach(array_reverse(str_split($tarjeta)) as $num)
    {
        $sum += array_sum(str_split(($impar = !$impar) ? $num*2 : $num));
    }

  return (($sum % 10 == 0) && ($sum != 0));
}

function CaducidadValida($caduc)
{
    return preg_match('/([0-1]?[0-9]{1}){1}\/([0-9]{2}){1}/', $caduc);
}

function CVCValido($cvc)
{
    return preg_match('/[0-9]{3}/', $cvc);
}

?>
