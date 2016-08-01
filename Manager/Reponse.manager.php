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

        if($tabReponse = $query->fetch(PDO::FETCH_ASSOC))
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

    /**
     * Fonction permettant d'ajouter un utilisateur � la BDD.
     * @param Utilisateur $user : l'utilisateur � ajouter.
     */
    public function addUser(Utilisateur $user)
    {
        $query = $this
            ->db
            ->prepare("INSERT INTO utilisateur(nom, email, mdp, date_inscription, date_connexion) VALUES (:username, :email, :mdp,  NOW(),NOW())");

        $query->execute(array(
            ":username" => $user->getNom(),
            ":email" => $user->getEmail(),
            ":mdp" => hash("sha256", $this->getMdp())
        ));
    }

    public function updateUserProfil(Utilisateur $user)
    {

        $query = $this
            ->db
            ->prepare("UPDATE utilisateur SET nom = :username, email = :email, question_secrete = :question, reponse_secrete = :reponse");

        $query
            ->execute(array(
                ":username" => $user->getNom(),
                ":mail" => $user->getEmail(),
                ":question" => $user->getQuestionSecrete(),
                ":reponse" => $user->getReponseSecrete()
            ));
        //$this->updateUserDroit($user->getId(), $user->getDroit()->getId());


    }

    public function updateUserConnect(Utilisateur $user)
    {
        $query = $this
            ->db
            ->prepare("UPDATE utilisateur SET date_connexion = NOW() WHERE id = :id");

        $query
            ->execute(array(
                ":id" => $user->getId()
            ));

    }

    public function addQuestionReponseSecrete($question, $reponse, Utilisateur $user)
    {
        $query = $this
            ->db
            ->prepare("UPDATE utilisateur SET question_secrete = :question, reponse_secrete = :reponse WHERE id = :id");

        $query
            ->execute(array(
                ":id" => $user->getId(),
                "question" => $question,
                "reponse" => hash("sha256",$reponse)
            ));
    }

    public function updateUserMdp (Utilisateur $user) {

        $query = $this
            -> db
            ->prepare("UPDATE utilisateur SET mdp = :mdp where id = :id");
        $query
            ->execute(array(
                ":id" => $user->getId(),
                ":mdp" => hash("sha256",$user->getMdp())
            ));
    }

}