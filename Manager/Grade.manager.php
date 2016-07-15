<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 17:47
 */

class Grademanager
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

    public function getGradeById($id)
    {
        $query = $this->db->prepare("SELECT * FROM grade WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabGrade = $query->fetch(PDO::FETCH_ASSOC)) {
            $grade = new Grade($tabGrade);
        } else {
            $grade = new Grade(array());
        }



        return $grade;
    }


}