<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 20:44
 */

function getConfigFile()
{
    if (file_exists("../config.ini.php")) {
        return parse_ini_file("../config.ini.php", true);
    } else {
        return parse_ini_file("../../config.ini.php", true);
    }

}