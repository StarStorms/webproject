<?php
echo("<br />sessions : ");
print_r($_SESSION);

$bonne = VerifierReponseSecrete($_SESSION['mail_recup']);
if($bonne == true)
{
    $user = getUserFromRecativationCode($_POST['code']);
    $_SESSION['user_id'] = $user->getId();
    $_SESSION['rep_secrete'] = true;

?>

    <form action="index.php?page=reinitialiser_mdp">
      <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
      <input type="submit" value="Réinitialisez votre mot de passe" />
    </form>  
    <div class="alert alert-success">
        <strong>Succès</strong> Votre réponse est correcte.
    </div>

<?php    
}
else
{
    $_SESSION['rep_secrete'] = false;
?>

    <form action="index.php?page=recuperation&code=<?php echo($_POST['code']); ?>">
      <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
      <input type="submit" value="Réessayer" />
    </form>  
    <div class="alert alert-danger">
        <strong>Erreur!</strong> Votre réponse est incorrecte.
    </div>

<?php
}
?>