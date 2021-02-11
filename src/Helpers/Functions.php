<?php

namespace App\Helpers;

class Functions
{
    static function centstoreal ($cents) {
        $resultado = ($cents / 100);
        $formato = number_format($resultado, 2, '.', ',');
        return $formato;
    }
}
