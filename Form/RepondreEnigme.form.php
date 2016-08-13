<?php

if(verifString($_GET['code'])
        && isConnect())
{
    include "Library/Page/Enigmes.lib.php";
    include "Library/Page/Questions.lib.php";

    $enigme = getEnigmeById($_GET['code']);
    $nomAuteur = getNomAuteurFromId($enigme->getAuteur());
    $indices = getIndicesEnigme($enigme->getId());
    $questions = getQuestionsEnigme($enigme->getId());
    
    if(isset($_POST['question']))
    {
        if(verifString($_POST['question']))
        {
            $nquestion = new Question(array());
            $nquestion->setTexte($_POST['question']);
            $nquestion->setAuteur(getIdSession());
            $nquestion->setEnigme($enigme->getId());

            addQuestion($nquestion);
            afficherAlertSucces("Votre suggestion a été ajoutée !");
        }
        else
        {
            afficherAlertErreur("Une erreur est survenue.");
        }
    }
?>

    <div class="container">
    <div class="jumbotron">
        <h1><?php echo($enigme->getTitre()); ?></h1>
        <p><?php echo($enigme->getTexte()); ?></p>
    </div>
    </div>
    <div class="container">
        <div>
            <table>
                <tr>
                    <td>Par : </td>
                    <td><?php echo($nomAuteur); ?></td>
                </tr>
                <tr>
                    <td>Crée le : </td>
                    <td><?php echo($enigme->getDateCrea()); ?></td>
                </tr>
                <tr>
                    <td>Dernière modification le :</td>
                    <td><?php echo($enigme->getDateModif()); ?></td>
                </tr>
                <tr>
                    <td>Nombre de tentatives :</td>
                    <td><?php echo(compterQuestionEnigme($enigme->getId())); ?></td>
                </tr>
                <tr>
                    <td>Nombre d'indices :</td>
                    <td><?php echo(compterIndiceEnigme($enigme->getId())); ?></td>
                </tr>
                <table>
<?php
                    foreach ($indices as $elem)
                    {
                        $imagePath="";
                        if(strlen($elem->getImage()) > 0)
                        {
                            $imagePath = $elem->getImage();
                        }
                        else
                        {
                            $imagePath = $conf['default_image_path'];
                        }
?>
                        <tr>
                            <td><img src="<?php echo($imagePath) ?>" alt="image indice" height="42" width="42" /></td>
                            <td><?php echo($elem->getTexte()); ?></td>
                            <td> Créé le : <?php echo($elem->getDateCrea()); ?></td>
                        </tr>
<?php
                    }                    
?>
                </table>
            </table>
        </div>
        <div>
            <form action="index.php?page=repondre_enigme&code=<?php echo($enigme->getId()); ?>" method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="question">Votre réponse ou suggestion : </label>
                    <input type="text" id="question" name="question" placeholder="Votre réponse" class="form-control" required />
                </div>
            <button type="submit" class="btn btn-default">Envoyer</button>
            </form>
        </div>
        <div>
            <table>
<?php
                foreach($questions as $elem)
                {
                    $reponse = getReponseQuestion($elem->getId());
                    $auteur = getAuteurQuestion($elem->getId());
?>
                <tr>
                    <td>Par : <?php echo($auteur->getNom()); ?></td>
                    <td>Le : <?php echo($elem->getDateCrea()); ?></td>
                    <td><?php echo($elem->getTexte()); ?></td>
<?php
                    if($reponse != NULL && $reponse->getId() != NULL)
                    {
                        $niveau = getReponseNiveau($reponse);
?>
                        <td><strong> Réponse : <?php echo($niveau->getLibelle()); ?></strong> <?php echo($reponse->getTexte()); ?></td>
<?php
                    }
                    else
                    {
?>
                        <td><p>Pas de réponse pour le moment</p></td>
<?php
                    }
?>
                </tr>
<?php
                }
?>
            </table>
        </div>
    </div>
<?php
}
else
{
    afficherAlertErreur("Une erreur est survenue.");
}