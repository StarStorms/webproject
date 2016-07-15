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

    public function getEnigmeByAuteur($auteur)
    {
        $query = $this->db->prepare("SELECT * FROM enigme WHERE auteur = :auteur");
        $query->execute(array(
            ":auteur" => $auteur
        ));

        if ($tabEnigme = $query->fetch(PDO::FETCH_ASSOC)) {
            $enigme = new Enigme($tabEnigme);
            $enigme->setEtat($this->getEtatEnigme($enigme->getId()));
        } else {
            $enigme = new Enigme(array());
        }

        return $enigme;
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






}