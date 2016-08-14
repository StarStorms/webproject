<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 16:22
 */

class ReponseManager
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

    public function getAllReponses() {
        $resultats = $this->db->query("SELECT * FROM reponse");
        $resultats->execute();

        $tabReponses = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabReponses as $elem)
        {
            $reponse = new Reponse($elem);
            $reponse->setNiveau($this->getReponseNiveau($reponse));
            $tab[] = $reponse;
        }
        return $tab;
    }

    public function getReponseById($id)
    {
        $query = $this->db->prepare("SELECT * FROM reponse WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabReponse = $query->fetch(PDO::FETCH_ASSOC)) {
            $reponse = new Reponse($tabReponse);
            $reponse->setNiveau($this->getReponseNiveau($reponse));
        } else {
            $reponse = new Reponse(array());
        }
        return $reponse;
    }

    /**
     * Fonction permettant de retrouver une réponse en fonction de sa question.
     * @param $question : l'id de la question.
     * @return $reponse : la réponse trouvée.
     */
    public function getReponseByQuestion($question)
    {
        $query = $this->db->prepare("SELECT * FROM reponse WHERE question = :question");
        $query->execute(array(
            ":question" => $question
        ));

        $tabReponse = $query->fetch(PDO::FETCH_ASSOC);
        if($tabReponse != NULL)
        {
            $reponse = new Reponse($tabReponse);
            $reponse->setNiveau($this->getReponseNiveau($reponse));
        }
        else
        {
            $reponse = new Reponse(array());
        }
        return $reponse;
    }

    public function getReponseNiveau(Reponse $reponse)
    {
        $nm = new Niveaumanager(connexionDb());
        $query = $this->db->prepare("SELECT * FROM niveau_reponse WHERE id_reponse = :id");
        $query->execute(array(
            ":id" => $reponse->getId()
        ));

        $tabNiveau = $query->fetchAll(PDO::FETCH_ASSOC);

        $niveauReponse = new Niveau(array());
        foreach ($tabNiveau as $elem) {
            $niveauReponse = $nm->getNiveauById($elem['id_niveau']);
        }
        return $niveauReponse;
    }
    
    public function addReponse(Reponse $reponse)
    {
        $query = $this
            ->db
            ->prepare("INSERT INTO reponse(question, texte, date_crea, date_modif) VALUES (:question, :texte, NOW(), NOW())");

        $query->execute(array(
            ":question" => $reponse->getQuestion(),
            ":texte" =>$reponse->getTexte()
        ));
        
        $query2 = $this->db->prepare("SELECT id FROM reponse WHERE question = :question AND texte = :texte");
        $query2->execute(array(
            ":question" => $reponse->getQuestion(),
            ":texte" => $reponse->getTexte()
        ));
        
        $ids = $query2->fetchAll(PDO::FETCH_ASSOC);
        $id = $ids[0]['id'];
        $niveau = $reponse->getNiveau();
        
        $query3 = $this->db->prepare("INSERT INTO niveau_reponse(id_reponse, id_niveau) VALUES (:id_reponse, :id_niveau)");
        $query3->execute(array(
            ":id_reponse" => $id,
            ":id_niveau" => $niveau->getId()
        ));
    }
    
    public function deleteReponse(Reponse $reponse)
    {
        $niveau = $reponse->getNiveau();
        
        $query = $this->db->prepare("DELETE FROM niveau_reponse WHERE id_reponse = :id_reponse AND id_niveau = :id_niveau");
        $query->execute(array(
            ":id_reponse" => $reponse->getId(),
            ":id_niveau" => $niveau->getId()
        ));
        
        $query2 = $this->db->prepare("DELETE FROM reponse WHERE id = :id");
        $query2->execute(array(
            ":id" => $reponse->getId()
        ));
    }
}