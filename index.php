<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/07/2016
 * Time: 21:28
 */
require "Library/Include.lib.php";


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
        <img src="<?php echo $conf['banniere']; ?>" alt="Bannière du site"><br>
        <h1><?php echo $conf['title']; ?></h1>
        <h2><?php echo $conf['Description']; ?></h2>
    </header>
</body>
