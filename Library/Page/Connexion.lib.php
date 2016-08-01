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
            $_SESSION['Utilisateur'] = $pseudo;
            $_SESSION['connected'] = true;
?>
            <div class="alert alert-success">
                <strong>Succes</strong> Vous êtes connecté(e) !
            </div>
<?php
        } else {
            $_SESSION['connected'] = false;
?>
              <div class="alert alert-danger">
                  <strong>Erreur!</strong> Couple identifiant - mot de passe incorrect !
              </div>
<?php
        }
    }
}
