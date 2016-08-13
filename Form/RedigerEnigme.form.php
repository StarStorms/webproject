<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<?php
if(isConnect())
{
?>
    <div class="container">
        <div class="jumbotron">
            <h1>Énigme</h1>
            <p>Ecrivez une énigme !</p>
        </div>
    </div>
<?php
    if(!isset($_POST['titre']))
    {
?>    
        <div class="container">
            <form action="index.php?page=rediger_enigme" method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="titre">Titre  : (4 caractères min)</label>
                    <input type="text" id="name" name="titre" placeholder="Titre de l'énigme" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="enigme">Image de votre énigme : (falcutatif) </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" name="picture" />
                </div>
                <div class="form-group">
                    <label for="enigme">Votre énigme : (4 caractères min)</label>
                    <input type="textarea" id="enigme" name="enigme" required class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="enigme">Indice : (falcutatif)</label>
                    <input type="textarea" id="indice" name="indice" required class="form-control" required />
                </div>
                 <div class="form-group">
                    <label for="enigme">Image de l'indice : (falcutatif) </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" name="indice_picture" />
                </div>
                <button type="submit" class="btn btn-default">Envoyer</button>
            </form>
        </div>

<?php
    }
    else
    {
        include "Library/Page/Enigmes.lib.php";
        if(!posterEnigme())
        {
?>
        <div class="container">
                <form action="index.php?page=rediger_enigme" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="titre">Titre  : (4 caractères min)</label>
                        <input type="text" id="name" name="titre" placeholder="Titre de l'énigme" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="enigme">Image de votre énigme : (falcutatif) </label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        <input type="file" name="picture" />
                    </div>
                    <div class="form-group">
                        <label for="enigme">Votre énigme : (4 caractères min)</label>
                        <input type="textarea" id="enigme" name="enigme" required class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="enigme">Indice : (falcutatif)</label>
                        <input type="textarea" id="indice" name="indice" required class="form-control" required />
                    </div>
                     <div class="form-group">
                        <label for="enigme">Image de l'indice : (falcutatif) </label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        <input type="file" name="indice_picture" />
                    </div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
            </div>
<?php
            afficherAlertErreur("une erreur est survenue.");
        }
        else
        {
            afficherAlertSucces("Votre énigme a été postée.");
        }
    }
}
?>