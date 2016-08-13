<?php

function test()
{
    echo("<br />test ok");
}

function getAllEnigmesEnCours()
{
    $em = new Enigmemanager(connexionDb());
    $etm = new Etatmanager(connexionDb());
    
    $etat = $etm->getEtatById(2);
    $enigmes = $em->getEnigmesByEtat($etat);
    
    return $enigmes;
}

function getNomAuteurFromId($idAuteur)
{
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserById($idAuteur);
    
    return $user->getNom();
}


function posterIndice(Enigme $enigme)
{
    $conf = parse_ini_file("config.ini.php");

    if(isset($_POST['indice']))
    {
        $im = new Indicemanager(connexionDb());
        $indice = new Indice(array());
        $enigmeId = $enigme->getId();
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
        return true;
    }
    else
    {
        return false;
    }
}

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
        $enigme = $em->getEnigmeById($enigmeId);
        $etat = new Etat(array());
        $etat->setId(1);
        $em->addEtat($etat, $enigmeId);
        
        posterIndice($enigme);
        return true;
    }
    else
    {
        return false;
    }
}

function getAllEnigmesAuteur($auteurId)
{
    $conf = parse_ini_file("config.ini.php");
    $em = new Enigmemanager(connexionDb());
    $enigmes = $em->getEnigmesByAuteur($auteurId);
    
    return $enigmes;
}

function compterIndiceEnigme($enigmeId) 
{
    $conf = parse_ini_file("config.ini.php");
    $im = new Indicemanager(connexionDb());
    $indices = $im->getIndiceById($id);
    
    return count($indices);
}

function getIndicesEnigme($enigmeId)
{
    $conf = parse_ini_file("config.ini.php");
    $im = new Indicemanager(connexionDb());
        
    return $im->getIndiceByEnigmeId($enigmeId);
}

function compterQuestionEnigme($enigmeId)
{
    $conf = parse_ini_file("config.ini.php");
    $qm = new QuestionManager(connexionDb());
    
    $tmp = $qm->countQuestionsByEnigme($enigmeId);

    return $tmp['count(*)'];
}

function getStatusEnigme($enigmeId)
{
    $conf = parse_ini_file("config.ini.php");
    $em = new Enigmemanager(connexionDb());
    $etat = $em->getEtatEnigme($enigmeId);
        
    return $etat->getLibelle();
}

function verifEnigmeAuteur($enigmeId, $auteurId)
{
    $conf = parse_ini_file("config.ini.php");
    $em = new Enigmemanager(connexionDb());
    $enigme = $em->getEnigmeById($enigmeId);
    
    return $enigme->getAuteur() == $auteurId;
}

function getEnigmeById($enigmeId)
{
    $conf = parse_ini_file("config.ini.php");
    $em = new Enigmemanager(connexionDb());
    
    return $em->getEnigmeById($enigmeId);
}

function listerEtats(Etat $etatInitial)
{
    $conf = parse_ini_file("config.ini.php");
    $em = new Etatmanager(connexionDb());
    
    $etats = $em->getAllEtat();
    $tab = array();
    foreach($etats as $elem)
    {
        if(strcmp($etatInitial->getLibelle(), "Création") == 0)
        {
            if(strcmp($elem->getLibelle(), "En cours") == 0)
            {
                array_push($tab, $elem);
            }
        }
        else if(strcmp($etatInitial->getLibelle(), "En cours") == 0)
        {
            if(strcmp($elem->getLibelle(), "Masquée") == 0
                    || strcmp($elem->getLibelle(), "En pause") == 0
                    || strcmp($elem->getLibelle(), "Résolue") == 0
                    || strcmp($elem->getLibelle(), "Abandonnée") == 0)
            {
                array_push($tab, $elem);
            }
        }
        else if(strcmp($etatInitial->getLibelle(), "Masquée") == 0)
        {
            if(strcmp($elem->getLibelle(), "En cours") == 0
                    || strcmp($elem->getLibelle(), "En pause") == 0
                    || strcmp($elem->getLibelle(), "Résolue") == 0
                    || strcmp($elem->getLibelle(), "Abandonnée") == 0)
            {
                array_push($tab, $elem);
            }
        }
        else if(strcmp($etatInitial->getLibelle(), "En pause") == 0)
        {
            if(strcmp($elem->getLibelle(), "En cours") == 0)
            {
                array_push($tab, $elem);
            }
        }
        else if(strcmp($etatInitial->getLibelle(), "Résolue") == 0)
        {
            /* rien */
        }
        else if(strcmp($etatInitial->getLibelle(), "Abandonnée") == 0)
        {
            /* rien */
        }
        
    }
    
    return $tab;
}

function changerEtat(Enigme $enigme, $etatId)
{
    $conf = parse_ini_file("config.ini.php");
    $etm = new Etatmanager(connexionDb());
    $em = new Enigmemanager(connexionDb());
    
    $etatsPossibles = listerEtats($enigme->getEtat());
    $etat = $etm->getEtatById($etatId);

    if($etat != NULL && $etat->getId() == $etatId && in_array($etat, $etatsPossibles))
    {
        $em->updateEtatEnigme($enigme, $etat);
        return TRUE;
    }
    else 
    {
        return FALSE;
    }            
}
?>
