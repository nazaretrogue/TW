<?php

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
