<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 16:22
 */

class QuestionManager
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

    public function getAllQuestions() {
        $resultats = $this->db->query("SELECT * FROM question");
        $resultats->execute();

        $tabQuestions = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabQuestions as $elem)
        {
            $question = new Question($elem);
            $tab[] = $question;

        }

        return $tab;
    }

    public function getQuestionById($id)
    {
        $query = $this->db->prepare("SELECT * FROM question WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabQuestion = $query->fetch(PDO::FETCH_ASSOC)) {
            $question = new Question($tabQuestion);
        } else {
            $question = new Question(array());
        }

        return $question;
    }

    public function getQuestionsByUser($id)
    {
        $query = $this->db->prepare("SELECT * FROM question WHERE auteur = :id");
        $query->execute(array(
            ":id" => $id
        ));
        $tabQuestions = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabQuestions as $elem)
        {
            $question = new Question($elem);
            $tab[] = $question;
        }

        return $tab;
    }

    public function getQuestionsByEnigme($id)
    {
        $query = $this->db->prepare("SELECT * FROM question WHERE enigme = :id");
        $query->execute(array(
            ":id" => $id
        ));
        $tabQuestions = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabQuestions as $elem)
        {
            $question = new Question($elem);
            $tab[] = $question;
        }

        return $tab;
    }
    
    public function countQuestionsByEnigme($id) {
        $query = $this->db->prepare("SELECT count(*) FROM question WHERE enigme = :id");
        $query->execute(array(
            ":id" => $id
        ));
        $count = $query->fetch(PDO::FETCH_ASSOC);
        return $count;
    }

    /**
     * Fonction permettant d'ajouter une question à la BDD.
     * @param Question $question : la question à ajouter.
     */
    public function addQuestion(Question $question)
    {
        $query = $this
            ->db
            ->prepare("INSERT INTO question(auteur, enigme, texte, date_crea, date_modif) VALUES (:auteur, :enigme, :texte, NOW(), NOW())");

        $query->execute(array(
            ":auteur" => $question->getAuteur(),
            ":enigme" => $question->getEnigme(),
            ":texte" => $question->getTexte()
        ));
    }

    public function updateQuestion(Question $question)
    {

        $query = $this
            ->db
            ->prepare("UPDATE question SET auteur = :auteur, enigme = :enigme, texte = :texte, date_modif = NOW()");

        $query
            ->execute(array(
                ":auteur" => $question->getAuteur(),
                ":enigme" => $question->getEnigme(),
                ":texte" => $question->getTexte()
            ));
    }
    
    public function deleteQuestionById($id)
    {
        $query = $this->db->prepare("DELETE FROM question WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));
    }
}