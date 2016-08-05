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
            move_uploaded_file($_FILES['picture']['tmp_name'], $dossier.$fichier);
            $enigme->setImage($fichier);
        }
        else
        {
            $enigme->setImage("");
        }
        
        $enigmeId = $em->addEnigme($enigme);        
        if(isset($_POST['indice']))
        {
            $im = new Indicemanager(connexionDb());
            $indice = new Indice(array());
            $indice->setEnigme($enigmeId);
            $indice->setTexte($_POST['indice']);
            
            if(isset($_POST['indice_picture']))
            {
                $fichier_indice = basename($_FILES['indice_picture']['name']);
                $dossier_indice = $conf['path_indices'];
                move_uploaded_file($_FILES['indice_picture']['tmp_name'], $dossier_indice.$fichier_indice);
                $indice->setImage($fichier_indice);
            }
            else
            {
                $indice->setImage("");
            }
            
            $im->addIndice($indice);
        }
        
        
        return true;
    }
    else
    {
        return false;
    }
}

?>
