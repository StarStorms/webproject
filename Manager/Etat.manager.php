<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 17:50
 */


class Etatmanager
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

    public function getEtatById($id)
    {
        $query = $this->db->prepare("SELECT * FROM etat WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabEtat = $query->fetch(PDO::FETCH_ASSOC)) {
            $etat = new Etat($tabEtat);
        } else {
            $etat = new Etat(array());
        }



        return $etat;
    }


}