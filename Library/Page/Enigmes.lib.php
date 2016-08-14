<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */
?>


<?php

/**
 * Retourne toutes les énigmes dans l'état "En cours"
 * @return array
 */
function getAllEnigmesEnCours()
{
    $em = new Enigmemanager(connexionDb());
    $etm = new Etatmanager(connexionDb());
    
    $etat = $etm->getEtatById(2);
    $enigmes = $em->getEnigmesByEtat($etat);
    
    return $enigmes;
}

/**
 * Retourne le nom de l'utilisateur a partir de son id
 * @param String $idAuteur
 * @return String
 */
function getNomAuteurFromId($idAuteur)
{
    $um = new UtilisateurManager(connexionDb());
    $user = $um->getUserById($idAuteur);
    
    return $user->getNom();
}

/**
 * Ajoute un indice a une enigme dans la BDD
 * @param Enigme $enigme
 * @return boolean TRUE si l'ajout a reussi, FALSE sinon
 */
function posterIndice(Enigme $enigme, $indiceTexte, $indicePic)
{
    $conf = parse_ini_file("config.ini.php");

    if(verifString($indiceTexte))
    {
        $im = new Indicemanager(connexionDb());
        $indice = new Indice(array());
        $enigmeId = $enigme->getId();
        $indice->setEnigme($enigmeId);
        $indice->setTexte($indiceTexte);

        if($indicePic != NULL && !empty($indicePic))
        {
            $fichier_indice = basename($indicePic['name']);
            $dossier_indice = $conf['path_indices'];
            move_uploaded_file($indicePic['tmp_name'], $dossier_indice.$fichier_indice);
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

/**
 * Ajoute une enigme et un indice dans la BDD
 * @param String $titre Titre de l'enigme
 * @param String $texte Texte de l'enigme
 * @param String $idAuteur Id de l'utilisateur
 * @param String $indiceTexte Texte de l'enigme
 * @param array $enigmePic Variable $_FILES pour l'image de l'enigme
 * @param array $indicePic Variable $_FILES pour l'image de l'indice
 * @return boolean TRUE si l'ajout a reussi, FALSE sinon
 */
function posterEnigme($titre, $texte, $idAuteur, $reponse, $indiceTexte, $enigmePic, $indicePic)
{
    if(verifString($titre)
        && verifString($texte)
        && strlen($titre) >= 4
        && strlen($texte) >= 4)
    {
        $conf = parse_ini_file("config.ini.php");
        $em = new Enigmemanager(connexionDb());
        $enigme = new Enigme(array());
        
        $enigme->setAuteur($idAuteur);
        $enigme->setTitre($titre);
        $enigme->setTexte($texte);
        $enigme->setReponse($reponse);
        
        if($enigmePic != NULL && !empty($enigmePic))
        {
            $fichier = basename($enigmePic['name']);
            $dossier = $conf['path_enigmes'];
            move_uploaded_file($enigmePic['tmp_name'], $dossier.$fichier);
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
        
        posterIndice($enigme, $indiceTexte, $indicePic);
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Retourne toutes les enigmes d'un utilisateur
 * @param String $auteurId
 * @return array
 */
function getAllEnigmesAuteur($auteurId)
{
    $em = new Enigmemanager(connexionDb());
    $enigmes = $em->getEnigmesByAuteur($auteurId);
    
    return $enigmes;
}

/**
 * Compte tous les indices liees a une enigme
 * @param String $enigmeId
 * @return int
 */
function compterIndiceEnigme($enigmeId) 
{
    $im = new Indicemanager(connexionDb());
    $indices = $im->getIndiceById($id);
    
    return count($indices);
}

/**
 * Retourne tous les indices lies a une enigme
 * @param String $enigmeId
 * @return array
 */
function getIndicesEnigme($enigmeId)
{
    $im = new Indicemanager(connexionDb());
        
    return $im->getIndiceByEnigmeId($enigmeId);
}

/**
 * Compte les questions/suggestions liee a une enigme
 * @param String $enigmeId
 * @return array
 */
function compterQuestionEnigme($enigmeId)
{
    $qm = new QuestionManager(connexionDb());
    $tmp = $qm->countQuestionsByEnigme($enigmeId);

    return $tmp['count(*)'];
}

/**
 * Retourne le libelle de l'etat d'une enigme
 * @param String $enigmeId
 * @return String
 */
function getStatusEnigme($enigmeId)
{
    $em = new Enigmemanager(connexionDb());
    $etat = $em->getEtatEnigme($enigmeId);
        
    return $etat->getLibelle();
}

/**
 * Verifie qu'un utilisateur est auteur de l'enigme
 * @param String $enigmeId
 * @param String $auteurId
 * @return boolean TRUE si l'utilisateur est auteur de l'enigme, FALSE sinon
 */
function verifEnigmeAuteur($enigmeId, $auteurId)
{
    $em = new Enigmemanager(connexionDb());
    $enigme = $em->getEnigmeById($enigmeId);
    
    return $enigme->getAuteur() == $auteurId;
}

/**
 * @param String $enigmeId L'identifiant de l'énigme
 * @return Enigme
 */
function getEnigmeById($enigmeId)
{
    $em = new Enigmemanager(connexionDb());
    
    return $em->getEnigmeById($enigmeId);
}

/**
 * Retourne la liste des etats possibles d'une enigmes en fonction de son etat actuel
 * @param Etat $etatInitial Etat actuel de l'enigme
 * @return array
 */
function listerEtats(Etat $etatInitial)
{
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

/**
 * Change l'etat d'une enigme dans la BDD
 * @param Enigme $enigme
 * @param String $etatId
 * @return boolean
 */
function changerEtat(Enigme $enigme, $etatId)
{
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
