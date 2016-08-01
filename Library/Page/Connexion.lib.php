<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */

function connectUser() {
    
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    
    if(isset($_POST['name']) && isset($_POST['mdp'])) {
        $pseudo = strtolower($_POST['name']);
        $mdp = $_POST['mdp'];
                
        $result=$um->verifyMdp($pseudo, $mdp);
        if($result == true)
        {
            $user=$um->getUserByUserName($pseudo);
            $grade = $um->getUserGrade($user);
            if($grade->getId()==1 || 
               $grade->getId()==2 || 
               $grade->getId()== 5)
            {
                $_SESSION['Utilisateur'] = $user->getNom();
                $_SESSION['id'] = $user->getId();
                $_SESSION['connected'] = true;
?>
                <div class="alert alert-success">
                    <strong>Succes</strong> Vous êtes connecté(e) !
                </div>
<?php
            
            }
            else
            {
?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Votre compte en actuellement dans l'état : 
                    <strong><?php echo($grade->getLibelle())?></strong>
                </div>
<?php
            }
        } 
        else 
        {
            $_SESSION['connected'] = false;
?>
              <div class="alert alert-danger">
                  <strong>Erreur!</strong> Couple identifiant - mot de passe incorrect !
              </div>
<?php
        }
    }
}
