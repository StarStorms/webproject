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

function verifyEmailExist($mail) 
{    
    echo("<br />Verify EMAIL : ".$mail);
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    
    if(!isset($user) || $user->getId() == NULL)
    {
        return false;
    }
    else 
    {
       return true;
    }

}

function VerifierReponseSecrete($mail) {    
    if(!isset($_POST['reponse']))
    {
        return false;
    }
        echo("<br />mail : ".$mail);
    echo("<br />REPONSE : ".$_POST['reponse']);
    
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);

    $bonneRep = $user->getReponseSecrete();
    
    echo("<br />Bonne rep : ".$bonneRep);

    
    $rep = hash("sha256", $_POST['reponse']);
        echo("<br />rep : ".$rep);

    if($rep == $bonneRep)
    {
        return true;
    }
    else
    {
        return false;
    }
}

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

function verifMdpReinitialisation($userId) 
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserById($userId);
       
    if(isset($_POST['mdp']) && isset($_POST['mdpConfirm'])
       && strlen($_POST['mdp']) > 4 && strcmp($_POST['mdp'], $_POST['mdpConfirm']) == 0)
    {
        $user->setMdp($_POST['mdp']);
        $um->updateUserMdp($user);
        /*TODO modifier pour revenir a l'ancien grade et nn juste le 5 */
        $um->updateUserGrade($user->getId(), 5);
?>        
        <div class="alert alert-success">
            <strong>Succès</strong> Votre mot de passe a été réinitialisé !
        </div>
<?php
        return true;
    }
    else
    {
        return false;
    }
}

function setRecupGrade($mail)
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    $userId = $user->getId();
    $um->updateUserGrade($userId, 3);
}

function setMDPPerduGrade($mail)
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserByEmail($mail);
    $userId = $user->getId();
    $um->updateUserGrade($userId, 4);
}

function deleteReactivation($code) 
{
    $conf = parse_ini_file("config.ini.php");
    $am = new ActivationManager(connexionDb());
    $act = $am->getActivationByCode($code);
    $userId = $act->getIdUtilisateur();
    $am->deleteActivation($act->getId(), $act->getLibelle());
}

function getUserFromRecativationCode($code)
{
    echo("<br />getUserFromRecativationCode : ".$code);
    if($code != NULL)
    {
        $conf = parse_ini_file("config.ini.php");
        $am = new ActivationManager(connexionDb());
        $um = new UtilisateurManager(connexionDb());
        $act = $am->getActivationByCode($code);
        echo("<br />act libel : ".$act->getLibelle());
        if(isset($act) && strcmp($act->getLibelle(), "Mot de passe perdu") == 0) 
        {
            $userId = $act->getIdUtilisateur();
            echo("<br /> user id from act: ".$userId);
            $user = $um->getUserById($userId);
            
            if($user->getNom() != NULL && strlen($user->getNom()) > 0)
            {
                return $user;
            }
        }        
    }
    
    return NULL;
}

function getQuestionSecreteFromReactivationCode($code) 
{
    echo("<br />getQuestionSecreteFromReactivationCode : ".$code);
    $user = getUserFromRecativationCode($code);
    echo("<br /> user id : ".$user->getId());
    if($user != NULL)
    {
        $question = $user->getQuestionSecrete();
        if($question != NULL && strlen($question) > 0)
        {
            return $question;
        }
    }
    return NULL;
}
