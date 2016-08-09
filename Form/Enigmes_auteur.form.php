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
        <h1>Vos énigmes :</h1>
    </div>
</div>
<div class="container">
    
<?php
    include "Library/Page/Enigmes.lib.php";
    $enigmes = getAllEnigmesAuteur($_SESSION['id']);
    $conf = parse_ini_file("config.ini.php");
?>
    <div class="enigme_auteur">
        <table>
<?php   
    foreach ($enigmes as $elem)
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
            <td><img src="<?php echo($imagePath) ?>" alt="image enigme" with="42" height="42" /></td>
            <td><strong><?php echo($elem->getTitre()) ?></strong></td>
            <td><strong>Status : <?php echo(getStatusEnigme($elem->getId())) ?></strong></td>
            <td><p><?php echo($elem->getTexte()) ?></p></td>
            <td><p>Nombre d'indices : <?php echo(compterIndiceEnigme($elem->getId())) ?></p></td>
            <td><p>Nombre de questions : <?php echo(compterQuestionEnigme($elem->getId())) ?></p></td>
            <td><a href="index.php?page=rediger_indice&id= <?php echo($elem->getId()) ?>">Rediger un indice</a></td>
        </tr>                    
<?php
    }
?>
        
        </table>
    </div>
</div>
