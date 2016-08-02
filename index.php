<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 21:28
 */
require "Library/Include.lib.php";
startSession();

?>

<!DOCTYPE HTML>
<head>
    <title> Site PMM 2016</title>
    <meta charset="UTF-8">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" type="text/css" href="Style/bootstrap-3.3.6-dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="Style/privateCSS.css" />
</head>
<body>
    <?php
    $conf = parse_ini_file("config.ini.php");
    include "Library/Page/QR.lib.php";
    ?>
    <header>
        <img src="<?php echo $conf['banniere']; ?>" alt="Bannière du site" class="img-responsive"><br>
        <h1><?php echo $conf['title']; ?></h1>
        <h2><?php echo $conf['Description']; ?></h2>
    </header>
    <div id="navbar">
        <ul>
            <li><a href="index.php"> Accueil</a></li>
            <li><a href="index.php?page=recherche"> Recherches</a></li>
            <?php if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) { ?>
            <li><a href="index.php?page=enigme"> Enigmes</a></li>
            <li><a href="index.php?page=administration"> Administration</a></li>
            <li><a href="index.php?page=profil"> Profil</a></li>
            <?php requestQR(); ?>
            <li><a href="index.php?page=deconnexion"> Déconnexion</a></li>
            <?php } else { ?>
            <li><a href="index.php?page=inscription"> Inscription</a></li>
            <li><a href="index.php?page=connexion"> Connexion</a></li>
            <?php } ?>


        </ul>
    </div>
    <article>
        <?php
        if(isset($_GET['page']))
        {
            if ($_GET['page'] == "inscription") {
                include "Library/Page/Inscription.lib.php";
                include "Form/Inscription.form.php";
                verifyInscription();
            }
            else if ($_GET['page'] == "connexion") {
                include "Form/Connexion.form.php";
            }
            else if ($_GET['page'] == "connexion_req")
            {
                include "Library/Page/Connexion.lib.php";
                connectUser();
                header("refresh:3;url=index.php" );
            }
            else if ($_GET['page'] == "enigme") {
                //todo
            }
            else if ($_GET['page'] == "administration") {
                //todo
            }
            else if ($_GET['page'] == "deconnexion") {
                session_destroy();
                include "Form/Deconnexion.form.php";
                header("refresh:3;url=index.php" );
            }
            else if ($_GET['page'] == "QR") {
                include "Form/QR.form.php";
            }
            else if ($_GET['page'] == "QR_req") {
                //include "Library/Page/QR.lib.php";
                setQR();
                header("refresh:3;url=index.php" );

            }
            else if ($_GET['page'] == "profil") {
                //todo
            }
            else if ($_GET['page'] == "mdp_perdu") {
                include "Form/mdp_perdu.form.php";
            }
            else if ($_GET['page'] == "mdp_perdu_mail") {
                include "Library/Page/Mdp_perdu.lib.php";
                $res = verifMdpReinitialisation($_POST['user_name']);
                /* TODO A ameliorer : redemander un mdp si erreur */
                if($res == false)
                {
?>
                    <div class="alert alert-danger">
                        <strong>Erreur!</strong> Une erreur est surevenue ! Veuillez recommencer la procédure de récupération de mot de passe.
                    </div>
<?php
                }
            }
            else if ($_GETt['page'] == "activation") {
                include "Library/Inscription.lib.php";
                activateUser();
            }
        }
        ?>
    </article>
    <footer>
        <a href="mailto:<?php echo $conf['mail']; ?>">Contacter l'administrateur</a><br>
        © Tous droits réservés à Erwan Samyn, Projet PMM 2016
    </footer>
</body>
