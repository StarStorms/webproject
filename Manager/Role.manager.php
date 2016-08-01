<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 17:37
 */

class Rolemanager
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


    public function getAllRoles() {
        $resultats = $this->db->query("SELECT * FROM role");
        $resultats->execute();

        $tabRoles = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabRoles as $elem)
        {
            $role = new Role($elem);
            $tab[] = $role;

        }

        return $tab;
    }


    public function getRoleById($id)
    {
        $query = $this->db->prepare("SELECT * FROM role WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabRole = $query->fetch(PDO::FETCH_ASSOC)) {
            $role = new Role($tabRole);
        } else {
            $role = new Role(array());
        }



        return $role;
    }


}