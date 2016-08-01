<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 20:45
 */

/**
 * Fonction faisant débuter la session.
 */
function startSession()
{
    session_name("Erwan");
    session_start();
}

/**
 * Fonction permettant de savoir si un utilisateur est connecter
 * @return bool : true si il est connecté, false sinon.
 */
function isConnect()
{
    return (isset($_SESSION['Utilisateur']));
}

/**
 * Fonction permettant de récupérer la variable session lié à un utilisateur
 * @return string
 */
function getSessionUser()
{
    return (isConnect() ? $_SESSION['Utilisateur'] : new Utilisateur(array()));
}

/**
 * Fonction permettant de générer la session de l'utilisateur.
 * @param User $user : l'utilisateur concerné.
 */
function setSessionUser(Utilisateur $user)
{
    $_SESSION['Utilisateur'] = $user;
}
