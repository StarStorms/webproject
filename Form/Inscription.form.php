<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 13:30
 */

?>

<div class="container">
    <div class="jumbotron">
        <h1>Formulaire d'inscription</h1>

        <p>Remplissez les champs ci-dessous pour pouvoir vous inscrire sur le site.</p>
    </div>
</div>
<div class="container">
    <form action="index.php?page=inscription" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="name">Pseudo (4 caractères min & 25 max) : </label>
            <input type="text" id="name" name="name" placeholder="Votre Pseudo" class="form-control"
                   required <?php if (isset($_POST['name'])) echo 'value="' . $_POST['name'] . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="email">E-mail : </label>
            <input type="email" id="email" name="email" placeholder="Votre e-mail" class="form-control"
                   required <?php if (isset($_POST['email'])) echo 'value="' . $_POST['email'] . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="emailConfirm">E-mail de confirmation: </label>
            <input type="email" id="emailConfirm" name="emailConfirm" placeholder="Réencodez votre e-mail"
                   class="form-control"
                   required <?php if (isset($_POST['emailConfirm'])) echo 'value="' . $_POST['emailConfirm'] . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe (4 caractères min): </label>
            <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required class="form-control">
        </div>
        <div class="form-group">
            <label for="mdpConfirm">Mot de passe de confirmation: </label>
            <input type="password" id="mdpConfirm" name="mdpConfirm" placeholder="Réencodez votre mot de passe"
                   required class="form-control">
        </div>
        <br><br>

        <div align="center" class="g-recaptcha"
             data-sitekey="6Ld1PSUTAAAAAMIy40I9QBhaZkcBBSkmqfS91Jvi"></div>
        <br>
        <button type="submit" class="btn btn-default">Envoyer</button>
    </form>
</div>