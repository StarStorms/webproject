<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 15:39
 */

class Enigme
{
    private $id;
    private $auteur;
    private $titre;
    private $texte;
    private $image;
    private $date_modif;
    private $date_crea;
    private $etat = array();

    /**
     * @return array
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param array $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }


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
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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



}