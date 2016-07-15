<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 10:13
 */

class Reponse
{
    private $id;
    private $question;
    private $texte;
    private $date_crea;
    private $date_modif;
    private $niveau = array();

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
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * @param mixed $texte
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    }

    /**
     * @return mixed
     */
    public function getDateCrea()
    {
        return $this->date_crea;
    }

    /**
     * @param mixed $date_crea
     */
    public function setDateCrea($date_crea)
    {
        $this->date_crea = $date_crea;
    }

    /**
     * @return mixed
     */
    public function getDateModif()
    {
        return $this->date_modif;
    }

    /**
     * @param mixed $date_modif
     */
    public function setDateModif($date_modif)
    {
        $this->date_modif = $date_modif;
    }

    /**
     * @return array
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param array $niveau
     */
    public function setNiveau(Niveau $niveau)
    {
        $this->niveau = $niveau;
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
}