<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */

?>


<form action="index.php?page=inscription" method="post">
    <div class="form-group">
        <label for="name">Pseudo : </label>
        <input type="text" id="name" name="name" placeholder="Votre Pseudo" required <?php if(isset($_POST['name']))echo'value="'.$_POST['name'].'"';?>>
    </div>
    <div class="form-group">
        <label for="email">E-mail : </label>
        <input type="email" id="email" name="email" placeholder="Votre e-mail" required <?php if(isset($_POST['email']))echo'value="'.$_POST['email'].'"';?>>
    </div>
    <div class="form-group">
        <label for="emailConfirm">E-mail de confirmation: </label>
        <input type="email" id="emailConfirm" name="emailConfirm" placeholder="Réencodez votre e-mail" required<?php if(isset($_POST['emailConfirm']))echo'value="'.$_POST['emailConfirm'].'"';?>>
    </div>
    <div class="form-group">
        <label for="mdp">Mot de passe (4 caractéres min): </label>
        <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required>
    </div>
    <div class="form-group">
        <label for="mdpConfirm">Mot de passe de confirmation: </label>
        <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe" required>
    </div>
    <button type="submit" class="btn btn-default">Envoyer</button>
</form>
