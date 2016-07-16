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
    <link rel="stylesheet" type="text/css" href="Style/bootstrap-3.3.6-dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="Style/privateCSS.css" />
</head>
<body>
    <?php
    $conf = parse_ini_file("config.ini.php");
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
            <?php if (isset($_SESSION['Utilisateur'])) { ?>
            <li><a href="index.php?page=enigme"> Enigmes</a></li>
            <li><a href="index.php?page=administration"> Administration</a></li>
            <li><a href="index.php?page=profil"> Profil</a></li>
            <li><a href="index.php?page=deconnexion"> D�connexion</a></li>
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
                include "Form/Inscription.form.php";
            } else if ($_GET['page'] == "connexion") {

                include "Form/Connexion.form.php";
            }

        }
        ?>
    </article>
    <footer>
        <a href="mailto:<?php echo $conf['mail']; ?>">Contacter l'administrateur</a><br>
        © Tous droits réservés à Erwan Samyn, Projet PMM 2016
    </footer>
</body>
