<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */

function activateUser() {
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
            $userGrade = $user->getGrade();
            if($userGrade->getId() == 6)
            {            
                $am->deleteActivation($userId, "Activation");
                $um->updateUserGrade($userId, 5);
  ?>
                <div class="alert alert-success">
                    <strong>Succes</strong> Votre compte est maintenant confirmé !
                </div>
<?php
                return;
            }
        }
    }
?>
    <div class="alert alert-danger">
        <strong>Erreur!</strong> Une erreur est surevenue !
    </div>
<?php
}

function setActivation(Utilisateur $user) {
    $conf = parse_ini_file("config.ini.php");

    $am = new ActivationManager(connexionDb());
    $code = genererCode();
    $act = new Activation(array());
    $act->setCode($code);
    $act->setLibelle("Activation");
    $act->setIdUtilisateur($user->getId());
    $am->addActivation($act);

    $adresseAdmin = $conf['mail'];
    $to = $user->getEmail();
    $sujet = "Confirmation de l'inscription";
    $entete = "From:" . $adresseAdmin . "\r\n";
    $entete .= "MIME-Version: 1.0\r\n";
    $entete .= "Content-Type: text/html; charset=windows-1252\r\n";
    $message = '<html><body>';
    $message .= '<div align="center"><h1> Bienvenue sur le site des énigmes !</h1></div>';
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10" align="center">';
    $message .= "<tr style='background: #eee;'><td><strong>Nom d'utilisateur</strong> </td><td>" . $user->getNom() . "</td></tr>";
    $message .= "<tr><td><strong>Email:</strong> </td><td>" . $user->getEmail() . "</td></tr>";
    $message .= "<tr><td><strong>Cliquez sur ce lien pour confirmer l'inscription :</strong> </td><td><a href='http://www.193.190.65.94/HE201085/TRAV/201608/index.php?page=activation&code=" . $act->getCode() . "' target='_blank'>http://www.193.190.65.94/HE201085/TRAV/201608/index.php?page=activation&code=" . $act->getCode() . " </a></td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";
     echo("<h1> MAIL : </h1><br />".$message." <br />");
    //mail($to, $sujet, $message, $entete);
    ?>
    <div class="alert alert-success">
        <strong>Bravo!</strong> Vous avez reçu un mail avec votre code d'activation !
    </div>
    <?php
} 

function verifyInscription()
{
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    $false = false;


        if (isset($_POST['name'])) {
            $pseudo = strtolower($_POST['name']);
            $mail = $_POST['email'];
            $mailConfirm = $_POST['emailConfirm'];
            $mdp = $_POST['mdp'];
            $mdpConfirm = $_POST['mdpConfirm'];
            
            if (strlen($pseudo) < $conf['size_user_name_min'] && strlen($pseudo) > $conf['size_user_name_max']) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre pseudo ne posséde pas la taille requise !
                </div>
                <?php
                $false = true;
            }
            if (strlen($mdp) < $conf['size_user_mdp']) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre mot de passe ne posséde pas la taille requise !
                </div>
                <?php
                $false = true;
            }
            if (strcmp($mail, $mailConfirm) != 0) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre e-mail ne correspond pas à sa confirmation !
                </div>
                <?php
                $false = true;
            }
            if (strcmp($mdp, $mdpConfirm) != 0) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre mot de passe ne correspond pas à sa confirmation !
                </div>
                <?php
                $false = true;
            }

            $userTest = $um->getUserByUserName($pseudo);
            if ($userTest->getId() != NULL) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre pseudo est déjà pris !
                </div>
                <?php
                $false = true;
            }

            $userTest = $um->getUserByEmail($mail);

            if ($userTest->getId() != NULL) {
                ?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre e-mail est déjà pris !
                </div>
                <?php
                $false = true;
            }

            if (!$false) {
                $user = new Utilisateur(array());
                $user->setNom($pseudo);
                $user->setEmail($mail);
                $user->setMdp($mdp);
                $um->addUser($user);
                $user = $um->getUserByUserName($pseudo);
                $um->setUserGrade($user, 6);
                $um->setUserRole($user, 1);
                
                echo("enter function");
                setActivation($user);
/*
            $adresseAdmin = $conf['mail'];
            $to = $user->getEmail();
            $sujet = "Confirmation de l'inscription";
            $entete = "From:" . $adresseAdmin . "\r\n";
            $entete .= "MIME-Version: 1.0\r\n";
            $entete .= "Content-Type: text/html; charset=windows-1252\r\n";
            $message = '<html><body>';
            $message .= '<div align="center"><h1> Bienvenue sur le site des énigmes !</h1></div>';
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10" align="center">';
            $message .= "<tr style='background: #eee;'><td><strong>Nom d'utilisateur</strong> </td><td>" . $user->getNom() . "</td></tr>";
            $message .= "<tr><td><strong>Email:</strong> </td><td>" . $user->getEmail() . "</td></tr>";
            $message .= "<tr><td><strong>Cliquez sur ce lien pour confirmer l'inscription :</strong> </td><td><a href='http://193.190.65.94/HE201085/TRAV/201608/index.php?page=activation&code=".$act->getCode()."' target='_blank'>http://193.190.65.94/HE201085/TRAV/201608/index.php?page=activation&code=".$act->getCode()." </a></td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";
            mail($to, $sujet, $message, $entete);
            ?>
            <div class="alert alert-success">
                <strong>Bravo!</strong> Votre inscription est complète, vous avez reçu un mail avec votre code d'activation !
            </div>
            <?php
  */
 


            }
        }

}