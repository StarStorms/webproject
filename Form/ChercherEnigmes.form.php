<div class="container">
    <div class="jumbotron">
        <h1>Les énigmes :</h1>
        <p>Cherchez des énigmes</p>
    </div>
</div>
<div class="container">

<?php
    include "Library/Page/Enigmes.lib.php";
    $enigmes = getAllEnigmesEnCours();
?>
    
    <table>
<?php
        foreach($enigmes as $elem)
        {
            $nomAuteur = getNomAuteurFromId($elem->getAuteur());
?>
        
        <tr>
            <td><strong><?php echo($elem->getTitre()); ?></strong></td>
            <td>Par : <?php echo($nomAuteur); ?></<td>
            <td>Le : <?php echo($elem->getDateCrea()); ?></td>
            <td>Nombre de tentatives : <?php echo(compterQuestionEnigme($elem->getId())); ?></td>
            <td><a href="index.php?page=repondre_enigme&code=<?php echo($elem->getId()); ?>">Répondre à l'énigme</a></td>
        </tr>
        
<?php
        }
?> 
    </table>
</div>