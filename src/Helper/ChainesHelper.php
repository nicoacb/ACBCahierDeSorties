<?php
namespace App\Helper;

use voku\helper\URLify;

class ChainesHelper
{
    public static function getLoginFromPrenomNom($prenom, $nom)
    {
        return str_replace(' ', '', strtolower(URLify::downcode($prenom . '.' . $nom)));
    }
}

?>