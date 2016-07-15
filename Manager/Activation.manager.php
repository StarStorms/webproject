<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 16:22
 */

class ActivationManager
{
    private $db;

    /**
     * Fonction générant un manager en fonction de la BDD.
     * @param PDO $database : la base de données.
     */
    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    public function getAllActivations() {
        $resultats = $this->db->query("SELECT * FROM activation");
        $resultats->execute();
        $tabActivations = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabActivations as $elem)
        {
            $activation = new Activation($elem);
            $tab[] = $activation;
        }
        return $tab;
    }

    public function getActivationByUser($id)
    {
        $query = $this->db->prepare("SELECT * FROM activation WHERE id_utilisateur = :id");
        $query->execute(array(
            ":id" => $id
        ));
        $tabActivations = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabActivations as $elem)
        {
            $activation = new Activation($elem);
            $tab[] = $activation;
        }
        return $tab;
    }

    public function getActivationByCode($code)
    {
        $query = $this->db->prepare("SELECT * FROM activation WHERE code = :code");
        $query->execute(array(
            ":code" => $code
        ));

        if($tabActivation = $query->fetch(PDO::FETCH_ASSOC))
        {
            $activation = new Activation($tabActivation);
        }
        else
        {
            $activation = new Activation(array());
        }
        return $activation;
    }

    public function getActivationByUserLibelle($id, $libelle)
    {
        $query = $this->db->prepare("SELECT * FROM activation WHERE id_utilisateur = :id AND libelle = :libelle");
        $query->execute(array(
            ":id" => $id,
            ":libelle" => $libelle
        ));

        if($tabActivation = $query->fetch(PDO::FETCH_ASSOC))
        {
            $activation = new Activation($tabActivation);
        }
        else
        {
            $activation = new Activation(array());
        }
        return $activation;
    }

    /**
     * Fonction permettant d'ajouter une question à la BDD.
     * @param Question $question : la question à ajouter.
     */
    public function addActivation(Activation $activation)
    {
        $query = $this
            ->db
            ->prepare("INSERT INTO activation(id_utilisateur, libelle, code) VALUES (:id, :libelle, :code)");

        $query->execute(array(
            ":id" => $activation->getIdUtilisateur(),
            ":libelle" => $activation->getLibelle(),
            ":code" => $activation->getCode()
        ));
    }

    public function deleteActivation($id, $libelle)
    {

        $query = $this
            ->db
            ->prepare("DELETE FROM activation WHERE id_utilisateur = :id AND libelle = :libelle");

        $query
            ->execute(array(
                ":id" => $id,
                ":libelle" => $libelle
            ));
    }
}