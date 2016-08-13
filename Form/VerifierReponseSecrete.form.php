<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<?php
if(verifierReponseSecrete($_SESSION['mail_recup'], $_POST['reponse']))
{
    $user = getUserFromRecativationCode($_POST['code']);
    $_SESSION['user_id'] = $user->getId();
    $_SESSION['rep_secrete'] = true;
    afficherAlertSucces("Votre réponse est correcte.");

?>

    <form method="post" action="index.php?page=reinitialiser_mdp">
      <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
      <input type="submit" value="Réinitialisez votre mot de passe" />
    </form>  
<?php    
}
else
{
    $_SESSION['rep_secrete'] = false;
    afficherAlertErreur("Votre reponse est incorrecte");
?>

    <form method="post" action="index.php?page=recuperation&code=<?php echo($_POST['code']); ?>">
      <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
      <input type="submit" value="Réessayer" />
    </form>  

<?php
}
?>