<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */
?>

<?php

/**
 * Retourne toutes les questions liees a une enigme
 * @param String $enigmeId L'id de la question
 * @return array
 */
function getQuestionsEnigme($enigmeId)
{
    $qm = new QuestionManager(connexionDb());
    
    return $qm->getQuestionsByEnigme($enigmeId);
}

/**
 * @param String $questionId
 * @return Question
 */
function getQuestionById($questionId)
{
    $qm = new QuestionManager(connexionDb());
    
    return $qm->getQuestionById($questionId);
}

/**
 * Retourne l'utilisateur qui a poste la question
 * @param String $questionId
 * @return Utilisateur
 */
function getAuteurQuestion($questionId)
{
    $conf = parse_ini_file("config.ini.php");
    $qm = new QuestionManager(connexionDb());
    $um = new UtilisateurManager(connexionDb());
    
    $question = $qm->getQuestionById($questionId);
    $auteur = $um->getUserById($question->getAuteur());
    
    return $auteur;
}

/**
 * Retourne le reponse (unique) liee a une qustion
 * @param String $questionId
 * @return Reponse
 */
function getReponseQuestion($questionId)
{
    $rm = new ReponseManager(connexionDb());
    $reponse = $rm->getReponseByQuestion($questionId);
    if($reponse != NULL && $reponse->getQuestion() == $questionId)
    {
        return $reponse;
    }
    else
    {
        return NULL;
    }
}

/**
 * Retourne le niveau (chaud, froid ...) de la reponse
 * @param Reponse $reponse
 * @return Niveau
 */
function getReponseNiveau(Reponse $reponse)
{
    $rm = new ReponseManager(connexionDb());
    return $rm->getReponseNiveau($reponse);
}

/**
 * Ajoute une question/suggestion dans la BDD
 * @param Question $question
 */
function addQuestion(Question $question)
{
    $qm = new QuestionManager(connexionDb());
    $qm->addQuestion($question);
}

/**
 * Retourne tous les niveaux (chaud, froid, ...) possible
 * @return array
 */
function getAllNiveaux()
{
    $nm = new Niveaumanager(connexionDb());
    
    return $nm->getAllNiveaux();
}

/**
 * Ajouter une reponse a une suggestion/question dans la BDD
 * @param Reponse $reponse
 */
function addReponse(Reponse $reponse)
{
    $rm = new ReponseManager(connexionDb());
    $rm->addReponse($reponse);
}

/**
 * Retourne le niveau (chaud, froid, ...) a partir d'un id
 * @param String $niveauId
 * @return Niveau
 */
function getNiveauFromId($niveauId)
{
    $nm = new Niveaumanager(connexionDb());
    
    return $nm->getNiveauById($niveauId);
}

function supprimerQuestion($idQuestion)
{
    $qm = new QuestionManager(connexionDb());
    $reponse = getReponseQuestion($idQuestion);
    
    if($reponse != NULL && $reponse->getNiveau() != NULL)
    {
        $rm = new ReponseManager(connexionDb());        
        $rm->deleteReponse($reponse);
    }
    
    $qm->deleteQuestionById($idQuestion);
}

?>