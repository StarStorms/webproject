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

    public function userGradeAndRole(Utilisateur $user) {
        $grade = $this->getUserGrade($user);
        $role = $this->getUserRole($user);
        $user->setGrade($grade);
        $user->setRole($role);
        return $user;
    }
    public function getAllUser() {
        $resultats = $this->db->query("SELECT * FROM utilisateur");
        $resultats->execute();

        $tabUser = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabUser as $elem)
        {
            $user = new Utilisateur($elem);
            $user = $this->userGradeAndRole($user);
            $tab[] = $user;

        }

        return $tab;
    }

    public function getUserGrade(Utilisateur $user)
    {
        $gm = new Grademanager(connexionDb());
        $query = $this->db->prepare("SELECT * FROM utilisateur_grade WHERE id_utilisateur = :id");
        $query->execute(array(
            ":id" => $user->getId()
        ));

        $tabGrade = $query->fetchAll(PDO::FETCH_ASSOC);

        $gradeUser = new Grade(array());
        foreach($tabGrade as $elem)
        {
            $gradeUser = $gm->getGradeById($elem['id_grade']);


        }
        return $gradeUser;
    }

    public function setUserGrade(Utilisateur $user, $grade)
    {

        $query = $this->db->prepare("INSERT INTO utilisateur_grade(id_grade, id_utilisateur) values (:idGrade, :idUser)");
        $query->execute(array(
            ":idUser" => $user->getId(),
            ":idGrade" => $grade
        ));
    }

    public function updateUserGrade($idUser, $idGrade)
    {
        $query = $this->db->prepare("UPDATE utilisateur_grade set id_grade = :idGrade WHERE id_utilisateur = :idUser");
        $query->execute(array(
            ":idUser" => $idUser,
            ":idGrade" => $idGrade
        ));
    }

    public function setUserRole(Utilisateur $user, $role)
    {

        $query = $this->db->prepare("INSERT INTO utilisateur_role(id_role, id_utilisateur) values (:idRole, :idUser)");
        $query->execute(array(
            ":idUser" => $user->getId(),
            ":idGrade" => $role
        ));
    }

    public function updateUserRole($idUser, $idRole)
    {
        $query = $this->db->prepare("UPDATE utilisateur_role set id_role = :idRole WHERE id_utilisateur = :idUser");
        $query->execute(array(
            ":idUser" => $idUser,
            ":idRole" => $idRole
        ));
    }

    public function getUserRole(Utilisateur $user)
    {
        $rm = new Rolemanager(connexionDb());
        $query = $this->db->prepare("SELECT * FROM utilisateur_role WHERE id_utilisateur = :id");
        $query->execute(array(
            ":id" => $user->getId()
        ));

        $tabRole = $query->fetchAll(PDO::FETCH_ASSOC);

        $roleUser = new Role(array());
        foreach($tabRole as $elem)
        {
            $roleUser = $rm->getRoleById($elem['id_role']);


        }
        return $roleUser;
    }
    public function getUserById($id)
    {
        $query = $this->db->prepare("SELECT * FROM utilisateur WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabUser = $query->fetch(PDO::FETCH_ASSOC)) {
            $user = new Utilisateur($tabUser);
            $user = $this->userGradeAndRole($user);
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
            $user = $this->userGradeAndRole($user);
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
            $user = $this->userGradeAndRole($user);
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
            ":mdp" => hash("sha256", $user->getMdp())
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