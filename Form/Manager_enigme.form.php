<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

 
<?php
    include "Library/Page/Enigmes.lib.php";
    include "Library/Page/Questions.lib.php";
    $conf = parse_ini_file("config.ini.php");
    if(isConnect())
    {
        if(isset($_GET['id'])
                && verifString($_GET['id'])
                && verifEnigmeAuteur($_GET['id'], $_SESSION['id']) == TRUE)
        {
            $enigme = getEnigmeById($_GET['id']);
            if($enigme != NULL && $enigme->getId() == $_GET['id']) {
?>
                <div class="container">
                     <div class="jumbotron">
                         <h1>Votre énigme : <?php echo($enigme->getTitre()); ?></h1>
                         <br /><p><?php echo($enigme->getTexte()); ?></p>
                    </div>
                </div>
<?php
                if(isset($_GET['action']) && $_GET['action'] == "suppr_question")
                {
                    if(isset($_GET['id_q']))
                    {
                        $question = getQuestionById($_GET['id_q']);
                        if($question != NULL && $question->getEnigme() == $enigme->getId())
                        {
                            supprimerQuestion($question->getId());
                            afficherAlertSucces("La question a été supprimée !");
                        }
                        else
                        {
                            afficherAlertErreur("Une erreur est survenue.");
                        }
                    }
                    else 
                    {
                        afficherAlertErreur("Une erreur est survenue.");
                    }
                }
                else if(isset($_GET['action']) && $_GET['action'] == "ch_etat")
                {
                    if(changerEtat($enigme, $_POST['menu_etats']) == TRUE)
                    {
                        afficherAlertSucces("Le status de l'énigme a bien été changé");
                    }
                    else
                    {
                        afficherAlertErreur("Une erreur est survenue");
                    }
                }
                else if(isset($_GET['action']) && $_GET['action'] == "poster_indice")
                {
                    if(posterIndice($enigme) == true)
                    {
                        afficherAlertSucces("L'indice a bien été posté");
                    }
                    else
                    {
                        afficherAlertErreur("Une erreur est survenue");
                    }
                }

                    

?>

                <div class="container">
                    <table>
                        <tr>
                            <td>Etat de l'énigme :</td>
                            <td><?php echo(getStatusEnigme($enigme->getId())); ?></td>
                        </tr>
                        <tr>
                            <td><p>Passer en état : </p></td>
                            <td>
                                <form method="post" action="index.php?page=manager_enigme&action=ch_etat&id=<?php echo($enigme->getId()); ?>">
                                <select name="menu_etats">
<?php
                                $etats = listerEtats($enigme->getEtat());
                                foreach($etats as $elem)
                                {
?>
                                    <option value="<?php echo($elem->getId()); ?>"><?php echo($elem->getLibelle()); ?></option>
<?php
                                }
?>
                                 </select>
                                 <input type="submit" value="ok" title="OK" />
                                 </form>
                            </td>
                        </tr>
                        <tr>
                        <form method="post" action="index.php?page=manager_enigme&action=poster_indice&id=<?php echo($enigme->getId()); ?>">                            
                        <div class="form-group">
                           <label for="indice">Poster un nouvel indice</label>
                           <input type="textarea" id="indice" name="indice" required class="form-control" required />
                       </div>
                        <div class="form-group">
                           <label for="indice_picture">Image de l'indice : (falcutatif) </label>
                           <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                           <input type="file" name="indice_picture" />
                       </div>
                        <button type="submit" class="btn btn-default">Poster l'indice</button>
                        </form>
                    </tr>
                    <tr>
                        <table>
                            <tr><td>Indices :</td></tr>
<?php
                            $indices = getIndicesEnigme($enigme->getId());
                            foreach ($indices as $elem)
                            {
                                $imagePath="";
                                if(verifString($elem->getImage()))
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
                        </tr>
                        
                        <table>
                            <tr>
                                <td>Questions :</td>
                                <td><?php echo(compterQuestionEnigme($enigme->getId())); ?></td>
                            </tr>
                            
<?php
                            $questions = getQuestionsEnigme($enigme->getId());
                            foreach ($questions as $elem)
                            {
                                $auteur = getAuteurQuestion($elem->getId());
?>
                                <tr>
                                    <td><?php echo($elem->getTexte()); ?></td>
                                    <td>Par : <?php echo($auteur->getNom()); ?></td>
                                    <td>Le : <?php echo($elem->getDateCrea()); ?></td>
<?php
                                    $reponse = getReponseQuestion($elem->getId());
                                    if($reponse != NULL && strlen($reponse) > 0)
                                    {
                                        $niveau = getReponseNiveau($reponse);
?>
                                    <td><strong> Réponse : <?php echo($niveau->getLibelle()); ?></strong><p><?php echo($reponse->getTexte()); ?></p></td>
<?php
                                    }
                                    else
                                    {
?>
                                    <td><a href="index.php?page=repondre_question&code=<?php echo($elem->getId()); ?>">Rédiger une réponse</a></td>
<?php
                                    }
?>
                                    <td><a href="index.php?page=manager_enigme&action=suppr_question&id_q=<?php echo($elem->getId()); ?>&id=<?php echo($enigme->getId()); ?>">Supprimer la question</a</td>
                                </tr>
<?php
                            }
?>
                        
                        </table>
                    </table>
                </div>

<?php
            }
        }
    }
?>
