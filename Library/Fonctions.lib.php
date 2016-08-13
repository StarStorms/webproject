<?php
/**
 * Created by Erwan
 * Date: 16/07/2016
 * Time: 14:45
 */
function genererCode() {
    $characts    = 'abcdefghijklmnopqrstuvwxyz';
    $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characts   .= '1234567890';
    $code_aleatoire      = '';

    for($i=0;$i < 6;$i++)    //10 est le nombre de caract�res
    {
        $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
    }
    return $code_aleatoire;
}

function verifString($string)
{
    return ($string != NULL 
             && is_string($string)
            && strlen($string) > 0);
}