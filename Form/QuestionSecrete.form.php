<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<?php
    include "Library/Page/Mdp_perdu.lib.php";
    $code = NULL;
    if(isset($_GET['code'])) {
        $code = $_GET['code'];
    }
    $question = getQuestionSecreteFromReactivationCode($code);
    if($question != NULL && strlen($question) > 0)
    {
?>

        <form action="index.php?page=reponse_secrete" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="reponse"> Question secrète : <?php echo($question) ?></label>
                <input type="textarea" id="reponse" name="reponse" placeholder="Votre reponse secrete" required class="form-control" />
                <input type="hidden" name="code" id="hiddenField" value="<?php echo($code) ?>" />
            </div>
            <button type="submit" class="btn btn-default">Envoyer</button>
        </form>

<?php
    }
    else
    {
?>

        <div class="alert alert-danger">
            <strong>Erreur!</strong> Une erreur est surevenue !
        </div>

<?php
    }
?>