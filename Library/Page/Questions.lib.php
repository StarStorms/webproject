<?php

function getQuestionsEnigme($enigmeId)
{
    $conf = parse_ini_file("config.ini.php");
    $qm = new QuestionManager(connexionDb());
    
    return $qm->getQuestionsByEnigme($enigmeId);
}

function getQuestionById($questionId)
{
    $conf = parse_ini_file("config.ini.php");
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
    
    return $auteur->getNom();
}

function getReponseQuestion($questionId)
{
    $conf = parse_ini_file("config.ini.php");
    $rm = new ReponseManager(connexionDb());
    $reponse = $rm->getReponseById($questionId);
    
    if($reponse != NULL && $reponse->getQuestion() == $questionId)
    {
        return $reponse->getTexte();
    }
    else
    {
        return NULL;
    }
}

?>
