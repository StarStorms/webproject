
<?php
if(isset($_SESSION['rep_secrete']) && $_SESSION['rep_secrete'] == true)
{
    if(isset($_POST['mpd']) && isset($_POST['mdpConfirm']))
    {
        $ok = verifMdpReinitialisation($_SESSION['user_id']);
        if($ok == true)
        {
            include "Library/Page/Inscription.lib.php";
            $user = getUserFromRecativationCode($_POST['code']);
            deleteReactivation($_POST['code']);
            setRecupGrade($_SESSION['mail_recup']);
            activateUser($user);
?>
            <div class="alert alert-success">
                <strong>Bravo!</strong> Votre mot de passe a été mis à jour !
            </div>
<?php            
            session_destroy();
            header("refresh:3;url=index.php" );
        }
        else
        {
 ?>
            <div class="container">
                <form action="index.php?page=reinitialiser_mdp" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                        <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mdpConfirm">Mot de passe de confirmation: </label>
                        <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control">
                    </div>
                    <div class="alert alert-danger">
                        <strong>Erreur!</strong> Vos mots de passes doivent correspondre !
                    </div>
                    <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
            </div>
            <div class="alert alert-danger">
                <strong>Erreur!</strong> Vos mots de passe doivent correspondre.
            </div>
<?php
        }
    }
    else
    {
?>

        <div class="container">
            <form action="index.php?page=reinitialiser_mdp" method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                    <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="mdpConfirm">Mot de passe de confirmation: </label>
                    <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control">
                </div>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Vos mots de passes doivent correspondre !
                </div>
                <input type="hidden" name="code" id="hiddenField" value="<?php echo($_POST['code']); ?>" />
                <button type="submit" class="btn btn-default">Envoyer</button>
            </form>
        </div>

<?php
    }
}
?>