<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:17
 */
?>

<div class="container">
    <div class="jumbotron">
        <h1>Connexion au site</h1>
    </div>
</div>
<div class="container">
<form action="index.php?page=inscription" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="name">Pseudo : </label>
        <input type="text" id="name" name="name" placeholder="Votre Pseudo" class="form-control" required <?php if(isset($_POST['name']))echo'value="'.$_POST['name'].'"';?>>
    </div>
    <div class="form-group">
        <label for="mdp">Mot de passe : </label>
        <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
    </div>
    <button type="submit" class="btn btn-default">Envoyer</button>
</form>
</div>