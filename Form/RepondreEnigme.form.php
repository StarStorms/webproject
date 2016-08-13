<?php
if(verifString($_GET['code']))
{
    include "Library/Page/Enigmes.lib.php";
    include "Library/Page/Questions.lib.php";

    $enigme = getEnigmeById($_GET['code']);
    $nomAuteur = getNomAuteurFromId($enigme->getAuteur());
    $indices = getIndicesEnigme($enigme->getId());
    $questions = getQuestionsEnigme($enigme->getId());

?>

    <div class="container">
    <div class="jumbotron">
        <h1><?php echo($enigme->getId()); ?></h1>
        <p><?php echo($enigme->getTexte()); ?></p>
    </div>
    </div>
    <div class="container">
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
        </table>
    </div>
<?php
}
else
{
    afficherAlertErreur("Une erreur est survenue.");
}