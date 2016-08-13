<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<?php
/**
 * Afficher le formulaire demandant le nouveau mot de passe
 * @param String $code Le code du ticket de reactivation
 */
function afficherFormulaireReinitaliserMdp($code)
{
    if(verifString($_POST['code']))
    {
?>
        <form action="index.php?page=reinitialiser_mdp" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control" />
            </div>
            <div class="form-group">
                <label for="mdpConfirm">Mot de passe de confirmation: </label>
                <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control" />
            </div>
            <input type="hidden" name="code" id="hiddenField" value="<?php echo($code); ?>" />
            <button type="submit" class="btn btn-default">Envoyer</button>
        </form>
<?php
    }
    else
    {
        afficherAlertErreur("Une erreur est survenue");
    }
}
?>

<div class="container">
    <div class="jumbotron">
        <h1>Mot de passe perdu</h1>
        <p>Veuillez rentrer un nouveau mot de passe </p>
    </div>
</div>

<div class="container">
<?php
    include "Library/Page/Mdp_perdu.lib.php";
    /* Si es 2 mdp ont ete donnes, verifier qu'ils correspondent */
    if(isset($_SESSION['rep_secrete']) && $_SESSION['rep_secrete'] == TRUE)
    {
        if(verifString($_POST['mdp'])
            && verifString($_POST['mdpConfirm']))
        {
            if(verifMdpReinitialisation($_SESSION['user_id'], $_POST['mdp'], $_POST['mdpConfirm']))
            {
                afficherAlertSucces("Votre mot de passe a été réinitialisé");
                
                //include "Library/Page/Inscription.lib.php";
                $user = getUserFromRecativationCode($_POST['code']);
                deleteReactivation($_POST['code']);
                setRecupGrade($_SESSION['mail_recup']);
                activateUser($_POST['code']);
                afficherAlertSucces("Votre mot de passe a été mis à jour !");           
                session_destroy();
                header("refresh:3;url=index.php" );
            }
            else
            {
                /* Si verifMdpReinitianlisation renvoie FALSE alors les mpd ne correspondent pas */
                /* Dans ce cas : afficher un message d'erreur et re-afficher le formulaire */
                afficherAlertErreur("Vos mots de passe doivent correspondre."); 
                afficherFormulaireReinitaliserMdp($_POST['code']);
            }
        }
        else
        {
            afficherFormulaireReinitaliserMdp($_POST['code']);
        }
    }
?>
</div>