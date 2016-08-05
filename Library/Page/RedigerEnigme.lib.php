<?php

function posterEnigme()
{
    if(isset($_POST['titre'])
        && isset($_POST['enigme'])
        && strlen($_POST['titre']) >= 4
        && strlen($_POST['enigme']) >= 4)
    {
        $conf = parse_ini_file("config.ini.php");
        $em = new Enigmemanager(connexionDb());
        $enigme = new Enigme(array());
        
        $enigme->setAuteur($_SESSION['id']);
        $enigme->setTitre($_POST['titre']);
        $enigme->setTexte($_POST['enigme']);
        
        if(isset($_FILES['picture']))
        {
            $fichier = basename($_FILES['picture']['name']);
            $dossier = $conf['path_enigmes'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier.$fichier);
            $enigme->setImage($fichier);
        }
        else
        {
            $enigme->setImage("");
        }
        $em->addEnigme($enigme);
        
        return true;
    }
    else
    {
        return false;
    }
}

?>
