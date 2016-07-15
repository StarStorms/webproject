<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 16:22
 */

class UtilisateurManager
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

    public function getAllUser() {
        $resultats = $this->db->query("SELECT * FROM utilisateur");
        $resultats->execute();

        $tabUser = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabUser as $elem)
        {
            $user = new Utilisateur($elem);
            //$user = $this->userDroit($user);
            $tab[] = $user;

        }

        return $tab;
    }

    public function getUserById($id)
    {
        $query = $this->db->prepare("SELECT * FROM utilisateur WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabUser = $query->fetch(PDO::FETCH_ASSOC)) {
            $user = new Utilisateur($tabUser);
            //$user = $this->userDroit($user);
        } else {
            $user = new Utilisateur(array());
        }



        return $user;
    }
    /**
     * Fonction permettant de retrouver un user en fonction de son nom.
     * @param $userName : le nom de l'utilisateur.
     * @return Utilisateur : la classe utilisateur trouvée.
     */
    public function getUserByUserName($userName)
    {
        $query = $this->db->prepare("SELECT * FROM utilisateur WHERE nom = :nom");
        $query->execute(array(
            ":nom" => $userName
        ));

        if($tabUser = $query->fetch(PDO::FETCH_ASSOC))
        {
            $user = new Utilisateur($tabUser);
            //$user = $this->userDroit($user);
        }
        else
        {
            $user = new Utilisateur(array());
        }
        return $user;
    }

    public function getUserByEmail($email)
    {
        $query = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $query->execute(array(
            ":email" => $email
        ));

        if($tabUser = $query->fetch(PDO::FETCH_ASSOC))
        {
            $user = new Utilisateur($tabUser);
            //$user = $this->userDroit($user);
        }
        else
        {
            $user = new Utilisateur(array());
        }
        return $user;
    }

    /**
     * Fonction permettant d'ajouter un utilisateur à la BDD.
     * @param Utilisateur $user : l'utilisateur à ajouter.
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