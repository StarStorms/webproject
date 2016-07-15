<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 10:13
 */

class Utilisateur
{
    private $id;
    private $nom;
    private $email;
    private $date_connexion;
    private $date_inscription;
    private $mdp;
    private $grade = array();
    private $reponse_secrete;
    private $question_secrete;
    private $role = array();

    /**
     * @return mixed
     */
    public function getReponseSecrete()
    {
        return $this->reponse_secrete;
    }

    /**
     * @param mixed $reponse_secrete
     */
    public function setReponseSecrete($reponse_secrete)
    {
        $this->reponse_secrete = $reponse_secrete;
    }

    /**
     * @return mixed
     */
    public function getQuestionSecrete()
    {
        return $this->question_secrete;
    }

    /**
     * @param mixed $question_secrete
     */
    public function setQuestionSecrete($question_secrete)
    {
        $this->question_secrete = $question_secrete;
    }

    /**
     * Fonction permettant l'hydratation de la classe.
     * @param array $tab est un tableau associatif selon les attributs a assigner.
     */
    private function __hydrate(array $tab)
    {
        foreach ($tab as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }

    public function __construct(array $user)
    {
        $this->__hydrate($user);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDateConnexion()
    {
        return $this->date_connexion;
    }

    /**
     * @param mixed $date_connexion
     */
    public function setDateConnexion($date_connexion)
    {
        $this->date_connexion = $date_connexion;
    }

    /**
     * @return mixed
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * @param mixed $date_inscription
     */
    public function setDateInscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return array
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param array $grade
     */
    public function setGrade(Grade $grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param array $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }




}