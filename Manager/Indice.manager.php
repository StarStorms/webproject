<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 20:36
 */

class Indicemanager
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

    public function getAllIndice() {
        $resultats = $this->db->query("SELECT * FROM indice");
        $resultats->execute();

        $tabIndices = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabIndices as $elem)
        {
            $indice = new Indice($elem);
            $tab[] = $indice;

        }

        return $tab;
    }



    public function getIndiceById($id)
    {
        $query = $this->db->prepare("SELECT * FROM indice WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabIndice = $query->fetch(PDO::FETCH_ASSOC)) {
            $indice = new Indice($tabIndice);
        } else {
            $indice = new Indice(array());
        }


        return $indice;
    }

    
    public function addIndice(Indice $indice)
    {
        $query = $this
            ->db
            ->prepare("INSERT INTO indice(enigme, texte, image, date_crea) VALUES (:enigme, :texte, :image, NOW())");

        $query->execute(array(
            ":enigme" => $indice->getEnigme(),
            ":texte" => $indice->getTexte(),
            ":image" => $indice->getImage()
                ));
        
        
    }
}
