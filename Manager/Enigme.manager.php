<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 17:50
 */


class Enigmemanager
{
    private $db;

    /**
     * Fonction g�n�rant un manager en fonction de la BDD.
     * @param PDO $database : la base de donn�es.
     */
    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    public function getAllEnigmes() {
        $resultats = $this->db->query("SELECT * FROM enigme");
        $resultats->execute();

        $tabEnigmes = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabEnigmes as $elem)
        {
            $enigmes = new Enigme($elem);
            $enigmes->setEtat($this->getEtatEnigme($enigmes->getId()));
            $tab[] = $enigmes;

        }

        return $tab;
    }



    public function getEnigmeById($id)
    {
        $query = $this->db->prepare("SELECT * FROM enigme WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabEnigme = $query->fetch(PDO::FETCH_ASSOC)) {
            $enigme = new Enigme($tabEnigme);
            $enigme->setEtat($this->getEtatEnigme($enigme->getId()));
        } else {
            $enigme = new Enigme(array());
        }



        return $enigme;
    }

    public function getEnigmesByAuteur($auteur)
    {
        $query = $this->db->prepare("SELECT * FROM enigme WHERE auteur = :auteur");
        $query->execute(array(
            ":auteur" => $auteur
        ));
        
        $tabEnigmes = $query->fetchAll(PDO::FETCH_ASSOC);
        $tab = array();

        foreach($tabEnigmes as $elem)
        {
            $enigmes = new Enigme($elem);
            $enigmes->setEtat($this->getEtatEnigme($enigmes->getId()));
            $tab[] = $enigmes;

        }

        return $tab;
    }

    public function getEnigmeByDateCrea($date_crea)
    {
        $query = $this->db->query("SELECT * FROM enigme ORDER BY date_crea DESC");
        $query->execute();

        if($tabEnigme = $query->fetch(PDO::FETCH_ASSOC)){
            $enigme = new Enigme($tabEnigme);
            $enigme->setEtat($this->getEtatEnigme($enigme->getId()));
        } else{
            $enigme = new Enigme(array());
        }

        return $enigme;
    }

    public function getEtatEnigme($id) {
        $em = new Etatmanager(connexionDb());
        $query = $this->db->prepare("SELECT * FROM etat_enigme WHERE id_enigme = :id");
        $query->execute(array(
            ":id" => $id
        ));

        $tabEtat = $query->fetchAll(PDO::FETCH_ASSOC);

        $etatEnigme = new Etat(array());
        foreach ($tabEtat as $elem) {
            $etatEnigme = $em->getEtatById($elem['id_etat']);
        }
        return $etatEnigme;
    }

    public function getEnigmeByDateModif($date_modif)
    {
        $query = $this->db->query("SELECT * FROM enigme ORDER BY date_modif DESC");
        $query->execute();

        if($tabEnigme = $query->fetch(PDO::FETCH_ASSOC)){
            $enigme = new Enigme($tabEnigme);
            $enigme->setEtat($this->getEtatEnigme($enigme->getId()));
        } else{
            $enigme = new Enigme(array());
        }

        return $enigme;
    }
    
    public function updateEtatEnigme(Enigme $enigme, Etat $etat)
    {
        $query = $this->db->prepare("UPDATE etat_enigme SET id_etat = :id_etat WHERE id_enigme = :id_enigme");
        $query->execute(array(
           ":id_etat" => $etat->getId(),
            "id_enigme" => $enigme->getId()
        ));
    }
    
    public function  addEnigme(Enigme $enigme)
    {
        $query = $this
        ->db
        ->prepare("INSERT INTO enigme(auteur, titre, texte, image, date_crea) VALUES (:auteur, :titre, :texte, :image, NOW())");

    $query->execute(array(
        ":auteur" => $enigme->getAuteur(),
        ":titre" => $enigme->getTitre(),
        ":texte" => $enigme->getTexte(),
        ":image" => $enigme->getImage()
            ));
        
    
        /* Retourner l'id de l'enigme cree */
        $query2 = $this
                ->db
                ->prepare("SELECT * FROM enigme WHERE auteur = :auteur AND titre = :titre AND texte = :texte AND image = :image");
        
        $query2->execute(array(
        ":auteur" => $enigme->getAuteur(),
        ":titre" => $enigme->getTitre(),
        ":texte" => $enigme->getTexte(),
        ":image" => $enigme->getImage()
            ));

         if($tabEnigme2 = $query2->fetch(PDO::FETCH_ASSOC)){
            $enigmeId = new Enigme($tabEnigme2);
            $enigmeId->setEtat($this->getEtatEnigme($enigmeId->getId()));
        } else {
            $enigmeId = new Enigme(array());
        }
        
        return $enigmeId->getId();
    }
    
    public function addEtat(Etat $etat, $enigmeId)
    {
        $query = $this
        ->db
        ->prepare("INSERT INTO etat_enigme(date_debut, id_etat, id_enigme) VALUES (NOW(), :id_etat, :id_enigme)");

        $query->execute(array(
        ":id_etat" => $etat->getId(),
        ":id_enigme" => $enigmeId
            ));
    }
    
    public function getEnigmesByEtat(Etat $etat)
    {
        $resultat = $this->db->prepare("SELECT id_enigme FROM etat_enigme WHERE id_etat = :id_etat");
        $resultat->execute(array(
           ":id_etat" => $etat->getId()
        ));
        
        $ids = $resultat->fetch(PDO::FETCH_ASSOC);  
        $tab = array();
        foreach ($ids as $elem)
        {
            $enigme = getEnigmeById($elem);
            if($enigme->getId() == $elem)
            {
                array_push($tab, $enigme);
            }
        }
        return $tab;
    }
}