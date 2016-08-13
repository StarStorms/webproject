<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:18
 */

/**
 * Verifie que le couple pseudo-mdp est correct et initie la session utilisateur?
 * @param String $pseudo Pseudo de l'utilisateur deja dans la BDD
 * @param String $mdp Mot de passe de l'utilisateur
 */
function connectUser($pseudo, $mdp) 
{
    $um = new UtilisateurManager(connexionDb());
    
    if(verifString($pseudo) && verifString($mdp)) 
    {
        $pseudo = strtolower($pseudo);
                
        $result = $um->verifyMdp($pseudo, $mdp);
        if($result == TRUE)
        {
            $user = $um->getUserByUserName($pseudo);
            $grade = $um->getUserGrade($user);
            if($grade->getId() == 1 || 
               $grade->getId() == 2 || 
               $grade->getId()== 5)
            {
                setSessionUser($user);
                afficherAlertSucces("Vous êtes connecté(e) !");     
            }
            else
            {
                afficherAlertErreur("Votre compte en actuellement dans l'état : <strong>".$grade->getLibelle()."</strong>");
            }
        } 
        else 
        {
            $_SESSION['connected'] = false;
            afficherAlertErreur("Couple identifiant - mot de passe incorrect !");
        }
    }
}
