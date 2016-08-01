<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */

function setQR() {
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    
    if(isset($_SESSION['connected']) && isset($_SESSION['id']) && $_SESSION['connected'] == true) {
        $user=$um->getUserById($_SESSION['id']);
        
        if(isset($_POST['question']) 
           && strlen($_POST['question']) > 3
           && isset($_POST['reponse'])
           && strlen($_POST['reponse']) >3 )
        {
            $um->addQuestionReponseSecrete($_POST['question'], $_POST['reponse'], $user);
?>
            <div class="alert alert-success">
                <strong>Succès</strong> Vous avez bien renseigné votre question secrète.
            </div>
<?php
        }
        else
        {
?>
            <div class="alert alert-danger">
                <strong>Erreur!</strong> Veuillez renseigner une question et une réponse de plus de 3 caractères. 
            </div>
<?php
        }
    }
}

function requestQR() {
    $conf = parse_ini_file("config.ini.php");
    $um = new UtilisateurManager(connexionDb());
    
    if(isset($_SESSION['connected']) && isset($_SESSION['id']) && $_SESSION['connected'] == true) {
        $user=$um->getUserById($_SESSION['id']);
        if($user->getQuestionSecrete() == NULL
           || $user->getReponseSecrete() == NULL)
        {
?>
            <li><a href="index.php?page=QR">Renseignez une question secrète !</a></li>
<?php

        }
    }
}