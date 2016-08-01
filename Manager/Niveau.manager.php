<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 17:52
 */

class Niveaumanager
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


    public function getAllNiveaux() {
        $resultats = $this->db->query("SELECT * FROM niveau");
        $resultats->execute();

        $tabNiveaux = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabNiveaux as $elem)
        {
            $niveau = new Niveau($elem);
            $tab[] = $niveau;

        }

        return $tab;
    }

    public function getNiveauById($id)
    {
        $query = $this->db->prepare("SELECT * FROM niveau WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabNiveau = $query->fetch(PDO::FETCH_ASSOC)) {
            $niveau = new Niveau($tabNiveau);
        } else {
            $niveau = new Niveau(array());
        }



        return $niveau;
    }


}