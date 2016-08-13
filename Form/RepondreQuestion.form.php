<?php
if(isConnect()
        && verifString($_GET['code']))
{
    include "Library/Page/Enigmes.lib.php";
    include "Library/Page/Questions.lib.php";
    $code = $_GET['code'];
    $question = getQuestionById($code);
    $auteur = getAuteurQuestion($question->getId());
    if($question != NULL && $question->getId() != NULL)
    {
        $enigme = getEnigmeById($question->getEnigme());
        if($enigme != NULL && $enigme->getAuteur() == getIdSession())
        {
            if(isset($_POST['niveau']) && isset($_POST['reponse']))
            {
                $reponse = new Reponse(array());
                $niveau = getNiveauFromId($_POST['niveau']);
                $reponse->setNiveau($niveau);
                $reponse->setTexte($_POST['reponse']);
                $reponse->setQuestion($question->getId());

                addReponse($reponse);
                afficherAlertSucces("Votre reponse a été postée !");
            }
            else
            {
                $test_rep = getReponseQuestion($question->getId());
                if($test_rep == NULL)
                {
?>    

                    <div class="container">
                    <div class="jumbotron">
                        <h1><?php echo($enigme->getTitre()); ?></h1>
                        <p><?php echo($enigme->getTexte()); ?></p>
                    </div>
                    </div>
                    <div class="container">
                        <h1> Suggestion / réponse :</h1><br />
                        <table>
                            <tr>
                                <td>De :</td>
                                <td><?php echo($auteur->getNom()); ?></td>
                            </tr>
                            <tr>
                                <td>Le :</td>
                                <td><?php echo($question->getDateCrea()); ?></td>
                            </tr>
                            <tr>
                                <td>Suggestion :</td>
                                <td><?php echo($question->getTexte()); ?></td>
                            </tr>
                        </table>
                        <br /><h1>Répondre à la suggetion :</h1>
                        <form method="post" action="index.php?page=repondre_question&code=<?php echo($code); ?>">
                            <div class="form-group">
                               <label for="reponse">Réponse : </label>
                               <input type="textarea" id="reponse" name="reponse" required class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="niveau">La suggestion est : </label>
                                <select name="niveau">
<?php
                                    $niveaux = getAllNiveaux();
                                    foreach($niveaux as $elem)
                                    {
?>
                                    <option value="<?php echo($elem->getId()); ?>"><?php echo($elem->getLibelle()); ?></option>
<?php
                                    }
?>
                                </select>
                            </div>
                           <button type="submit" class="btn btn-default">Poster la réponse</button>
                        </form>
                    </div>

<?php
                }
                else
                {
                    afficherAlertErreur("Cette suggestion a déjà une réponse !");
                }
            }
        }
        else
        {
            afficherAlertErreur("Vous n'avez pas acces a cette page !");
        }
    }
    else
    {
        afficherAlertErreur("Une erreur est survenue");
    }
}
else
{
    afficherAlertErreur("Une erreur est survenue.");
}

?>
