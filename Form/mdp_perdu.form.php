<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */

/**
 * Reinitialisation du mot de passe en 4 étapes :
 * 1 : Entrer le pseudo
 * 2 : Si le pseudo est valide, retrouver la question secrète.
        _ Si il n'y a pas de question secrète : envoyer un mail de récupération
        _ Sinon demander la réponse à la question
 * 3 : vérifier la réponse à la question et rinitialiser le mot de passe si ok
 * 4 : Vérifier le mot de passe et le réinitialiser
 */


?>

<div class="container">
    <div class="jumbotron">
        <h1>Mot de passe perdu</h1>
        <p>Veuillez renseigner votre pseudo et répondez à votre question secèrete </p>
    </div>
</div>
<div class="container">
<form action="index.php?page=mdp_perdu" method="post" class="form-horizontal">
 <?php
    if(!isset($_POST['etape']) || $_POST['etape'] == 1) {
 ?>       
    <div class="form-group">
        <label for="name">Pseudo : </label>
        <input type="hidden" name="etape" id="hiddenField" value="2" />
        <input type="text" id="name" name="name" placeholder="Votre Pseudo" class="form-control" required <?php if(isset($_POST['name']))echo'value="'.$_POST['name'].'"';?>>
    </div>
<?php
    }
    else if(isset($_POST['etape']) && $_POST['etape'] == 2)
    {
        include "Library/Page/Mdp_perdu.lib.php";

        $exist=verifyPseudoExist($_POST['name']);
        if($exist == true)
        {
            $_SESSION['connected'] = false;
            $_SESSION['pseudo_recup'] = $_POST['name'];
            $_SESSION['question'] = retrieveQuestion();
            if(!isset($_SESSION['question'] ) || $_SESSION['question']  == NULL || $_SESSION['question']  == false || strlen($_SESSION['question'] ) == 0) {
                
                sendMailRecuperation($_SESSION['pseudo_recup']);
?>
            <div class="alert alert-success">
                Un mail contenant un lien vous a été envoyé. <br />
                Cliquez sur le lien pour réinitialiser votre mot de passe.
            </div>
<?php
            session_destroy();
            header("refresh:3;url=index.php" );

          }
            else
            {
?>
                <div class="form-group">
                    <label for="name">Question secrète : <?php echo($_SESSION['question'] ) ?></label>
                    <input type="hidden" name="etape" id="hiddenField" value="3" />
                    <input type="text" id="reponse" name="reponse" placeholder="Votre réponse" class="form-control" required <?php if(isset($_POST['reponse']))echo'value="'.$_POST['reponse'].'"';?>>
                </div>
<?php
            }
        }
        else 
        {
?>
            <div class="form-group">
                <label for="name">Pseudo : </label>
                <input type="hidden" name="etape" id="hiddenField" value="2" />
                <input type="text" id="name" name="name" placeholder="Votre Pseudo" class="form-control" required <?php if(isset($_POST['name']))echo'value="'.$_POST['name'].'"';?>>
            </div>
            <div class="alert alert-danger">
                <strong>Erreur!</strong> Votre pseudo n'est pas valide !
            </div>
<?php
        }
    }
    else if (isset($_POST['etape']) && $_POST['etape'] == 3) 
    {
        include "Library/Page/Mdp_perdu.lib.php";

        $bonne = VerifierReponseSecrete($_SESSION['pseudo_recup']);
        if($bonne == false)
        {            
 ?>
            <div class="form-group">
                <label for="name">Question secrète : <?php echo($_SESSION['question'] ) ?></label>
                    <input type="hidden" name="etape" id="hiddenField" value="3" />
                    <input type="text" id="reponse" name="reponse" placeholder="Votre réponse" class="form-control" required <?php if(isset($_POST['reponse']))echo'value="'.$_POST['reponse'].'"';?>>
            </div>
            <div class="alert alert-danger">
                <strong>Erreur!</strong> Votre réponse n'est pas valide !
            </div>
<?php
        }
        else
        {
?>
            <div class="form-group">
                <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
            </div>
            <div class="form-group">
                <label for="mdpConfirm">Mot de passe de confirmation: </label>
                <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control">
                <input type="hidden" name="etape" id="hiddenField" value="4" />
            </div>
 <?php
        }
    }
    else if (isset($_POST['etape']) && $_POST['etape'] == 4) 
    {
//        include "Library/Page/Mdp_perdu.lib.php";

        $error = verifMdpReinitialisation($_SESSION['pseudo_recup']);
        if($error == true)
        {
?>
            <div class="form-group">
                <label for="mdp">Réinitalisez votre mot de passe (4 caractères min): </label>
                <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
            </div>
            <div class="form-group">
                <label for="mdpConfirm">Mot de passe de confirmation: </label>
                <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required class="form-control">
                <input type="hidden" name="etape" id="hiddenField" value="4" />
            </div>
            <div class="alert alert-danger">
                <strong>Erreur!</strong> Vos mots de passes doivent correspondre !
            </div>
<?php
        }
        else
        {
?>
        <div class="alert alert-success">
            Un mail contenant un lien vous a été envoyé. <br />
            Cliquez sur le lien pour réinitialiser votre mot de passe.
        </div>
<?php
        session_destroy();
        header("refresh:3;url=index.php" );
            
        }
    }
?>
<button type="submit" class="btn btn-default">Envoyer</button>
</form>
</div>