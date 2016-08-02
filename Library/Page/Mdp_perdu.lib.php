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
    $act->setLibelle("Reactivation");
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

function reactivateUser() {
    if(isset($_GET['code'])) 
    {
        $conf = parse_ini_file("config.ini.php");
        $am = new ActivationManager(connexionDb());
        $um = new UtilisateurManager(connexionDb());
        
        $act = $am->getActivationByCode($_GET['code']);
        $am->deleteActivation($userId, "Activation");
        if(isset($act) && strcmp($act->getLibelle(), "Activation") == 0) 
        {
            $userId = $act->getIdUtilisateur();
            $user = $um->getUserById($userId);
            if($user->getGrade() == 3)
            {
                $am->deleteActivation($userId, "Reactivation");
?>
            <form action="index.php?page=mdp_perdu_mail" method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                    <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="mdpConfirm">Mot de passe de confirmation: </label>
                    <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control">
                    <input type="hidden" name="user_name" id="hiddenField" value="<?php echo($user->getNom()) ?>" />
                </div>
                <button type="submit" class="btn btn-default">Envoyer</button>
            </form>
<?php
                
            }
        }
    }
?>
    <div class="alert alert-danger">
        <strong>Erreur!</strong> Une erreur est surevenue !
    </div>
<?php
}