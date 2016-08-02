<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */


function verifyPseudoExist($pseudo) 
{    
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByUserName($pseudo);
    
    if(!isset($user) || $user->getId() == NULL)
    {
        return false;
    }
    else 
    {
       return true; 
    }

}

function retrieveQuestion()
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());    
    if(isset($_POST['name']) && strlen($_POST['name'])>0)
    {
        $pseudo = strtolower($_POST['name']);
        $user = $um->getUserByUserName($pseudo);
            $question = $user->getQuestionSecrete();
            return $question;
    }
}

function VerifierReponseSecrete() {    
    if(!isset($_POST['reponse']))
    {
        return false;
    }
    
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByUserName($pseudo);

    $bonneRep = $user->getReponseSecrete();
    $rep = hash("sha256", $_POST['reponse']);
    if($rep == $bonneRep)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function sendMailRecuperation($pseudo)
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByUserName($pseudo);
    
    $am = new ActivationManager(connexionDb());
    $code = genererCode();
    $act = new Activation(array());
    $act->setCode($code);
    $act->setLibelle("Activation");
    $act->setIdUtilisateur($user->getId());
    $am->addActivation($act);
    
    $url="http://www.193.190.65.94/HE201085/TRAV/201608/index.php?page=recuperation&code=" . $act->getCode() . "' target='_blank'";
    
    $adresseAdmin = $conf['mail'];
    $to = $user->getEmail();
    $sujet = "Reinitialisation de votre mot de passe";
    $entete = "From:" . $adresseAdmin . "\r\n";
    $entete .= "MIME-Version: 1.0\r\n";
    $entete .= "Content-Type: text/html; charset=windows-1252\r\n";
    $message = '<html><body>';
    $message .= '<div align="center"><h1> Récupérer votre mot de passe sur le site aux énigmes : </h1></div>';
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10" align="center">';
    $message .= "<tr style='background: #eee;'><td><strong>Nom d'utilisateur</strong> </td><td>" . $user->getNom() . "</td></tr>";
    $message .= "<tr><td><strong>Email:</strong> </td><td>" . $user->getEmail() . "</td></tr>";
    $message .= "<tr><td><strong>Cliquez sur ce lien pour retrouver votre mot de passe :</strong> </td><td><a href='" . $url . "'>" . $url . "</a></td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";
    mail($to, $sujet, $message, $entete);
}

function verifMdpReinitialisation($pseudo) 
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByUserName($pseudo);
    
    if(isset($_POST['mdp']) && isset($_POST['mdpConfirm'])
       && strlen($_POST['mdp']) > 4 && strcmp($_POST['mdp'], $_POST['mdpConfirm']) == 0)
    {
        $user->setMdp($_POST['mdp']);
        return true;
    }
    else
    {
        return false;
    }
}