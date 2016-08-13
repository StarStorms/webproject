<?php

function getQuestionsEnigme($enigmeId)
{
    $qm = new QuestionManager(connexionDb());
    
    return $qm->getQuestionsByEnigme($enigmeId);
}

function getQuestionById($questionId)
{
    $qm = new QuestionManager(connexionDb());
    
    return $qm->getQuestionById($questionId);
}

function getAuteurQuestion($questionId)
{
    $conf = parse_ini_file("config.ini.php");
    $qm = new QuestionManager(connexionDb());
    $um = new UtilisateurManager(connexionDb());
    
    $question = $qm->getQuestionById($questionId);
    $auteur = $um->getUserById($question->getAuteur());
    
    return $auteur;
}

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

function getReponseNiveau(Reponse $reponse)
{
    $rm = new ReponseManager(connexionDb());
    return $rm->getReponseNiveau($reponse);
}

function addQuestion(Question $question)
{
    $qm = new QuestionManager(connexionDb());
    $qm->addQuestion($question);
}

function getAllNiveaux()
{
    $nm = new Niveaumanager(connexionDb());
    
    return $nm->getAllNiveaux();
}

function addReponse(Reponse $reponse)
{
    $rm = new ReponseManager(connexionDb());
    $rm->addReponse($reponse);
}

function getNiveauFromId($niveauId)
{
    $nm = new Niveaumanager(connexionDb());
    
    return $nm->getNiveauById($niveauId);
}

?>
