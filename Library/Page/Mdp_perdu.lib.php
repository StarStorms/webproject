<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */


/**
 * Verifie si le pseudo d'un utilisateur existe dans la BDD
 * @param String $pseudo Le nom de l'utilisateur
 * @return boolean TRUE si l'uilisateur existe, FALSE sinon
 */
function verifyPseudoExist($pseudo) 
{
    if(!verifString($pseudo))
    {
        return FALSE;
    }
    
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByUserName($pseudo);
    
    return ($user != NULL
            && strcmp($user->getNom(), $pseudo) == 0);
}

/**
 * Verifie si l'adresse mail d'un utilisateur existe dans la BDD
 * @param String $mail L'adresse mail de l'utilisateur
 * @return boolean TRUE si l'uilisateur existe, FALSE sinon
 */
function verifyEmailExist($mail) 
{
    if(!verifString($mail))
    {
        return FALSE;
    }
    
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    
    return ($user != NULL
            && strcmp($user->getEmail(), $mail) == 0);
}

/**
 * Verifie si la reponse secrete est la meme que celle dans la BDD
 * @param String $mail L'adresse mail de l'utilisateur repondant a la question secrete
 * @param String $reponse La reponse a la question secrete, en clair (sans hash)
 * @return boolean TRUE si la reponse correspond a celle renseignee dans la BBD pour cet utilisateur
 */
function verifierReponseSecrete($mail, $reponse)
{
    if(!verifString($mail)
            && !verifString($reponse))
    {
        return FALSE;
    }

    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    $bonneRep = $user->getReponseSecrete();
    
    $rep = hash("sha256", $reponse);

    return (strcmp($rep, $bonneRep) == 0);
}

/**
 * Genere un code de ractivation, cree un ticket de reactivation dans la BDD puis envoie
 * un mail a l'utilisateur avec le code du ticket
 * @param String $mail Adresse mail de l'utilisateur
 */
function sendMailRecuperation($mail)
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    
    $am = new ActivationManager(connexionDb());
    $code = genererCode();
    $act = new Activation(array());
    $act->setCode($code);
    $act->setLibelle("Mot de passe perdu");
    $act->setIdUtilisateur($user->getId());
    $am->addActivation($act);
    
    $url="http://www.193.190.65.94/HE201085/TRAV/201608/index.php?page=recuperation&code=" . $act->getCode();
    
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
    echo("<h1> MAIL : </h1><br />".$message." <br />");
    //mail($to, $sujet, $message, $entete);
}

/**
 * Verifie si les 2 mdp fournis sont identiques et update le mdp de l'utilisateur dans la BDD
 * @param String $userId L'ID de l'utilisateur dans la BDD
 * @param String $mdp Le nouveau mdp de l'utilisateur
 * @param String $mdpConfirm Doit etre strictement identique a $mdp
 * @return boolean TRUE si les 2 mdp sont identiques et que le mdp a ete change, FALSE sinon
 */
function verifMdpReinitialisation($userId, $mdp, $mdpConfirm) 
{   
    if(strlen($mdp) >= 4
            && strcmp($mdp, $mdpConfirm) == 0)
    {
        $um = new UtilisateurManager(connexionDb());
        $user = $um->getUserById($userId);
        $user->setMdp($mdp);
        
        $um->updateUserMdp($user);
        /*TODO modifier pour revenir a l'ancien grade et nn juste le 5 */
        $um->updateUserGrade($user->getId(), 5);
        return TRUE;
    }
    return FALSE;
}

/**
 * Met un utilisateur dans l'etat "Recuperation"
 * @param String $mail L'adresse mail de l'utilisateur
 */
function setRecupGrade($mail)
{
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    $userId = $user->getId();
    $um->updateUserGrade($userId, 3);
}

/**
 * Met un utilisateur dans l'etat "mot de passe perdu"
 * @param String $mail L'adresse mail de l'utilisateur
 */
function setMDPPerduGrade($mail)
{
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    $userId = $user->getId();
    $um->updateUserGrade($userId, 4);
}

/**
 * Supprime le ticket de reactivation de la BDD
 * @param String $code Le code du ticket de ractivation
 */
function deleteReactivation($code) 
{
    $am = new ActivationManager(connexionDb());
    $act = $am->getActivationByCode($code);
    $am->deleteActivation($act->getId(), $act->getLibelle());
}

/**
 * Recupere l'utilisateur correspondant au ticket de reactivation
 * @param String $code Le code du ticket de reactivation
 * @return Utilisateur L'utilisateur trouve ou NULL si non trouve
 */
function getUserFromRecativationCode($code)
{
    if(verifString($code))
    {
        $am = new ActivationManager(connexionDb());
        $um = new UtilisateurManager(connexionDb());
        $act = $am->getActivationByCode($code);
        if(isset($act) && strcmp($act->getLibelle(), "Mot de passe perdu") == 0) 
        {
            $userId = $act->getIdUtilisateur();
            $user = $um->getUserById($userId);
            
            if(verifString($user->getNom()))
            {
                return $user;
            }
        }        
    }
    return NULL;
}

/**
 * Recupere la question secrete d'un utilisateur a partir d'un ticket de ractivation
 * @param String $code Le code du ticket de reactivation
 * @return String La question si elle a ete trouve et NULL sinon
 */
function getQuestionSecreteFromReactivationCode($code) 
{
    $user = getUserFromRecativationCode($code);
    if($user != NULL && verifString($user->getNom()))
    {
        $question = $user->getQuestionSecrete();
        if(verifString($question))
        {
            return $question;
        }
    }
    return NULL;
}
