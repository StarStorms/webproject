<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<div class="container">
    <div class="jumbotron">
        <h1>Mot de passe perdu</h1>
        <p>Veuillez renseigner votre adresse mail et répondez à votre question secèrete </p>
    </div>
</div>
<div class="container">
    <form action="index.php?page=mdp_perdu" method="post" class="form-horizontal">
<?php
        if(!isset($_POST['etape']) || $_POST['etape'] == 1) {
            /* Demander l'adresse mail */
?>       
        <div class="form-group">
            <label for="name">Votre adresse mail : </label>
            <input type="hidden" name="etape" id="hiddenField" value="2" />
            <input type="text" id="mail" name="mail" placeholder="Votre adresse mail" class="form-control" required />
        </div>
<?php
        }
        else if(isset($_POST['etape']) && $_POST['etape'] == 2)
        {
            /* envoyer le mail */
            include "Library/Page/Mdp_perdu.lib.php";

            $mail = strtolower($_POST['mail']);
            if(verifyEmailExist($mail) == true)
            {
                $_SESSION['connected'] = false;
                $_SESSION['mail_recup'] = $mail;
                setMDPPerduGrade($mail);
                sendMailRecuperation($mail);
                afficherAlertSucces("Un mail contenant un lien vous a été envoyé. <br />Cliquez sur le lien pour réinitialiser votre mot de passe.");

                //header("refresh:3;url=index.php" );
            }
            else
            {
?>
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Nous n'avons pas pu trouver votre adresse mail.
                </div>
<?php
            }
        }
?>
        <button type="submit" class="btn btn-default">Envoyer</button>
    </form>
</div>