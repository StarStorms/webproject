<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 20:43
 */

function connexionDb()
{
    $confDb = getConfigFile()['DATABASE'];


    $type = $confDb['type'];
    $host = $confDb['host'];
    $servername = "$type:host=$host";
    $username = $confDb['username'];
    $password = $confDb['password'];
    $dbname = $confDb['dbname'];

    $db = new PDO("$servername;dbname=$dbname", $username, $password);
    return $db;
}